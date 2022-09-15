FROM yiisoftware/yii2-php:7.4-apache

# Install Xdebug
RUN pecl install -f xdebug && docker-php-ext-enable xdebug

COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY docker/php/php.ini /usr/local/etc/php/conf.d/php.ini
RUN touch /tmp/xdebug.log
RUN chmod -R 777 /tmp/xdebug.log

RUN apt update
RUN apt install -y vim