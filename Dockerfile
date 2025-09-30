FROM php:8.3-apache@sha256:fc959fc1a4cb498c3c54b3c4da68c289f7dc82d92ca22280d307213ab6ddd0dd

RUN apt-get update && apt-get -y upgrade && \
    apt-get install -y wget && \
    apt-get install -y git && \
    apt-get clean && \
    rm -fr /tmp/* /var/tmp/*

# Uncomment below for debug config
# RUN pecl install xdebug \
#     && docker-php-ext-enable xdebug
# COPY ./conf.d/error_reporting.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# COPY ./conf.d/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Set up php composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

COPY ./src/*.php /var/www/html
COPY ./src/*.json /var/www/html

# Install composer requirements
RUN cd /var/www/html \
    && composer update

EXPOSE 80 9003