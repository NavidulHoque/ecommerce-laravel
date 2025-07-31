# 🚀 Laravel Project Setup with Docker

This guide explains how to set up and run ** Laravel project** using Docker and Docker Compose.

---

## ✅ Prerequisites

Before starting, make sure you have:

- [Docker](https://www.docker.com/products/docker-desktop) installed
- [Docker Compose](https://docs.docker.com/compose/) available

---

## ⚙️ Step-by-Step Setup

### 1. Use this docker-compose.yml

```bash
version: "3.8"

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: laravel-app
    working_dir: /var/www/html
    volumes:
      - .:/var/www/html
    networks:
      - laravel
    depends_on:
      - mysql

  nginx:
    image: nginx:stable-alpine
    container_name: laravel-nginx
    ports:
      - "8000:80"
    volumes:
      - .:/var/www/html
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    networks:
      - laravel
    depends_on:
      - app

  mysql:
    image: mysql:8.0
    container_name: laravel-mysql
    ports:
      - "3306:3306"
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: laravel
      MYSQL_USER: laravel
      MYSQL_PASSWORD: secret
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - laravel

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: laravel-phpmyadmin
    restart: always
    environment:
        PMA_HOST: mysql
        PMA_PORT: 3306
        MYSQL_ROOT_PASSWORD: root
    ports:
        - "8080:80"
    depends_on:
        - mysql
    networks:
        - laravel

networks:
  laravel:

volumes:
  mysql_data:
```

### 2. Use this DockerFile

```bash
# Dockerfile
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy everything
COPY . .

# Install Laravel dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

EXPOSE 9000
CMD ["php-fpm"]
```

### 3. Add defaulf.conf

In nginx/default.conf use - 

```bash
# nginx/default.conf
server {
    listen 80;
    index index.php index.html;
    root /var/www/html/public;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### 4. Create .env File

- Copy .env.example to .env

### 5. Update the .env

- Update database section as follows:

```bash
DB_CONNECTION=mysql    # This MUST match the connection key in database.php
DB_HOST=mysql  # container name of mysql
DB_PORT=3306           # internal port exposed by mysql image
DB_DATABASE=ecommerce  # name it according to your project
DB_USERNAME=root
DB_PASSWORD=root
```

### 6. Build and Start the Docker Containers

```bash
docker compose up -d --build
```

This will:

- Build the PHP container using the Dockerfile

- Start services for: Laravel app, MySQL, PhpMyAdmin, Nginx, and Composer

### 7. Install Laravel Dependencies

```bash
docker exec -it laravel-app composer install
```

### 8. Generate Application Key

```bash
docker compose exec app php artisan key:generate
```

### 9. Run Database Migrations

```bash
docker compose exec app php artisan migrate
```

### 10. Open the Project in Browser

Visit: http://localhost:8000

### 11. Create Model, Migration, and Controller

```bash
docker compose exec app php artisan make:model Post -mcr
```

This creates:

- app/Models/Post.php
- database/migrations/xxxx_create_posts_table.php
- app/Http/Controllers/PostController.php

Then enjoy building an excellent application


