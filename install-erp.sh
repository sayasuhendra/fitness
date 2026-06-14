#!/bin/bash

set -e

echo "==================================="
echo " Installing Filament Plugins"
echo "==================================="

echo ""
echo "[1/8] Filament Curator"
composer require awcodes/filament-curator

echo ""
echo "[2/8] Filament Table Repeater"
composer require awcodes/filament-table-repeater

echo ""
echo "[3/8] Filament Excel"
composer require pxlrbt/filament-excel

echo ""
echo "[4/8] Laravel Excel"
composer require maatwebsite/excel

echo ""
echo "[5/8] Filament FullCalendar"
composer require saade/filament-fullcalendar

echo ""
echo "[6/8] Filament Phone Input"
composer require ysfkaya/filament-phone-input

echo ""
echo "[7/8] Filament Google Maps"
composer require cheesegrits/filament-google-maps

echo ""
echo "[8/8] Filament Icon Picker"
composer require guava/filament-icon-picker

echo ""
echo "==================================="
echo " Publishing Assets"
echo "==================================="

# Laravel Excel
php artisan vendor:publish \
  --provider="Maatwebsite\Excel\ExcelServiceProvider" \
  --tag=config \
  --force || true

# Curator
php artisan vendor:publish \
  --tag=filament-curator-config \
  --force || true

php artisan vendor:publish \
  --tag=filament-curator-migrations \
  --force || true

# Google Maps
php artisan vendor:publish \
  --tag=filament-google-maps-config \
  --force || true

echo ""
echo "==================================="
echo " Running Migrations"
echo "==================================="

php artisan migrate

echo ""
echo "==================================="
echo " Clearing Cache"
echo "==================================="

php artisan optimize:clear

echo ""
echo "==================================="
echo " Plugin Installation Complete"
echo "==================================="

echo ""
echo "IMPORTANT:"
echo "1. Configure Google Maps API Key"
echo "2. Configure Curator Disk"
echo "3. Configure FullCalendar Settings"
echo "4. Review config/excel.php"
echo ""
