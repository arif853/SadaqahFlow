# SadaqahFlow Deployment Guide

## Overview

This guide covers deploying SadaqahFlow to various hosting environments including shared hosting, VPS, and cloud platforms.

---

## Requirements

### Server Requirements

| Requirement | Minimum | Recommended |
|-------------|---------|-------------|
| PHP | 8.1 | 8.2+ |
| MySQL | 8.0 | 8.0+ |
| RAM | 512MB | 1GB+ |
| Storage | 1GB | 5GB+ |
| Node.js | 18.x | 20.x |

### PHP Extensions

```
BCMath, Ctype, cURL, DOM, Fileinfo, GD, JSON, 
Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML, ZIP
```

---

## Deployment Options

### Option 1: Shared Hosting (cPanel)

#### Step 1: Prepare Files

```bash
# Build assets locally
npm run build

# Create deployment archive (exclude dev files)
zip -r deploy.zip . -x "node_modules/*" -x ".git/*" -x "tests/*"
```

#### Step 2: Upload to Server

1. Upload `deploy.zip` via File Manager or FTP
2. Extract to a folder above `public_html`

```
/home/username/
├── sadaqahflow/          # Laravel application
│   ├── app/
│   ├── config/
│   ├── public/           # Only this goes to public_html
│   └── ...
└── public_html/          # Web root
    └── (symlink or copy of public/)
```

#### Step 3: Configure Public Directory

Option A - Symlink (recommended):
```bash
cd ~/public_html
rm -rf *
ln -s ../sadaqahflow/public/* .
```

Option B - Move public contents:
```bash
cp -r sadaqahflow/public/* public_html/
```

Update `public_html/index.php`:
```php
require __DIR__.'/../sadaqahflow/vendor/autoload.php';
$app = require_once __DIR__.'/../sadaqahflow/bootstrap/app.php';
```

#### Step 4: Configure Environment

```bash
cd ~/sadaqahflow
cp .env.example .env
nano .env
```

```env
APP_NAME="SadaqahFlow"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

SESSION_DRIVER=database
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

#### Step 5: Run Setup Commands

Via SSH or cPanel Terminal:
```bash
cd ~/sadaqahflow
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Step 6: Set Permissions

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

### Option 2: VPS (Ubuntu/Debian)

#### Step 1: Install Dependencies

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring \
    php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip php8.2-gd -y

# Install Nginx
sudo apt install nginx -y

# Install MySQL
sudo apt install mysql-server -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install nodejs -y
```

#### Step 2: Configure MySQL

```bash
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```

```sql
CREATE DATABASE sadaqahflow;
CREATE USER 'sadaqah_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON sadaqahflow.* TO 'sadaqah_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Step 3: Deploy Application

```bash
# Clone repository
cd /var/www
sudo git clone https://github.com/yourusername/sadaqahflow.git
cd sadaqahflow

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Configure environment
cp .env.example .env
nano .env  # Edit with production settings

# Generate key and run migrations
php artisan key:generate
php artisan migrate --force
php artisan storage:link

# Set permissions
sudo chown -R www-data:www-data /var/www/sadaqahflow
sudo chmod -R 755 storage bootstrap/cache
```

#### Step 4: Configure Nginx

```bash
sudo nano /etc/nginx/sites-available/sadaqahflow
```

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/sadaqahflow/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/sadaqahflow /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

#### Step 5: SSL with Let's Encrypt

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

#### Step 6: Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

### Option 3: Docker Deployment

#### docker-compose.yml

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: sadaqahflow-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
    networks:
      - sadaqahflow-network
    depends_on:
      - db

  nginx:
    image: nginx:alpine
    container_name: sadaqahflow-nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./docker/nginx/conf.d:/etc/nginx/conf.d
    networks:
      - sadaqahflow-network

  db:
    image: mysql:8.0
    container_name: sadaqahflow-db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: sadaqahflow
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_USER: sadaqah_user
      MYSQL_PASSWORD: secure_password
    volumes:
      - dbdata:/var/lib/mysql
    networks:
      - sadaqahflow-network
    ports:
      - "3306:3306"

networks:
  sadaqahflow-network:
    driver: bridge

volumes:
  dbdata:
```

#### Dockerfile

```dockerfile
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . /var/www

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
```

#### Deploy with Docker

```bash
# Build and start containers
docker-compose up -d --build

# Run migrations
docker-compose exec app php artisan migrate --force

# Generate key
docker-compose exec app php artisan key:generate

# Create storage link
docker-compose exec app php artisan storage:link
```

---

## Post-Deployment Checklist

- [ ] Environment set to `production`
- [ ] Debug mode disabled (`APP_DEBUG=false`)
- [ ] Application key generated
- [ ] Database migrated
- [ ] Storage symlink created
- [ ] File permissions set correctly
- [ ] SSL certificate installed
- [ ] Caches generated (config, route, view)
- [ ] Default admin password changed
- [ ] Backup strategy configured
- [ ] Monitoring set up

---

## Maintenance Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check application health
php artisan about

# Run migrations
php artisan migrate --force

# Rollback last migration
php artisan migrate:rollback
```

---

## Troubleshooting

### Common Issues

**500 Internal Server Error**
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check permissions
chmod -R 755 storage bootstrap/cache
```

**Storage Link Issues**
```bash
# Remove existing link
rm public/storage

# Create new link
php artisan storage:link
```

**Database Connection Failed**
- Verify `.env` database credentials
- Check MySQL service is running
- Verify database exists and user has permissions

**Styles Not Loading**
```bash
# Rebuild assets
npm run build

# Check if public/build exists
ls -la public/build
```
