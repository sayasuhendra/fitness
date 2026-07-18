#!/usr/bin/env bash

set -Eeuo pipefail

DOMAIN="${DOMAIN:-fitness.dbaik.com}"
APP_DIR="${APP_DIR:-/var/www/fitness}"
WEB_USER="${WEB_USER:-www-data}"
REPO_BRANCH="${REPO_BRANCH:-main}"
RUN_DEMO_USERS="${RUN_DEMO_USERS:-true}"
RUN_SEEDERS="${RUN_SEEDERS:-false}"
INSTALL_SYSTEMD_SERVICES="${INSTALL_SYSTEMD_SERVICES:-true}"
SKIP_GIT_PULL="${SKIP_GIT_PULL:-false}"
NPM_CACHE="${NPM_CACHE:-${APP_DIR}/.npm-cache}"
COMPOSER_HOME="${COMPOSER_HOME:-${APP_DIR}/.composer}"
PHP_BIN="${PHP_BIN:-$(command -v php || true)}"

log() {
    printf "\n\033[1;32m==>\033[0m %s\n" "$1"
}

warn() {
    printf "\n\033[1;33mWARN:\033[0m %s\n" "$1"
}

fail() {
    printf "\n\033[1;31mERROR:\033[0m %s\n" "$1"
    exit 1
}

need_root() {
    if [[ "${EUID}" -ne 0 ]]; then
        fail "Please run with sudo/root: sudo bash fitness-setup.sh"
    fi
}

assert_ready() {
    [[ -d "${APP_DIR}" ]] || fail "APP_DIR does not exist: ${APP_DIR}"
    [[ -d "${APP_DIR}/.git" ]] || fail "${APP_DIR} is not a git repository. Clone it first."
    [[ -f "${APP_DIR}/artisan" ]] || fail "Laravel artisan file not found in ${APP_DIR}"
    [[ -f "${APP_DIR}/.env" ]] || fail ".env not found in ${APP_DIR}. Create it before deploy."

    command -v git >/dev/null 2>&1 || fail "git is not installed"
    command -v composer >/dev/null 2>&1 || fail "composer is not installed"
    command -v npm >/dev/null 2>&1 || fail "npm is not installed"
    [[ -n "${PHP_BIN}" ]] || fail "php is not installed"
}

run_as_web() {
    sudo -u "${WEB_USER}" \
        env HOME="${APP_DIR}" COMPOSER_HOME="${COMPOSER_HOME}" NPM_CONFIG_CACHE="${NPM_CACHE}" \
        bash -lc "cd '${APP_DIR}' && $*"
}

pull_latest_code() {
    if [[ "${SKIP_GIT_PULL}" == "true" ]]; then
        warn "Skipping git pull because SKIP_GIT_PULL=true"
        return
    fi

    log "Pulling latest code from ${REPO_BRANCH}"
    git -C "${APP_DIR}" fetch --all --prune
    git -C "${APP_DIR}" checkout "${REPO_BRANCH}"
    git -C "${APP_DIR}" pull --ff-only origin "${REPO_BRANCH}"
}

prepare_permissions() {
    log "Preparing writable directories"
    mkdir -p \
        "${APP_DIR}/storage" \
        "${APP_DIR}/bootstrap/cache" \
        "${APP_DIR}/public/build" \
        "${COMPOSER_HOME}" \
        "${NPM_CACHE}"

    chown -R "${WEB_USER}:${WEB_USER}" \
        "${APP_DIR}/storage" \
        "${APP_DIR}/bootstrap/cache" \
        "${APP_DIR}/public/build" \
        "${COMPOSER_HOME}" \
        "${NPM_CACHE}"

    chmod -R ug+rwX "${APP_DIR}/storage" "${APP_DIR}/bootstrap/cache" "${APP_DIR}/public/build"
}

install_backend_dependencies() {
    log "Installing PHP dependencies"
    run_as_web "composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader"
}

build_frontend_assets() {
    log "Installing Node dependencies and building frontend assets"

    rm -rf "${APP_DIR}/node_modules"
    mkdir -p "${APP_DIR}/public/build" "${NPM_CACHE}"
    chown -R "${WEB_USER}:${WEB_USER}" \
        "${APP_DIR}/public/build" \
        "${NPM_CACHE}"
    chmod -R ug+rwX "${APP_DIR}/public/build"

    if [[ -f "${APP_DIR}/package-lock.json" ]]; then
        run_as_web "npm ci --no-audit --no-fund --cache '${NPM_CACHE}'"
    else
        run_as_web "npm install --no-audit --no-fund --cache '${NPM_CACHE}'"
    fi

    run_as_web "npm run build"

    chown -R "${WEB_USER}:${WEB_USER}" "${APP_DIR}/public/build"
    chmod -R ug+rwX "${APP_DIR}/public/build"
}

