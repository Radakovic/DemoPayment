#!/bin/bash

# If the vendor directory does not exist or is empty, install dependencies
if [ ! -d "vendor" ] || [ -z "$(ls -A vendor)" ]; then
    echo "Installing Composer dependencies..."
    composer install --prefer-dist --no-scripts --no-interaction
    mkdir -p ./var
    chmod -R a+rwx ./vendor ./var
else
    echo "Vendor directory already exists and is not empty."
fi

# Start PHP-FPM (this replaces the CMD in Dockerfile)
php-fpm
