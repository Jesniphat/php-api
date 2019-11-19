FROM php:7.3-apache
# NGINX
# FROM php:7.3-fpm

# Disable when use nginx
RUN a2enmod rewrite

#Install คำสั่งสำหรับการลง package ที่ laravel required ไว้นะครับ
RUN apt-get update \
  && apt-get install -y \
    cron \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxslt1-dev \
    libzip-dev \
    openssh-server \
    openssh-client \
    rsync

RUN docker-php-ext-configure \
  gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/

# ตามด้วยคำสั่งสำหรับการลง php extension ครับ
RUN docker-php-ext-install \
  bcmath \
  gd \
  intl \
  mbstring \
  pdo_mysql \
  soap \
  xsl \
  zip

RUN apt-get install libmcrypt-dev libreadline-dev -y

RUN pecl install mcrypt-1.0.2 \
    && pecl install redis-5.0.0 \
    && pecl install xdebug-2.7.2 \
    && docker-php-ext-enable mcrypt redis xdebug

# อันนี้ลงเพิ่มเติมคือ composer นั้นเอง เป็นตัว package manager สำหรับการจัดการพวก dependency ของภาษา php ครับ
RUN curl -sS https://getcomposer.org/installer | \
  php -- --install-dir=/usr/local/bin --filename=composer

# ให้ Composer run บน super user ได้
RUN echo 'export COMPOSER_ALLOW_SUPERUSER=1' >> ~/.bashrc

RUN echo date.timezone=Asia/Bangkok > /usr/local/etc/php/conf.d/timezone.ini

# Enable when use nginx
# EXPOSE 9000
# CMD ["php-fpm"]