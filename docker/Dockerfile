FROM php:8.2-cli-alpine

WORKDIR /var/www/html

ENV WWWUSER=sail \
    WWWGROUP=sail

# Install essential packages
RUN apk --no-cache add -t .production-deps \
    supervisor libpq

# Install and configure bash
RUN apk --no-cache add bash \
    && sed -i 's/bin\/ash/bin\/bash/g' /etc/passwd

# Install and configure PHP extensions and timezone
RUN set -xe \
    && apk --no-cache add -t .build-deps $PHPIZE_DEPS \
    libpq-dev alpine-conf linux-headers \
    && setup-timezone -z UTC \
    && pecl install -f apcu redis xdebug \
    && docker-php-source extract \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install -j$(nproc) pdo_pgsql pgsql pcntl \
    && docker-php-ext-enable apcu redis xdebug \
    && docker-php-source delete \
    && pecl clear-cache \
    && rm -rf /tmp/* \
    && apk del --purge .build-deps
COPY php.ini /etc/php/8.0/cli/conf.d/99-sail.ini

# Configure supervisord
RUN touch /var/run/supervisord.pid \
    && mkdir -p /etc/supervisor.d/conf.d \
    && mkdir -p /var/log/supervisor \
    && touch /var/log/supervisor/supervisord.log
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Configure container startup script
COPY start-container /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

# Configure user for sail
RUN addgroup sail \
    && adduser -h /var/www/html -s /bin/bash -G sail -D sail

# Install composer binary
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

EXPOSE 80
ENTRYPOINT ["start-container"]
