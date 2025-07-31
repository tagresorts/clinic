#!/bin/bash

# Fix Composer Dependencies Issue for Dental Clinic PMS
# Run this script in your project directory on the UAT server

echo "🔧 Fixing Composer dependencies for Dental Clinic PMS..."

# Check current directory
echo "📍 Current directory: $(pwd)"

# Check if we're in the correct project directory
if [ ! -f "composer.json" ]; then
    echo "❌ Error: composer.json not found. Please run this script from the project root directory."
    echo "💡 Navigate to your project directory first:"
    echo "   cd /var/www/html/clinic/dental-clinic-pms"
    exit 1
fi

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer not found. Installing Composer..."
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
fi

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP not found. Please install PHP first:"
    echo "   sudo apt update"
    echo "   sudo apt install -y php8.2 php8.2-cli php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip"
    exit 1
fi

# Display versions
echo "📋 Environment Info:"
php --version | head -1
composer --version

# Remove existing vendor directory if corrupted
if [ -d "vendor" ]; then
    echo "🗑️ Removing existing vendor directory..."
    rm -rf vendor/
fi

# Remove composer.lock to ensure fresh install
if [ -f "composer.lock" ]; then
    echo "🗑️ Removing composer.lock for fresh install..."
    rm composer.lock
fi

# Clear Composer cache
echo "🧹 Clearing Composer cache..."
composer clear-cache

# Install dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Verify installation
if [ -f "vendor/autoload.php" ]; then
    echo "✅ Composer dependencies installed successfully!"
    echo "📁 vendor/autoload.php exists"
else
    echo "❌ Failed to install dependencies!"
    exit 1
fi

# Set proper permissions
echo "🔐 Setting proper permissions..."
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage/
sudo chmod -R 775 bootstrap/cache/

# Test Laravel installation
echo "🧪 Testing Laravel installation..."
if php artisan --version; then
    echo "✅ Laravel is working correctly!"
else
    echo "❌ Laravel test failed!"
    exit 1
fi

# Generate application key if .env exists
if [ -f ".env" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate
else
    echo "⚠️ .env file not found. You'll need to create it from .env.example"
    echo "   cp .env.example .env"
    echo "   php artisan key:generate"
fi

echo ""
echo "🎉 Setup complete! Your Dental Clinic PMS should now work properly."
echo ""
echo "📋 Next steps:"
echo "1. Make sure your .env file is configured"
echo "2. Run database migrations: php artisan migrate"
echo "3. Seed test data: php artisan db:seed"
echo "4. Test the application: php artisan serve"