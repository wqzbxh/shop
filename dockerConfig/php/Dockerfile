FROM php:8.2-fpm

# Install necessary extensions and libraries
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    librabbitmq-dev \
    && docker-php-ext-install zip pdo_mysql \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && pecl install redis && docker-php-ext-enable redis
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
# 安装redis服务器并且自启动
RUN apt-get update && \
    apt-get install -y redis-server && \
    sed -i 's/bind 127.0.0.1/bind 0.0.0.0/g' /etc/redis/redis.conf && \
    sed -i "s/# requirepass foobared/requirepass admin123/g" /etc/redis/redis.conf && \
    echo "requirepass admin123" >> /etc/redis/redis.conf && \
    sed -i 's/protected-mode yes/protected-mode no/g' /etc/redis/redis.conf && \
    service redis-server restart

# 安装vim编辑器
RUN apt-get update && apt-get install -y vim

# Copy the custom PHP configuration file to the container
COPY php.ini /usr/local/etc/php/conf.d/
