FROM php:7.4-cli
RUN apt-get update && apt-get install --no-install-recommends -y \
  git \
  libzip-dev \
  unzip \
  && apt-get clean && rm -rf /var/lib/apt/lists/*
RUN pecl install xdebug-2.8.1 libzip libxml pcov soap
RUN docker-php-ext-install zip \
  && docker-php-ext-enable xdebug \
  && docker-php-ext-install pdo_mysql \
  && docker-php-ext-install bcmath

RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === '8a6138e2a05a8c28539c9f0fb361159823655d7ad2deecb371b04a83966c61223adc522b0189079e3e9e277cd72b8897') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer
RUN /usr/local/bin/composer global require hirak/prestissimo

WORKDIR /var/www/app
ENTRYPOINT ["/var/www/app/docker_entrypoint.sh"]
CMD []