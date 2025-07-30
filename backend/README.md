# Dental Clinic Management System - Laravel Backend

A robust Laravel API backend for the Dental Clinic Patient Management System, featuring comprehensive patient management, appointment scheduling, treatment planning, billing, and inventory management.

## 🚀 Features

### 🔐 Authentication & Authorization
- **Laravel Sanctum** for API authentication
- **Spatie Laravel Permission** for role-based access control
- **JWT-like token** management with automatic expiration
- **Three user roles**: Administrator, Dentist, Receptionist

### 📊 Core Modules
- **Patient Management**: Complete CRUD with medical/dental history
- **Appointment System**: Smart scheduling with conflict detection
- **Treatment Planning**: Comprehensive treatment plans and records
- **Billing & Invoicing**: Full billing cycle with payment tracking
- **Inventory Management**: Stock tracking with low-stock alerts
- **Reporting**: Financial and operational analytics

### 🛡️ Security Features
- **Form Request Validation** for all inputs
- **Rate Limiting** to prevent API abuse
- **CORS Protection** for cross-origin requests
- **SQL Injection Prevention** with Eloquent ORM
- **Password Hashing** with bcrypt

## 📋 Requirements

- **PHP 8.1+**
- **Composer 2.0+**
- **MySQL 8.0+** or **PostgreSQL 12+**
- **Laravel 10.x**

## 🔧 Installation

### 1. Clone and Setup
```bash
# Navigate to backend directory
cd backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 2. Database Configuration
```bash
# Edit .env file with your database credentials
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dcpms_laravel
DB_USERNAME=your_username
DB_PASSWORD=your_password

# For PostgreSQL, use:
# DB_CONNECTION=pgsql
# DB_PORT=5432
```

### 3. Run Migrations and Seeders
```bash
# Run database migrations
php artisan migrate

# Install Spatie Permission tables
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate

# Seed initial data (roles and admin user)
php artisan db:seed
```

### 4. Configure Laravel Sanctum
```bash
# Publish Sanctum configuration
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Add to .env
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1,127.0.0.1:3000
```

### 5. Storage Setup
```bash
# Create storage link for file uploads
php artisan storage:link

# Set permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 6. Start Development Server
```bash
# Start Laravel development server
php artisan serve

# API will be available at: http://localhost:8000/api
```

## 🗄️ Database Schema

### Core Tables
- **users** - System users with roles
- **patients** - Patient information and medical history
- **appointments** - Appointment scheduling and management
- **treatment_plans** - Treatment planning and cost estimation
- **treatment_records** - Completed treatments and procedures
- **invoices** - Billing and invoice management
- **payments** - Payment tracking and history
- **inventory_items** - Dental supplies and equipment
- **suppliers** - Supplier information
- **dental_charts** - Tooth-by-tooth condition tracking

### Permission Tables (Spatie)
- **roles** - User roles (Administrator, Dentist, Receptionist)
- **permissions** - Granular permissions
- **model_has_roles** - User-role assignments
- **role_has_permissions** - Role-permission assignments

## 🔑 Authentication

### Login
```bash
POST /api/auth/login
Content-Type: application/json

{
    "username": "admin",
    "password": "password"
}
```

### Response
```json
{
    "message": "Login successful",
    "token": "1|abc123...",
    "user": {
        "id": 1,
        "username": "admin",
        "email": "admin@clinic.com",
        "first_name": "Admin",
        "last_name": "User",
        "role": "administrator"
    }
}
```

### Using Token
```bash
Authorization: Bearer 1|abc123...
```

## 📡 API Endpoints

### Authentication
- `POST /api/auth/login` - User login
- `GET /api/auth/me` - Get current user
- `POST /api/auth/logout` - Logout current session
- `POST /api/auth/change-password` - Change password
- `POST /api/auth/register` - Register new user (Admin only)

### Patients
- `GET /api/patients` - List patients with pagination
- `POST /api/patients` - Create new patient
- `GET /api/patients/{id}` - Get patient details
- `PUT /api/patients/{id}` - Update patient
- `DELETE /api/patients/{id}` - Soft delete patient
- `GET /api/patients/search?query=john` - Search patients
- `GET /api/patients/{id}/dental-chart` - Get dental chart
- `POST /api/patients/{id}/dental-chart` - Update dental chart

### Appointments
- `GET /api/appointments` - List appointments with filters
- `POST /api/appointments` - Create appointment
- `GET /api/appointments/calendar` - Calendar view
- `GET /api/appointments/available-slots` - Check availability
- `GET /api/appointments/upcoming` - Upcoming appointments
- `PUT /api/appointments/{id}` - Update appointment
- `DELETE /api/appointments/{id}` - Cancel appointment

