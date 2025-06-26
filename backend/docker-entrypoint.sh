#!/bin/sh

# Permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Run PHP-FPM
exec php-fpm
