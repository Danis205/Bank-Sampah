#!/bin/bash

# Exit on fail
set -e

# --- START OF FIX ---
# Run this EVERY time the container starts to guarantee clean config
echo "Cleaning up Apache MPM configurations..."
rm -f /etc/apache2/mods-enabled/mpm_event.load
rm -f /etc/apache2/mods-enabled/mpm_event.conf
rm -f /etc/apache2/mods-enabled/mpm_worker.load
rm -f /etc/apache2/mods-enabled/mpm_worker.conf
# Ensure the correct one is enabled
a2enmod mpm_prefork || true
# --- END OF FIX ---

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Start Apache
echo "Starting Apache..."
exec apache2-foreground