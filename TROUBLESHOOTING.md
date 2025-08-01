# Troubleshooting Guide - Dental Clinic PMS

## 🚨 Common Issues and Solutions

### 1. Composer Autoload Error

**Error:**
```
PHP Fatal error: Failed opening required 'vendor/autoload.php'
```

**Solution:**
```bash
# Navigate to your project directory
cd /var/www/html/clinic/dental-clinic-pms

# Run the fix script
chmod +x fix-composer-issue.sh
./fix-composer-issue.sh
```

**Manual Fix:**
```bash
# Remove vendor directory and reinstall
rm -rf vendor/
composer install --no-dev --optimize-autoloader

# Set proper permissions
sudo chown -R www-data:www-data .
sudo chmod -R 775 storage/ bootstrap/cache/
```

### 2. Environment Configuration Issues

**Error:**
```
No application encryption key has been specified
```

**Solution:**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database settings in .env
nano .env
```

### 3. Database Connection Issues

**Error:**
```
SQLSTATE[HY000] [1049] Unknown database
```

**Solution:**
```bash
# Create database
mysql -u root -p
CREATE DATABASE dental_clinic_pms_uat;
EXIT;

# Run migrations
php artisan migrate
php artisan db:seed
```

### 4. Permission Issues

**Error:**
```
The stream or file "storage/logs/laravel.log" could not be opened
```

**Solution:**
```bash
# Fix storage permissions
sudo chown -R www-data:www-data storage/
sudo chmod -R 775 storage/
sudo chmod -R 775 bootstrap/cache/

# Create storage link
php artisan storage:link
```

### 5. Web Server Issues

**Error:**
```
403 Forbidden / 404 Not Found
```

**Solution:**
```bash
# Check Nginx configuration
sudo nginx -t

# Verify document root points to public/
# In your Nginx config:
root /var/www/html/clinic/dental-clinic-pms/public;

# Restart web server
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

### 6. SSL Certificate Issues

**Error:**
```
Your connection is not private
```

**Solution:**
```bash
# Install Let's Encrypt certificate
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com

# Check certificate status
sudo certbot certificates
```

### 7. PHP Version Issues

**Error:**
```
This package requires php ^8.1
```

**Solution:**
```bash
# Install PHP 8.2
sudo apt update
sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-curl php8.2-zip php8.2-mbstring php8.2-intl php8.2-bcmath php8.2-gd

# Update alternatives
sudo update-alternatives --set php /usr/bin/php8.2
```

### 8. Memory Limit Issues

**Error:**
```
Fatal error: Allowed memory size exhausted
```

**Solution:**
```bash
# Increase PHP memory limit
sudo nano /etc/php/8.2/fpm/php.ini

# Change these values:
memory_limit = 512M
max_execution_time = 300

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

### 9. Node.js/NPM Issues

**Error:**
```
npm: command not found
```

**Solution:**
```bash
# Install Node.js and NPM
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install frontend dependencies
npm install
npm run build
```

### 10. Cache Issues

**Error:**
```
Route not defined / View not found
```

**Solution:**
```bash
# Clear all caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🔍 Diagnostic Commands

### Check System Status
```bash
# Check PHP version and modules
php --version
php -m | grep -E "(pdo|mysql|mbstring|xml|curl|zip)"

# Check Composer
composer --version

# Check Laravel
php artisan --version

# Check database connection
php artisan tinker
>>> DB::connection()->getPdo();
>>> exit
```

### Check File Permissions
```bash
# Check ownership
ls -la storage/
ls -la bootstrap/cache/

# Check web server user
ps aux | grep -E "(nginx|apache|www-data)"
```

### Check Services
```bash
# Check running services
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
sudo systemctl status mysql

# Check port availability
sudo netstat -tlnp | grep -E "(80|443|3306)"
```

### Check Logs
```bash
# Application logs
tail -f storage/logs/laravel.log

# Web server logs
tail -f /var/log/nginx/error.log
tail -f /var/log/nginx/access.log

# PHP-FPM logs
tail -f /var/log/php8.2-fpm.log
```

## 🆘 Emergency Recovery

### Quick Reset
```bash
# If everything breaks, start fresh:
cd /var/www/html/clinic/dental-clinic-pms

# Backup first
sudo mysqldump -u root -p dental_clinic_pms_uat > backup.sql

# Reset application
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh
php artisan db:seed

# Fix permissions
sudo chown -R www-data:www-data .
sudo chmod -R 775 storage/ bootstrap/cache/
```

### Rollback Database
```bash
# If database is corrupted
mysql -u root -p dental_clinic_pms_uat < backup.sql
```

## 📞 Support Checklist

When reporting issues, please provide:

- [ ] Full error message
- [ ] PHP version (`php --version`)
- [ ] Laravel version (`php artisan --version`)
- [ ] Operating system and version
- [ ] Web server (Nginx/Apache) version
- [ ] Recent changes made
- [ ] Contents of relevant log files
- [ ] Steps to reproduce the issue

## 🔧 Development vs Production

### Development Environment
```bash
# Use SQLite for quick setup
cp .env.example .env
# Set DB_CONNECTION=sqlite
php artisan migrate
php artisan db:seed
php artisan serve
```

### Production Environment
```bash
# Use MySQL/PostgreSQL
# Set APP_ENV=production
# Set APP_DEBUG=false
# Configure proper database
# Set up web server
# Enable HTTPS
# Set up monitoring
```