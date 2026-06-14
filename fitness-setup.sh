#!/usr/bin/env bash

set -Eeuo pipefail

DOMAIN="${DOMAIN:-fitness.dbaik.com}"
APP_NAME="${APP_NAME:-Fitness Akhwat}"
APP_DIR="${APP_DIR:-/var/www/fitness}"
REPO_URL="${REPO_URL:-}"
REPO_BRANCH="${REPO_BRANCH:-main}"
WEB_USER="${WEB_USER:-www-data}"
PHP_VERSION="${PHP_VERSION:-8.4}"
DB_NAME="${DB_NAME:-fitness}"
DB_USER="${DB_USER:-fitness}"
DB_PASSWORD="${DB_PASSWORD:-$(openssl rand -base64 32 | tr -d '/+=' | cut -c1-24)}"
APP_ENV="${APP_ENV:-production}"
APP_DEBUG="${APP_DEBUG:-false}"
RUN_SEEDERS="${RUN_SEEDERS:-false}"
FORCE_ENV="${FORCE_ENV:-false}"

log() {
    printf "\n\033[1;32m==>\033[0m %s\n" "$1"
}

warn() {
    printf "\n\033[1;33mWARN:\033[0m %s\n" "$1"
}

need_root() {
    if [[ "${EUID}" -ne 0 ]]; then
        echo "Please run with sudo/root: sudo bash fitness-setup.sh"
        exit 1
    fi
}

run_as_web() {
    sudo -u "${WEB_USER}" bash -lc "cd '${APP_DIR}' && $*"
}

detect_php_fpm_service() {
    if systemctl list-unit-files "php${PHP_VERSION}-fpm.service" >/dev/null 2>&1; then
        echo "php${PHP_VERSION}-fpm"
        return
    fi

    local service
    service="$(systemctl list-unit-files 'php*-fpm.service' --no-legend 2>/dev/null | awk '{print $1}' | sed 's/.service$//' | sort -V | tail -n1 || true)"
    if [[ -n "${service}" ]]; then
        echo "${service}"
        return
    fi

    echo "php${PHP_VERSION}-fpm"
}

install_packages() {
    log "Installing OS packages"

    export DEBIAN_FRONTEND=noninteractive
    apt-get update
    apt-get install -y \
        ca-certificates \
        curl \
        gnupg \
        unzip \
        git \
        acl \
        supervisor \
        postgresql \
        postgresql-contrib \
        caddy \
        "php${PHP_VERSION}-fpm" \
        "php${PHP_VERSION}-cli" \
        "php${PHP_VERSION}-pgsql" \
        "php${PHP_VERSION}-mbstring" \
        "php${PHP_VERSION}-xml" \
        "php${PHP_VERSION}-curl" \
        "php${PHP_VERSION}-zip" \
        "php${PHP_VERSION}-bcmath" \
        "php${PHP_VERSION}-intl" \
        "php${PHP_VERSION}-gd" \
        "php${PHP_VERSION}-redis"

    if ! command -v composer >/dev/null 2>&1; then
        log "Installing Composer"
        curl -fsSL https://getcomposer.org/installer -o /tmp/composer-setup.php
        php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
        rm -f /tmp/composer-setup.php
    fi

    if ! command -v node >/dev/null 2>&1; then
        log "Installing Node.js 22"
        curl -fsSL https://deb.nodesource.com/setup_22.x | bash -
        apt-get install -y nodejs
    fi
}

prepare_code() {
    log "Preparing application code in ${APP_DIR}"

    mkdir -p "${APP_DIR}"

    if [[ -n "${REPO_URL}" ]]; then
        if [[ -d "${APP_DIR}/.git" ]]; then
            git -C "${APP_DIR}" fetch --all --prune
            git -C "${APP_DIR}" checkout "${REPO_BRANCH}"
            git -C "${APP_DIR}" pull --ff-only origin "${REPO_BRANCH}"
        else
            rm -rf "${APP_DIR:?}/"*
            git clone --branch "${REPO_BRANCH}" "${REPO_URL}" "${APP_DIR}"
        fi
    else
        warn "REPO_URL is empty. Using files already present in ${APP_DIR}."
        warn "To deploy from Git: REPO_URL=git@github.com:org/repo.git sudo -E bash fitness-setup.sh"
    fi

    chown -R "${WEB_USER}:${WEB_USER}" "${APP_DIR}"
}

configure_postgres() {
    log "Configuring PostgreSQL database ${DB_NAME}"

    systemctl enable --now postgresql

    sudo -u postgres psql -v ON_ERROR_STOP=1 <<SQL
DO \$\$
BEGIN
    IF NOT EXISTS (SELECT FROM pg_catalog.pg_roles WHERE rolname = '${DB_USER}') THEN
        CREATE ROLE ${DB_USER} LOGIN PASSWORD '${DB_PASSWORD}';
    ELSE
        ALTER ROLE ${DB_USER} WITH LOGIN PASSWORD '${DB_PASSWORD}';
    END IF;
END
\$\$;

SELECT 'CREATE DATABASE ${DB_NAME} OWNER ${DB_USER}'
WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = '${DB_NAME}')\\gexec
GRANT ALL PRIVILEGES ON DATABASE ${DB_NAME} TO ${DB_USER};
SQL
}

