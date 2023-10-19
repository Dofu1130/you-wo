FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive

WORKDIR /var/www/html

RUN apt-get update && \
    apt-get install -y git \
    zip \
    curl \
    apt-utils \
    software-properties-common

RUN add-apt-repository -y ppa:ondrej/php && \
    apt-get update && \
    apt-get install -y apache2 \
    php8.1 \
    php8.1-curl \
    php8.1-mbstring \
    php8.1-cli \
    php8.1-xml \
    php8.1-zip \
    php8.1-mysql \
    php8.1-gd \
    php8.1-bcmath \
    php8.1-opcache \
    php8.1-readline \
    php8.1-tokenizer \
    libapache2-mod-php8.1

# Apache設置
COPY config/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Install Composer and extensions
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer self-update --2

# Set work folder permission
RUN usermod -u 1000 www-data

# Install NPM
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt update && apt install -y nodejs

EXPOSE 80

CMD ["apachectl", "-D", "FOREGROUND"]
