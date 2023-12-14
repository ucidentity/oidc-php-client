FROM php:8.3-apache

RUN apt-get update && apt-get -y upgrade && \
    apt-get install -y wget && \
    apt-get install -y git && \
    apt-get clean && \
    rm -fr /tmp/* /var/tmp/*

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

EXPOSE 80