write_env() {
    log "Writing Laravel environment"

    if [[ -f "${APP_DIR}/.env" && "${FORCE_ENV}" != "true" ]]; then
        warn "${APP_DIR}/.env already exists. Keeping it. Set FORCE_ENV=true to regenerate."
        return
    fi

    local app_key
    app_key="$(php -r 'echo "base64:".base64_encode(random_bytes(32));')"

    cat > "${APP_DIR}/.env" <<ENV
APP_NAME="${APP_NAME}"
APP_ENV=${APP_ENV}
APP_KEY=${app_key}
APP_DEBUG=${APP_DEBUG}
APP_URL=https://${DOMAIN}

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=info

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=${DB_NAME}
DB_USERNAME=${DB_USER}
DB_PASSWORD=${DB_PASSWORD}

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=.${DOMAIN}

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@${DOMAIN}"
MAIL_FROM_NAME="\${APP_NAME}"

SANCTUM_STATEFUL_DOMAINS=${DOMAIN}
VITE_APP_NAME="\${APP_NAME}"
ENV

    chown "${WEB_USER}:${WEB_USER}" "${APP_DIR}/.env"
    chmod 640 "${APP_DIR}/.env"
}

build_app() {
    log "Installing PHP dependencies"
    run_as_web "composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader"

    log "Installing Node dependencies and building frontend assets"
    if [[ -f "${APP_DIR}/package-lock.json" ]]; then
        run_as_web "npm ci"
    else
        run_as_web "npm install"
    fi
    run_as_web "npm run build"

    log "Preparing Laravel storage and database"
    mkdir -p "${APP_DIR}/storage" "${APP_DIR}/bootstrap/cache"
    chown -R "${WEB_USER}:${WEB_USER}" "${APP_DIR}/storage" "${APP_DIR}/bootstrap/cache"
    chmod -R ug+rwX "${APP_DIR}/storage" "${APP_DIR}/bootstrap/cache"

    run_as_web "php artisan storage:link || true"
    run_as_web "php artisan migrate --force"

    if [[ "${RUN_SEEDERS}" == "true" ]]; then
        run_as_web "php artisan db:seed --force"
    fi

    run_as_web "php artisan filament:assets"
    run_as_web "php artisan optimize:clear"
    run_as_web "php artisan config:cache"
    run_as_web "php artisan route:cache"
    run_as_web "php artisan view:cache"
    run_as_web "php artisan filament:cache-components"
}

configure_caddy() {
    log "Configuring Caddy for ${DOMAIN}"

    local php_fpm_service
    local php_fpm_socket
    php_fpm_service="$(detect_php_fpm_service)"
    php_fpm_socket="/run/php/${php_fpm_service}.sock"

    cat > "/etc/caddy/Caddyfile" <<CADDY
${DOMAIN} {
    root * ${APP_DIR}/public
    encode zstd gzip
    php_fastcgi unix/${php_fpm_socket}
    file_server

    header {
        Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"
        X-Content-Type-Options "nosniff"
        X-Frame-Options "SAMEORIGIN"
        Referrer-Policy "strict-origin-when-cross-origin"
        Permissions-Policy "camera=(), microphone=(), geolocation=()"
    }

    @hidden {
        path /.env
        path /.git/*
        path /composer.*
        path /package*.json
    }
    respond @hidden 404
}
CADDY

    caddy validate --config /etc/caddy/Caddyfile
    systemctl enable --now "${php_fpm_service}"
    systemctl enable --now caddy
    systemctl reload caddy
}

configure_workers() {
    log "Configuring Laravel queue worker and scheduler"

    cat > /etc/systemd/system/fitness-queue.service <<UNIT
[Unit]
Description=Fitness Akhwat Laravel Queue Worker
After=network.target postgresql.service

[Service]
User=${WEB_USER}
Group=${WEB_USER}
Restart=always
WorkingDirectory=${APP_DIR}
ExecStart=/usr/bin/php ${APP_DIR}/artisan queue:work --sleep=3 --tries=3 --timeout=90

[Install]
WantedBy=multi-user.target
UNIT

    cat > /etc/systemd/system/fitness-scheduler.service <<UNIT
[Unit]
Description=Fitness Akhwat Laravel Scheduler

[Service]
User=${WEB_USER}
Group=${WEB_USER}
WorkingDirectory=${APP_DIR}
ExecStart=/usr/bin/php ${APP_DIR}/artisan schedule:run
UNIT

    cat > /etc/systemd/system/fitness-scheduler.timer <<UNIT
[Unit]
Description=Run Fitness Akhwat Laravel Scheduler every minute

[Timer]
OnCalendar=*-*-* *:*:00
Persistent=true

[Install]
WantedBy=timers.target
UNIT

    systemctl daemon-reload
    systemctl enable --now fitness-queue.service
    systemctl enable --now fitness-scheduler.timer
}

print_summary() {
    log "Deployment complete"
    cat <<SUMMARY
Domain:        https://${DOMAIN}
App dir:       ${APP_DIR}
Database:      ${DB_NAME}
DB user:       ${DB_USER}
DB password:   ${DB_PASSWORD}

Useful commands:
  sudo systemctl status caddy
  sudo systemctl status fitness-queue
  sudo systemctl status fitness-scheduler.timer
  sudo -u ${WEB_USER} php ${APP_DIR}/artisan optimize:clear

Admin seed login, if RUN_SEEDERS=true:
  admin@fitnessakhwat.test / password
SUMMARY
}

main() {
    need_root
    install_packages
    prepare_code
    configure_postgres
    write_env
    build_app
    configure_caddy
    configure_workers
    print_summary
}

main "$@"
