# 设置基础镜像
FROM php:7.4-fpm

# 安装必要的软件包和依赖项
RUN apt-get update && apt-get install -y \
    nginx \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# 安装 PHP 扩展
RUN docker-php-ext-install zip pdo_mysql gd

# 安装 Redis 扩展
RUN pecl install redis && docker-php-ext-enable redis

# 配置 Nginx
COPY ./dockerConfig/nginx/default.conf /etc/nginx/conf.d/default.conf

# 复制 Laravel 代码到容器中
COPY . /var/www/html

# 设置文件和目录的权限
RUN chown -R www-data:www-data /var/www/html

# 启动 Nginx 和 PHP-FPM
CMD nginx -g "daemon off;" & php-fpm
