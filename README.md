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
      - ./:/var/www/html
    ports:
      - "9000:9000"
    depends_on:
      - mysql
    networks:
      - laravel

  web:
    image: nginx:alpine
    container_name: laravel-web
    ports:
      - "8000:80"
    volumes:
      - ./:/var/www/html
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
    networks:
      - laravel

  mysql:
    image: mysql:8.0
    container_name: laravel-mysql
    restart: always
    environment:
      - MYSQL_ROOT_PASSWORD=root
      - MYSQL_TCP_PORT=3306
    ports:
      - 3310:3306
    volumes:
      - mysql-data:/var/lib/mysql
    networks:
      - laravel

  composer:
    image: composer
    container_name: laravel-composer
    working_dir: /var/www/html
    volumes:
      - ./:/var/www/html
    entrypoint: ["composer"]
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
  mysql-data:
```

### 2. Use this DockerFile

```bash
FROM php:8.2-fpm

# Install required system packages and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html
```

### 3. Add defaulf.conf

In nginx/default.conf use - 

```bash
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
DB_HOST=laravel-mysql  # container name of mysql
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
docker compose run --rm composer install
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


