# PHP + Apache
FROM php:8.1.12-apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Update OS and install common dev tools
RUN apt-get update
RUN apt-get install -y \
	wget \
	vim \
	git \
	zip \
	unzip \
	nano \
	sudo \
	zlib1g-dev \
	libzip-dev \
	libpng-dev \
    libicu-dev \
    libbz2-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    libsodium-dev \
	g++

RUN docker-php-ext-install \
	mysqli \
	pdo_mysql \
	gd \
	zip \
	pcntl \
	exif \
	sodium \
	intl \
	bz2 \
	calendar \
	opcache \
	bcmath
	
	
RUN docker-php-ext-enable \
	mysqli \
	intl \
	pdo_mysql \
	gd \
	zip \
	pcntl \
	exif \
	sodium 

# Enable common Apache modules
RUN a2enmod headers expires rewrite

# XDEBUG
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
# This needs in order to run xdebug from VSCode
ENV PHP_IDE_CONFIG 'serverName=csia'

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Set working directory to web files
WORKDIR /var/www/html

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf