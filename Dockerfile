FROM php:7.0-apache
LABEL owner="Giancarlos Salas"
LABEL maintainer="giansalex@gmail.com"

RUN apt-get update && apt-get install -y --no-install-recommends \
    zlib1g-dev \
    git \
    zip \
    unzip && \
    docker-php-ext-install pdo_mysql zip opcache && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    a2enmod rewrite && \
    curl --silent --show-error -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV SUFEL_DB_HOST 127.0.0.1
ENV SUFEL_DB_DATABASE sufel
ENV SUFEL_DB_USER root
ENV SUFEL_DB_PASS ""
ENV SUFEL_JWT_KEY yYa3Nmalk1a56fhA
ENV SUFEL_ADMIN jsAkl34Oa2Tyu

COPY . /var/www/html/

VOLUME /var/www/html/public/upload

WORKDIR /var/www/html

RUN chmod -R 777 logs/ && \
    chmod -R 777 public/upload/ && \
    cp -f docker/.htaccess . && \
    cp -f docker/settings.php src/ && \
    composer install --no-interaction --no-dev --optimize-autoloader && \
    composer dump-autoload --optimize --no-dev --classmap-authoritative