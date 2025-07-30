# Deployment Guide - Dental Clinic Management System

This guide covers various deployment options for the Dental Clinic Management System, from development to production environments.

## 🚀 Deployment Options

### 1. Development Environment
### 2. Shared Hosting (cPanel)
### 3. VPS/Cloud Server (Ubuntu/CentOS)
### 4. Docker Deployment
### 5. Cloud Platforms (AWS, DigitalOcean, etc.)

---

## 🔧 Development Environment

### Prerequisites
- PHP 8.1+
- Composer 2.0+
- Node.js 18+
- MySQL 8.0+ or PostgreSQL 12+

### Quick Setup
```bash
# Clone repository
git clone https://github.com/YOUR_USERNAME/clinic.git
cd clinic

# Install dependencies
npm run install:all

# Setup backend
cd backend
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed

# Setup frontend
cd ../frontend
cp .env.example .env

# Start development servers
cd ..
npm run dev
```

---

## 🏠 Shared Hosting (cPanel) Deployment

### Step 1: Prepare Files
```bash
# Build frontend for production
cd frontend
npm run build

# Optimize Laravel for production
cd ../backend
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 2: Upload Files
1. **Upload backend files** to `public_html/api/` (or subdomain)
2. **Upload frontend build** to `public_html/`
3. **Set correct permissions**:
   - `storage/` and `bootstrap/cache/` folders: 755
   - `.env` file: 644

### Step 3: Database Setup
1. **Create database** via cPanel
2. **Import database structure**:
   ```sql
   -- Run migrations manually or import SQL dump
   ```
3. **Update .env** with database credentials

### Step 4: Configure .htaccess
```apache
# public_html/.htaccess (Frontend)
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # Handle API requests
    RewriteRule ^api/(.*)$ /api/public/index.php [L]
    
    # Handle React routing
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . /index.html [L]
</IfModule>

# public_html/api/public/.htaccess (Backend)
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## 🖥️ VPS/Cloud Server Deployment (Ubuntu 22.04)

### Step 1: Server Setup
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx mysql-server php8.1 php8.1-fpm php8.1-mysql \
    php8.1-xml php8.1-curl php8.1-zip php8.1-mbstring php8.1-gd \
    nodejs npm composer git certbot python3-certbot-nginx

# Install PM2 for process management
sudo npm install -g pm2
```

### Step 2: Database Setup
```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```
```sql
CREATE DATABASE dcpms_production;
CREATE USER 'dcpms_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON dcpms_production.* TO 'dcpms_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 3: Application Deployment
```bash
# Clone repository
cd /var/www
sudo git clone https://github.com/YOUR_USERNAME/clinic.git
sudo chown -R www-data:www-data clinic
cd clinic

# Setup backend
cd backend
sudo -u www-data composer install --optimize-autoloader --no-dev
sudo -u www-data cp .env.example .env

# Configure environment
sudo -u www-data nano .env
```

### Step 4: Environment Configuration
```bash
# backend/.env
APP_NAME="Dental Clinic Management System"
APP_ENV=production
APP_KEY=base64:GENERATED_KEY
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dcpms_production
DB_USERNAME=dcpms_user
DB_PASSWORD=secure_password

# Mail configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### Step 5: Laravel Setup
```bash
cd backend
sudo -u www-data php artisan key:generate
sudo -u www-data php artisan migrate
sudo -u www-data php artisan db:seed
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
sudo -u www-data php artisan storage:link

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Step 6: Frontend Setup
```bash
cd ../frontend
sudo npm install
sudo npm run build

# Copy build files
sudo cp -r build/* /var/www/html/
```

### Step 7: Nginx Configuration
```nginx
# /etc/nginx/sites-available/clinic
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/html;
    index index.html;

    # Frontend (React)
    location / {
        try_files $uri $uri/ /index.html;
    }

    # Backend API (Laravel)
    location /api {
        alias /var/www/clinic/backend/public;
        try_files $uri $uri/ @laravel;

        location ~ \.php$ {
            include fastcgi_params;
            fastcgi_param SCRIPT_FILENAME $request_filename;
            fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        }
    }

    location @laravel {
        rewrite /api/(.*)$ /api/index.php?/$1 last;
    }

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;
}
```

### Step 8: Enable Site and SSL
```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/clinic /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx

# Install SSL certificate
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

### Step 9: Process Management
```bash
# Setup Laravel queue worker (if using queues)
cd /var/www/clinic/backend
sudo -u www-data pm2 start "php artisan queue:work --daemon" --name laravel-queue

# Setup Laravel scheduler
sudo crontab -e
# Add: * * * * * cd /var/www/clinic/backend && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🐳 Docker Deployment

### Step 1: Create Docker Files

#### backend/Dockerfile
```dockerfile
FROM php:8.1-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
```

#### frontend/Dockerfile
```dockerfile
# Build stage
FROM node:18-alpine as build

WORKDIR /app
COPY package*.json ./
RUN npm ci --only=production

COPY . ./
RUN npm run build

# Production stage
FROM nginx:alpine

COPY --from=build /app/build /usr/share/nginx/html
COPY nginx.conf /etc/nginx/nginx.conf

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
```

