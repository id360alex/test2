FROM php:8.2.0-fpm AS php-fpm

# common extensions
RUN apt-get update \
    && apt-get install -y libpq-dev zlib1g-dev g++ nano vim net-tools telnet libicu-dev git zip unzip libzip-dev libxml2-dev libssh-dev \
    && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && docker-php-ext-install -j$(nproc) intl iconv pdo_pgsql gd exif opcache zip pcntl sockets

# config
COPY docker/php/php.ini $PHP_INI_DIR/conf.d/00-php.ini

VOLUME /var/shared
RUN mkdir -p /var/shared && chmod 0777 /var/shared

##### PHP-CLI #####
FROM php-fpm AS php-cli

# Allow Composer to be run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Install Composer
RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
    && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
    && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
    && php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /tmp/composer-setup.php \
    && composer --version

ENTRYPOINT ["php"]
CMD ["-v"]

##### PHP-SUPERVISOR #####
#FROM php-cli AS php-supervisor
#
#RUN apt-get install -y supervisor
#
#COPY docker/php/supervisor/consumer.conf /etc/supervisor/conf.d/consumer.conf
#
#ENTRYPOINT ["/usr/bin/supervisord"]
