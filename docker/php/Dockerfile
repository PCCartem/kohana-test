FROM php:7.2-fpm

ARG PHP_ENTRYPOINT_NAME
ARG PHP_CRON_NAME
ADD ./php.ini /usr/local/etc/php/php.ini

RUN apt-get update \
    && apt-get install -y \
        curl \
        wget \
        git \
        cron \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libssh-dev \
        libpq-dev \
        libxslt-dev \
        libicu-dev \
        libpng-dev \
        libxml2-dev \
        libgearman-dev \
        libevent-dev \
    && docker-php-ext-install -j$(nproc) iconv mbstring pgsql zip bcmath \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install xsl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && cd /tmp \
    && git clone https://github.com/wcgallego/pecl-gearman.git \
    && cd pecl-gearman \
    && git checkout gearman-2.0.3 \
    && phpize \
    && ./configure \
    && make -j$(nproc) \
    && make install \
    && docker-php-ext-enable gearman

RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_pgsql

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN rm /bin/sh && ln -s /bin/bash /bin/sh

# nvm environment variables
ENV NVM_DIR /usr/local/nvm
ENV NODE_VERSION 12.16.2

# install nvm
# https://github.com/creationix/nvm#install-script
RUN curl --silent -o- https://raw.githubusercontent.com/creationix/nvm/v0.31.2/install.sh | bash

# install node and npm
RUN source $NVM_DIR/nvm.sh \
    && nvm install $NODE_VERSION \
    && nvm alias default $NODE_VERSION \
    && nvm use default

# add node and npm to path so the commands are available
ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH

COPY $PHP_ENTRYPOINT_NAME /usr/local/bin/docker-entrypoint.sh

COPY $PHP_CRON_NAME /root/cronci

WORKDIR /var/www/application

ENTRYPOINT ["bash", "docker-entrypoint.sh"]

CMD ["php-fpm"]