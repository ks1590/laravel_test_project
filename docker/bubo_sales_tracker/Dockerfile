FROM php:7.4-apache

RUN apt-get update
RUN apt-get install -y zlib1g-dev libzip-dev zip
RUN docker-php-ext-install zip

RUN a2enmod ssl
EXPOSE 443

RUN docker-php-ext-install pdo_mysql mysqli

RUN apt-get install -y libxml2-dev libpng-dev
RUN docker-php-ext-install xml gd iconv simplexml

RUN apt-get install -y vim sudo

RUN useradd -m preapp_dev && echo "preapp_dev:Predevapp_1" | chpasswd && adduser preapp_dev sudo

RUN usermod -a -G www-data preapp_dev
RUN usermod -a -G preapp_dev www-data

COPY --from=composer /usr/bin/composer /usr/bin/composer

ADD php.ini /usr/local/etc/php/
COPY bubo_sales_tracker.conf /etc/apache2/sites-enabled/000-default.conf
COPY laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin
RUN composer global require "laravel/installer"
RUN a2enmod rewrite

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# cronのインストール
RUN apt-get install -y cron

# crontabは、末尾が改行されていないとダメ。
ADD crontab /etc/cron.d/bubo_sales_tracker_batch

ARG ENVIRONMENT

# パーミッションの変更。
RUN chmod 0644 /etc/cron.d/bubo_sales_tracker_batch

RUN apt-get install -y \
    apt-transport-https \
    libpcre3 \
    libpcre3-dev \
    supervisor \
    ca-certificates \
    curl \
    oathtool \
    unzip \
    --no-install-recommends

RUN pecl install oauth

RUN apt-get update && apt-get install -y \
    curl \
    gnupg \

RUN curl -sL https://deb.nodesource.com/setup_14.x | bash -
RUN apt-get -y install --no-install-recommends python build-essential nodejs npm && npm install -g yarn

RUN apt install -y default-mysql-client

ENV TZ=Asia/Tokyo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone