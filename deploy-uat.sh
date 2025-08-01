#!/bin/bash

# UAT Deployment Script for Dental Clinic PMS
# Usage: ./deploy-uat.sh

set -e

echo "🚀 Starting UAT Deployment for Dental Clinic PMS"

# Configuration
APP_DIR="/var/www/dental-clinic-pms"
BACKUP_DIR="/var/backups/dental-clinic-pms"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup directory if it doesn't exist
sudo mkdir -p $BACKUP_DIR

echo "📦 Creating backup..."
sudo mysqldump -u root -p dental_clinic_pms_uat > $BACKUP_DIR/database_backup_$DATE.sql
sudo tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz -C $APP_DIR .

echo "🔄 Pulling latest changes..."
cd $APP_DIR
git pull origin main

echo "📚 Installing/updating dependencies..."
composer install --no-dev --optimize-autoloader
npm install
npm run build

echo "🗄️ Running database migrations..."
php artisan migrate --force

echo "🌱 Seeding UAT data..."
php artisan db:seed --class=UATSeeder

echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🔐 Setting permissions..."
sudo chown -R www-data:www-data $APP_DIR
sudo chmod -R 755 $APP_DIR
sudo chmod -R 775 $APP_DIR/storage
sudo chmod -R 775 $APP_DIR/bootstrap/cache

echo "🔄 Restarting services..."
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx

echo "🧪 Running health checks..."
# Check if application is responding
if curl -f -s https://your-uat-domain.com > /dev/null; then
    echo "✅ Application is responding"
else
    echo "❌ Application health check failed"
    exit 1
fi

# Check database connection
if php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database OK';" | grep -q "Database OK"; then
    echo "✅ Database connection successful"
else
    echo "❌ Database connection failed"
    exit 1
fi

echo "🎉 UAT Deployment completed successfully!"
echo "📋 UAT Test Accounts:"
echo "   Administrator: admin@dentaluat.com / UATAdmin2024!"
echo "   Dentist: dr.rodriguez@dentaluat.com / UATDentist2024!"
echo "   Receptionist: sarah@dentaluat.com / UATReception2024!"
echo ""
echo "🌐 UAT URL: https://your-uat-domain.com"
echo "📊 Monitor logs: tail -f /var/log/nginx/dental-clinic-error.log"