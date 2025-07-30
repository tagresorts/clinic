# Dental Clinic Patient Management System (DCPMS)

A comprehensive, modern web-based patient management system designed specifically for dental clinics. Built with React, TypeScript, Node.js, Express, and PostgreSQL.

## 🏥 Overview

The Dental Clinic Patient Management System streamlines administrative, clinical, and financial operations of dental clinics, improving efficiency, patient care, and data management. The system provides role-based access control for administrators, dentists, and receptionists.

## ✨ Key Features

### 👥 Patient Management
- **Patient Registration**: Complete patient demographics, contact information, and emergency contacts
- **Medical & Dental History**: Comprehensive health records and treatment history
- **Insurance Management**: Patient insurance details and claims processing
- **File Management**: Upload and manage patient documents, X-rays, and photos
- **Dental Charting**: Interactive dental chart for recording tooth conditions

### 📅 Appointment Management
- **Smart Scheduling**: Prevent double-booking with conflict detection
- **Calendar Views**: Daily, weekly, and monthly appointment calendars
- **Available Slots**: Real-time availability checking for efficient scheduling
- **Appointment Types**: Support for consultations, cleanings, procedures, and emergencies
- **Automated Reminders**: SMS/Email notifications 24 hours before appointments

### 🦷 Treatment Planning & Records
- **Treatment Plans**: Create and manage comprehensive treatment plans
- **Procedure Tracking**: Record completed procedures and treatment progress
- **Cost Estimation**: Detailed cost breakdowns for proposed treatments
- **Treatment History**: Complete timeline of patient treatments

### 💰 Billing & Financial Management
- **Invoice Generation**: Automated invoice creation from treatment records
- **Payment Tracking**: Support for multiple payment methods and partial payments
- **Outstanding Balances**: Track and manage unpaid invoices
- **Financial Reports**: Revenue tracking and financial analytics

### 📦 Inventory Management
- **Supply Tracking**: Monitor dental supplies and equipment
- **Automatic Alerts**: Low stock notifications and reorder reminders
- **Supplier Management**: Maintain supplier contacts and ordering information
- **Stock Movement**: Track inventory usage and restocking

### 📊 Reporting & Analytics
- **Financial Reports**: Revenue, expenses, and profitability analysis
- **Patient Analytics**: New registrations, demographics, and treatment statistics
- **Appointment Reports**: Scheduling efficiency and completion rates
- **Operational Insights**: Clinic performance metrics and trends

### 🔐 Security & Compliance
- **Role-Based Access**: Granular permissions for different user types
- **Data Encryption**: Industry-standard encryption for sensitive data
- **Audit Trails**: Complete logging of user actions and data changes
- **HIPAA Compliance**: Privacy and security standards for healthcare data

## 🏗️ Architecture

### Frontend (React + TypeScript)
- **Modern UI**: Material-UI components with responsive design
- **State Management**: React Context API for global state
- **Form Validation**: Comprehensive client-side validation
- **API Integration**: Axios for secure API communication
- **Routing**: React Router for single-page application navigation

### Backend (Node.js + Express + TypeScript)
- **RESTful API**: Clean, documented API endpoints
- **Authentication**: JWT-based authentication with role-based access
- **Database ORM**: Prisma for type-safe database operations
- **Validation**: Server-side validation with Joi
- **Security**: Helmet, CORS, and rate limiting

### Database (PostgreSQL)
- **Relational Design**: Normalized schema with proper relationships
- **Data Integrity**: Foreign key constraints and validation rules
- **Performance**: Optimized queries and indexing
- **Scalability**: Designed to handle growing clinic operations

## 🚀 Quick Start

### Prerequisites
- Node.js 18+ and npm
- PostgreSQL 12+
- Git

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd dental-clinic-management-system
   ```

2. **Install root dependencies**
   ```bash
   npm install
   ```

3. **Install all dependencies (backend + frontend)**
   ```bash
   npm run install:all
   ```

4. **Set up the database**
   ```bash
   # Create PostgreSQL database
   createdb dcpms_db
   ```

5. **Configure environment variables**
   ```bash
   # Backend configuration
   cd backend
   cp .env.example .env
   # Edit .env with your database URL and other settings
   ```

6. **Run database migrations**
   ```bash
   cd backend
   npm run migrate
   npm run db:generate
   ```

7. **Start the development servers**
   ```bash
   # From the root directory
   npm run dev
   ```

   This will start:
   - Backend API server on http://localhost:3001
   - Frontend React app on http://localhost:3000

### Initial Setup

1. **Create the first admin user** (via database or API):
   ```sql
   INSERT INTO users (username, email, password, role, "firstName", "lastName") 
   VALUES ('admin', 'admin@clinic.com', '$2b$12$hashedpassword', 'ADMINISTRATOR', 'System', 'Admin');
   ```

2. **Access the application**:
   - Open http://localhost:3000
   - Login with your admin credentials

## 👥 User Roles & Permissions

### 🔑 Administrator
- Full system access and configuration
- User management and role assignment
- Financial reports and analytics
- Inventory management
- System settings and maintenance

### 🦷 Dentist
- Patient medical and dental records
- Treatment planning and execution
- Appointment management (own appointments)
- Treatment records and notes
- Patient communication

### 📋 Receptionist
- Patient registration and updates
- Appointment scheduling for all dentists
- Basic billing and payment processing
- Patient communication and reminders
- Calendar management

## 🔧 Configuration

### Environment Variables

#### Backend (.env)
```bash
# Database
DATABASE_URL="postgresql://username:password@localhost:5432/dcpms_db"

