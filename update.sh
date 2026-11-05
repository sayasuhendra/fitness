#!/usr/bin/env bash
# ==============================================================================
# Akhwat Gym (fitness.dbaik.com) - Production Server Update Script
# ==============================================================================
set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Determine script & application directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

if [ -f "$SCRIPT_DIR/artisan" ]; then
    APP_DIR="$SCRIPT_DIR"
elif [ -d "$SCRIPT_DIR/fitness" ] && [ -f "$SCRIPT_DIR/fitness/artisan" ]; then
    APP_DIR="$SCRIPT_DIR/fitness"
else
    echo -e "${RED}Error: Cannot locate Laravel directory (artisan file not found).${NC}"
    exit 1
fi

cd "$APP_DIR"

echo -e "${BLUE}====================================================${NC}"
echo -e "${BLUE}   Akhwat Gym Server Update Script                 ${NC}"
echo -e "${BLUE}   Target Directory: ${APP_DIR}                     ${NC}"
echo -e "${BLUE}====================================================${NC}"

# Ensure site is brought back up even if an error occurs midway
cleanup() {
    EXIT_CODE=$?
    if [ $EXIT_CODE -ne 0 ]; then
        echo -e "\n${RED}[!] Update encountered an error (exit code: $EXIT_CODE).${NC}"
        echo -e "${YELLOW}[*] Bringing application back online...${NC}"
        php artisan up || true
    fi
}
trap cleanup EXIT

# 1. Activate Maintenance Mode
echo -e "\n${YELLOW}[1/8] Putting application into maintenance mode...${NC}"
php artisan down --render="errors::503" || true

# 2. Pull latest changes from Git
echo -e "\n${YELLOW}[2/8] Pulling latest code from Git repository...${NC}"
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo "main")
echo -e "      Current branch: ${GREEN}${CURRENT_BRANCH}${NC}"
git pull origin "$CURRENT_BRANCH"

# 3. Install/Update PHP Dependencies
echo -e "\n${YELLOW}[3/8] Installing PHP Composer dependencies...${NC}"
if [ -f "composer.phar" ]; then
    php composer.phar install --no-interaction --prefer-dist --optimize-autoloader --no-dev
else
    composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
fi

# 4. Run Database Migrations
echo -e "\n${YELLOW}[4/8] Running database migrations...${NC}"
php artisan migrate --force

# 5. Build Frontend Assets (Vite)
echo -e "\n${YELLOW}[5/8] Building frontend assets (Vite / Tailwind)...${NC}"
if command -v npm >/dev/null 2>&1; then
    npm install --no-audit --prefer-offline
    npm run build
else
    echo -e "${YELLOW}      Node/npm not found on PATH. Skipping npm run build.${NC}"
fi

# 6. Publish Filament & Laravel Assets
echo -e "\n${YELLOW}[6/8] Upgrading Filament & publishing assets...${NC}"
php artisan filament:upgrade || true
php artisan storage:link || true

# 7. Clear & Rebuild Application Caches
echo -e "\n${YELLOW}[7/8] Optimizing and caching configuration, routes, and views...${NC}"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Restart queue workers if running
php artisan queue:restart || true

# 8. Deactivate Maintenance Mode
echo -e "\n${YELLOW}[8/8] Bringing application back online...${NC}"
php artisan up

echo -e "\n${GREEN}====================================================${NC}"
echo -e "${GREEN}✓ Server update completed successfully!             ${NC}"
echo -e "${GREEN}====================================================${NC}"
