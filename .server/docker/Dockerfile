# Use PHP 8.3 FPM as the base image
FROM php:8.3-fpm
ARG USER=www-data
ARG USER

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
# Install system dependencies for PHP extensions
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions amqp bcmath gd intl opcache pdo_mysql pdo_pgsql sockets xsl zip xdebug pcov

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory inside the container
WORKDIR /app
RUN chown -R ${USER}:${USER} /app

COPY composer.* symfony.* ./
RUN composer install --prefer-dist --no-scripts --no-interaction

RUN	mkdir -p var && chown -R ${USER}:${USER} var

# Copy the entrypoint script
COPY ./.server/docker/entrypoint.sh /usr/local/bin/entrypoint.sh
# Ensure the entrypoint script is executable
RUN chmod +x /usr/local/bin/entrypoint.sh
# Use the entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