#### docker-compose.yml
```yaml
version: '3.8'

services:
  # MySQL Database
  db:
    image: mysql:8.0
    container_name: clinic_db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: dcpms
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_PASSWORD: password
      MYSQL_USER: dcpms_user
    volumes:
      - dbdata:/var/lib/mysql
    ports:
      - "3306:3306"

  # Laravel Backend
  backend:
    build:
      context: ./backend
      dockerfile: Dockerfile
    container_name: clinic_backend
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./backend:/var/www
    depends_on:
      - db
    networks:
      - clinic-network

  # Nginx for Laravel
  nginx:
    image: nginx:alpine
    container_name: clinic_nginx
    restart: unless-stopped
    ports:
      - "8000:80"
    volumes:
      - ./backend:/var/www
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - backend
    networks:
      - clinic-network

  # React Frontend
  frontend:
    build:
      context: ./frontend
      dockerfile: Dockerfile
    container_name: clinic_frontend
    restart: unless-stopped
    ports:
      - "3000:80"
    depends_on:
      - backend
    networks:
      - clinic-network

volumes:
  dbdata:
    driver: local

networks:
  clinic-network:
    driver: bridge
```

### Step 2: Deploy with Docker
```bash
# Build and start containers
docker-compose up -d --build

# Run Laravel migrations
docker-compose exec backend php artisan migrate
docker-compose exec backend php artisan db:seed

# Generate application key
docker-compose exec backend php artisan key:generate
```

---

## ☁️ Cloud Platform Deployment

### AWS Deployment

#### Using AWS Elastic Beanstalk
1. **Prepare application**:
   ```bash
   # Create deployment package
   zip -r clinic-app.zip . -x "node_modules/*" "*.git*"
   ```

2. **Deploy to Elastic Beanstalk**:
   - Create new application
   - Upload ZIP file
   - Configure environment variables
   - Set up RDS database

#### Using AWS EC2
Follow the VPS deployment guide with these AWS-specific considerations:
- Use Amazon Linux 2 or Ubuntu AMI
- Configure security groups for ports 80, 443, 22
- Use RDS for database
- Use S3 for file storage
- Use CloudFront for CDN

### DigitalOcean Deployment

#### Using App Platform
1. **Connect GitHub repository**
2. **Configure build settings**:
   ```yaml
   # .do/app.yaml
   name: clinic-app
   services:
   - name: backend
     source_dir: backend
     github:
       repo: YOUR_USERNAME/clinic
       branch: main
     run_command: php artisan serve --host=0.0.0.0 --port=8080
     environment_slug: php
     instance_count: 1
     instance_size_slug: basic-xxs
     
   - name: frontend
     source_dir: frontend
     github:
       repo: YOUR_USERNAME/clinic
       branch: main
     build_command: npm run build
     run_command: npx serve -s build -l 3000
     environment_slug: node-js
     instance_count: 1
     instance_size_slug: basic-xxs
   ```

#### Using Droplets
Follow the VPS deployment guide with DigitalOcean-specific features:
- Use DigitalOcean Managed Databases
- Configure DigitalOcean Spaces for file storage
- Use DigitalOcean Load Balancers for scaling

---

## 🔒 Security Considerations

### SSL/TLS Configuration
```bash
# Use strong SSL configuration
sudo nano /etc/nginx/sites-available/clinic
```

```nginx
# Add to server block
ssl_protocols TLSv1.2 TLSv1.3;
ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
ssl_prefer_server_ciphers off;
ssl_session_cache shared:SSL:10m;
ssl_session_timeout 10m;
```

### Firewall Configuration
```bash
# Configure UFW firewall
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow ssh
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

### Application Security
```bash
# Set proper file permissions
sudo find /var/www/clinic -type f -exec chmod 644 {} \;
sudo find /var/www/clinic -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/clinic/backend/storage
sudo chmod -R 775 /var/www/clinic/backend/bootstrap/cache
```

---

## 📊 Monitoring and Maintenance

### Log Management
```bash
# Laravel logs
tail -f /var/www/clinic/backend/storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# MySQL logs
tail -f /var/log/mysql/error.log
```

### Backup Strategy
```bash
#!/bin/bash
# backup-script.sh

# Database backup
mysqldump -u dcpms_user -p dcpms_production > backup_$(date +%Y%m%d_%H%M%S).sql

# File backup
tar -czf files_backup_$(date +%Y%m%d_%H%M%S).tar.gz /var/www/clinic

# Upload to cloud storage (optional)
# aws s3 cp backup_*.sql s3://your-backup-bucket/
```

### Performance Optimization
```bash
# Enable OPcache
sudo nano /etc/php/8.1/fpm/php.ini

# Add/modify:
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

---

## 🚨 Troubleshooting

### Common Issues

#### 1. Permission Errors
```bash
sudo chown -R www-data:www-data /var/www/clinic
sudo chmod -R 775 storage bootstrap/cache
```

#### 2. Database Connection Issues
```bash
# Check MySQL status
sudo systemctl status mysql

# Test connection
mysql -u dcpms_user -p dcpms_production
```

#### 3. Nginx Configuration Errors
```bash
# Test configuration
sudo nginx -t

# Check logs
tail -f /var/log/nginx/error.log
```

#### 4. Laravel Errors
```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check logs
tail -f storage/logs/laravel.log
```

---

## 📈 Scaling Considerations

### Load Balancing
- Use multiple application servers
- Implement database read replicas
- Use Redis for session storage
- Implement CDN for static assets

### Database Optimization
- Regular database maintenance
- Query optimization
- Index optimization
- Connection pooling

### Caching Strategy
- Redis for application caching
- CDN for static assets
- Database query caching
- API response caching

---

This deployment guide covers most common scenarios. Choose the deployment method that best fits your infrastructure requirements and technical expertise.

For additional support, please refer to the [Contributing Guide](CONTRIBUTING.md) or create an issue in the repository.