# JWT Authentication
JWT_SECRET="your-super-secret-jwt-key"
JWT_EXPIRES_IN="24h"

# Server
PORT=3001
NODE_ENV="development"

# Email (for reminders)
EMAIL_HOST="smtp.gmail.com"
EMAIL_PORT=587
EMAIL_USER="your-email@gmail.com"
EMAIL_PASS="your-app-password"

# Security
BCRYPT_ROUNDS=12
CORS_ORIGIN="http://localhost:3000"
```

#### Frontend (.env)
```bash
REACT_APP_API_URL=http://localhost:3001/api
```

## 📱 API Documentation

### Authentication Endpoints
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout
- `GET /api/auth/me` - Get current user
- `POST /api/auth/change-password` - Change password
- `POST /api/auth/register` - Register new user (Admin only)

### Patient Endpoints
- `GET /api/patients` - List patients with pagination and search
- `POST /api/patients` - Create new patient
- `GET /api/patients/:id` - Get patient details
- `PUT /api/patients/:id` - Update patient
- `DELETE /api/patients/:id` - Soft delete patient
- `GET /api/patients/:id/dental-chart` - Get dental chart
- `POST /api/patients/:id/dental-chart` - Update dental chart

### Appointment Endpoints
- `GET /api/appointments` - List appointments with filters
- `POST /api/appointments` - Create appointment
- `GET /api/appointments/:id` - Get appointment details
- `PUT /api/appointments/:id` - Update appointment
- `DELETE /api/appointments/:id` - Cancel appointment
- `GET /api/appointments/calendar` - Calendar view
- `GET /api/appointments/available-slots` - Check availability

## 🔒 Security Features

- **Authentication**: JWT tokens with configurable expiration
- **Authorization**: Role-based access control (RBAC)
- **Data Encryption**: Bcrypt for passwords, TLS for data in transit
- **Input Validation**: Server-side validation for all inputs
- **Rate Limiting**: Prevent API abuse and brute force attacks
- **CORS Protection**: Configurable cross-origin resource sharing
- **SQL Injection Prevention**: Parameterized queries with Prisma
- **XSS Protection**: Content Security Policy headers

## 📊 Database Schema

The system uses a normalized PostgreSQL database with the following main entities:

- **Users**: System users (Admin, Dentist, Receptionist)
- **Patients**: Patient demographics and contact information
- **Appointments**: Scheduled appointments with conflict prevention
- **Treatment Plans**: Proposed treatments and cost estimates
- **Treatment Records**: Completed procedures and notes
- **Invoices & Payments**: Billing and payment tracking
- **Inventory**: Dental supplies and stock management
- **Audit Logs**: System activity tracking

## 🚀 Deployment

### Production Build
```bash
# Build both frontend and backend
npm run build

# Start production server
npm start
```

### Docker Deployment
```bash
# Build and run with Docker Compose
docker-compose up -d
```

### Environment Setup
- Set `NODE_ENV=production`
- Configure production database URL
- Set secure JWT secret
- Configure email service for reminders
- Set up SSL/TLS certificates
- Configure backup procedures

## 🔄 Development Workflow

### Available Scripts
```bash
# Development
npm run dev              # Start both frontend and backend
npm run dev:backend      # Start backend only
npm run dev:frontend     # Start frontend only

# Building
npm run build            # Build both applications
npm run build:backend    # Build backend only
npm run build:frontend   # Build frontend only

# Database
npm run migrate          # Run database migrations
npm run db:generate      # Generate Prisma client
npm run db:studio        # Open Prisma Studio

# Installation
npm run install:all      # Install all dependencies
```

### Code Structure
```
├── backend/                 # Node.js/Express API
│   ├── src/
│   │   ├── routes/         # API route handlers
│   │   ├── middleware/     # Authentication, validation, etc.
│   │   ├── services/       # Business logic
│   │   └── prisma/         # Database schema and migrations
│   └── package.json
├── frontend/               # React/TypeScript UI
│   ├── src/
│   │   ├── components/     # Reusable UI components
│   │   ├── pages/          # Page components
│   │   ├── contexts/       # React contexts
│   │   └── services/       # API communication
│   └── package.json
└── package.json           # Root package.json
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support, please contact the development team or create an issue in the repository.

## 🔮 Future Enhancements

- **Mobile Application**: Native iOS/Android apps
- **Telemedicine**: Video consultation integration
- **AI Integration**: Automated diagnosis assistance
- **Multi-location**: Support for multiple clinic branches
- **Integration APIs**: Connect with dental equipment and lab systems
- **Advanced Analytics**: Machine learning insights and predictions

---

**Built with ❤️ for dental professionals to provide better patient care.**
