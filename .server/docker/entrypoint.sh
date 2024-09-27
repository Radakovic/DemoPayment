#!/bin/bash

# If the vendor directory does not exist or is empty, install dependencies
if [ ! -d "vendor" ] || [ -z "$(ls -A vendor)" ]; then
    echo "######################################"
    echo "Installing Composer dependencies..."
    echo "######################################"
    composer install --prefer-dist --no-scripts --no-interaction
    mkdir -p ./var
    chmod -R a+rwx ./vendor ./var
else
    echo "Composer vendor directory already exists and is not empty."
fi
# Install Bootstrap and other assets, only if assets/vendor folder is empty or does not exists
if [ ! -d "assets/vendor" ] || [ -z "$(ls -A assets/vendor)" ]; then
    echo "######################################"
    echo "Installing Assets dependencies..."
    echo "######################################"
    php bin/console importmap:install
else
    echo "Assets vendor directory already exists and is not empty."
fi
# Install Sonata Admin assets if public/assets folder is empty or does not exists
if [ ! -d "public/assets" ] || [ -z "$(ls -A public/assets)" ]; then
    echo "######################################"
    echo "Installing Sonata Admin Assets ..."
    echo "######################################"
    php bin/console asset-map:compile
else
    echo "Sonata admin assets already installed."
fi

    echo "######################################"
    echo "Execute migrations ..."
    echo "######################################"
    php bin/console doctrine:migrations:migrate -n

    echo "######################################"
    echo "Execute fixtures load ..."
    echo "######################################"
    php bin/console doctrine:fixtures:load -n

# Start PHP-FPM (this replaces the CMD in Dockerfile)
php-fpm
