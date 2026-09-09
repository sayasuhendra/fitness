#!/usr/bin/env bash
set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

if [ -d "$SCRIPT_DIR/fitness" ]; then
    APP_DIR="$SCRIPT_DIR/fitness"
elif [ -f "$SCRIPT_DIR/artisan" ]; then
    APP_DIR="$SCRIPT_DIR"
else
    echo "Error: Cannot locate Laravel directory (fitness or artisan)."
    exit 1
fi

cd "$APP_DIR"

php artisan instructors:delete-permanent "$@"