### Treatments
- `GET /api/treatments/plans` - List treatment plans
- `POST /api/treatments/plans` - Create treatment plan
- `GET /api/treatments/records` - List treatment records
- `POST /api/treatments/records` - Create treatment record

### Billing
- `GET /api/billing/invoices` - List invoices
- `POST /api/billing/invoices` - Create invoice
- `GET /api/billing/payments` - List payments
- `POST /api/billing/payments` - Record payment
- `GET /api/billing/outstanding` - Outstanding invoices

### Inventory (Admin Only)
- `GET /api/inventory/items` - List inventory items
- `POST /api/inventory/items` - Add inventory item
- `GET /api/inventory/suppliers` - List suppliers
- `GET /api/inventory/low-stock` - Low stock alerts

### Reports (Admin Only)
- `GET /api/reports/dashboard` - Dashboard statistics
- `GET /api/reports/financial` - Financial reports
- `GET /api/reports/patients` - Patient analytics
- `GET /api/reports/appointments` - Appointment reports

### Users
- `GET /api/users` - List all users (Admin only)
- `GET /api/users/dentists` - List dentists
- `PUT /api/users/{id}` - Update user (Admin only)
- `POST /api/users/{id}/toggle-status` - Toggle user status (Admin only)

## 🔒 Role-Based Access Control

### Administrator
- Full system access
- User management
- Financial reports
- Inventory management
- System configuration

### Dentist
- Patient medical records
- Treatment planning
- Own appointments
- Treatment records
- Patient communication

### Receptionist
- Patient registration
- Appointment scheduling
- Basic billing
- Patient communication
- Calendar management

## 🧪 Artisan Commands

### Custom Commands (to be created)
```bash
# Create admin user
php artisan dcpms:create-admin

# Send appointment reminders
php artisan dcpms:send-reminders

# Check low stock items
php artisan dcpms:check-inventory

# Generate reports
php artisan dcpms:generate-reports
```

## 📅 Scheduled Tasks

Add to `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Send appointment reminders daily at 9 AM
    $schedule->command('dcpms:send-reminders')->dailyAt('09:00');
    
    // Check inventory daily at 8 AM
    $schedule->command('dcpms:check-inventory')->dailyAt('08:00');
    
    // Generate daily reports at midnight
    $schedule->command('dcpms:generate-reports')->daily();
}
```

Run scheduler:
```bash
# Add to crontab
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## 🔧 Configuration

### Environment Variables
```bash
# Application
APP_NAME="Dental Clinic Management System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dcpms_laravel
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls

# File Storage
FILESYSTEM_DISK=local
MAX_FILE_SIZE=10240  # 10MB in KB

# Clinic Information
CLINIC_NAME="Your Dental Clinic"
CLINIC_ADDRESS="123 Healthcare St"
CLINIC_PHONE="+1-555-0123"
CLINIC_EMAIL="info@yourclinicdomain.com"
```

## 🚀 Deployment

### Production Setup
```bash
# Optimize for production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Set up queue worker (if using queues)
php artisan queue:work --daemon
```

### Nginx Configuration
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/dental-clinic/backend/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🧪 Testing

### Setup Testing
```bash
# Create testing database
php artisan migrate --env=testing

# Run tests
php artisan test

# Run specific test
php artisan test --filter=PatientTest

# Generate test coverage
php artisan test --coverage
```

### Example Test
```php
// tests/Feature/PatientTest.php
public function test_can_create_patient()
{
    $user = User::factory()->create();
    $patientData = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'date_of_birth' => '1990-01-01',
        'gender' => 'male',
        'phone' => '+1234567890',
        'address' => '123 Main St',
        'city' => 'Anytown',
        'zip_code' => '12345',
        'emergency_contact' => 'Jane Doe',
        'emergency_phone' => '+1234567891',
    ];

    $response = $this->actingAs($user)
                    ->postJson('/api/patients', $patientData);

    $response->assertStatus(201)
            ->assertJson(['message' => 'Patient created successfully']);
}
```

## 📝 API Documentation

### Generate API Documentation
```bash
# Install Laravel API Documentation Generator
composer require --dev knuckleswtf/scribe

# Generate documentation
php artisan scribe:generate

# Documentation will be available at /docs
```

## 🔍 Debugging

### Enable Query Logging
```php
// In AppServiceProvider boot method
if (app()->environment('local')) {
    DB::listen(function ($query) {
        Log::info($query->sql, $query->bindings);
    });
}
```

### Debug Bar (Development)
```bash
composer require barryvdh/laravel-debugbar --dev
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
```

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License.

---

**Built with Laravel for dental professionals to provide exceptional patient care.**