run_laravel_deploy_steps() {
    log "Running Laravel deploy steps"

    run_as_web "php artisan down --render='errors::503' || true"
    run_as_web "php artisan optimize:clear"
    run_as_web "php artisan storage:link || true"
    run_as_web "php artisan migrate --force"
    run_as_web "php artisan db:seed --class=AdminRoleSeeder --force"

    if [[ "${RUN_DEMO_USERS}" == "true" ]]; then
        run_as_web "php artisan db:seed --class=DemoUserSeeder --force"
    fi

    if [[ "${RUN_SEEDERS}" == "true" ]]; then
        run_as_web "php artisan db:seed --force"
    fi

    run_as_web "php artisan filament:assets"
    run_as_web "php artisan config:cache"
    run_as_web "php artisan route:cache"
    run_as_web "php artisan view:cache"
    run_as_web "php artisan filament:cache-components"
    run_as_web "php artisan up"
}

install_systemd_services() {
    if [[ "${INSTALL_SYSTEMD_SERVICES}" != "true" ]]; then
        warn "Skipping systemd services because INSTALL_SYSTEMD_SERVICES=false"
        return
    fi

    if ! command -v systemctl >/dev/null 2>&1; then
        warn "systemctl not found. Skipping queue and scheduler service installation."
        return
    fi

    log "Installing queue and scheduler systemd services"

    cat >/etc/systemd/system/fitness-queue.service <<SERVICE
[Unit]
Description=Akhwat Gym Laravel Queue Worker
After=network.target

[Service]
Type=simple
User=${WEB_USER}
Group=${WEB_USER}
WorkingDirectory=${APP_DIR}
ExecStart=${PHP_BIN} ${APP_DIR}/artisan queue:work database --sleep=3 --tries=3 --timeout=90 --max-time=3600
Restart=always
RestartSec=5
KillSignal=SIGTERM
TimeoutStopSec=90
StandardOutput=journal
StandardError=journal

[Install]
WantedBy=multi-user.target
SERVICE

    cat >/etc/systemd/system/fitness-scheduler.service <<SERVICE
[Unit]
Description=Akhwat Gym Laravel Scheduler
After=network.target

[Service]
Type=oneshot
User=${WEB_USER}
Group=${WEB_USER}
WorkingDirectory=${APP_DIR}
ExecStart=${PHP_BIN} ${APP_DIR}/artisan schedule:run
StandardOutput=journal
StandardError=journal
SERVICE

    cat >/etc/systemd/system/fitness-scheduler.timer <<TIMER
[Unit]
Description=Run Akhwat Gym Laravel Scheduler Every Minute

[Timer]
OnCalendar=*-*-* *:*:00
Persistent=true
Unit=fitness-scheduler.service

[Install]
WantedBy=timers.target
TIMER

    systemctl daemon-reload
    systemctl enable --now fitness-queue.service
    systemctl enable --now fitness-scheduler.timer
}

reload_services() {
    log "Reloading application services"

    if systemctl list-unit-files caddy.service >/dev/null 2>&1; then
        caddy validate --config /etc/caddy/Caddyfile
        systemctl reload caddy
    else
        warn "Caddy service not found. Skipping Caddy reload."
    fi

    if systemctl list-unit-files fitness-queue.service >/dev/null 2>&1; then
        systemctl restart fitness-queue.service
    else
        warn "fitness-queue.service not found. Skipping queue restart."
    fi

    if systemctl list-unit-files fitness-scheduler.timer >/dev/null 2>&1; then
        systemctl restart fitness-scheduler.timer
    else
        warn "fitness-scheduler.timer not found. Skipping scheduler timer restart."
    fi
}

print_summary() {
    log "Deploy complete"
    cat <<SUMMARY
Domain:  https://${DOMAIN}
App dir: ${APP_DIR}
Branch:  ${REPO_BRANCH}

Useful checks:
  sudo systemctl status caddy
  sudo systemctl status fitness-queue
  sudo systemctl status fitness-scheduler.timer
  sudo -u ${WEB_USER} php ${APP_DIR}/artisan about
SUMMARY
}

main() {
    need_root
    assert_ready
    pull_latest_code
    prepare_permissions
    install_backend_dependencies
    build_frontend_assets
    run_laravel_deploy_steps
    install_systemd_services
    reload_services
    print_summary
}

main "$@"
