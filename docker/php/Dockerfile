FROM php:7.2-zts

#RUN pecl install pthreads-3.1.6

RUN apt-get update && \
    apt-get -y install \
        gnupg2 && \
    apt-key update && \
    apt-get update && \
    apt-get -y install \
            g++ \
            git \
            curl \
            imagemagick \
            libcurl3-dev \
            libicu-dev \
            libfreetype6-dev \
            libjpeg-dev \
            libjpeg62-turbo-dev \
            libonig-dev \
            libmagickwand-dev \
            libpq-dev \
            libpng-dev \
            libxml2-dev \
            libzip-dev \
            zlib1g-dev \
            default-mysql-client \
            openssh-client \
            nano \
            unzip \
            libcurl4-openssl-dev \
            libssl-dev \
            wget \
        --no-install-recommends && \
        apt-get clean && \
        rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN wget https://github.com/krakjoe/pthreads/archive/v3.2.0.zip && \
    mv v3.2.0.zip /tmp && \
    unzip /tmp/v3.2.0.zip -d /tmp && \
    docker-php-ext-configure /tmp/pthreads-3.2.0 --enable-pthreads && \
    docker-php-ext-install /tmp/pthreads-3.2.0 && docker-php-ext-enable pthreads

RUN docker-php-ext-install \
        soap \
        zip \
        curl \
        bcmath \
        exif \
        gd \
        iconv \
        intl \
        mbstring \
        opcache \
        pdo_mysql

WORKDIR /app

CMD ["php", "-a"]