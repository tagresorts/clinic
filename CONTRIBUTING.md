# Contributing to Dental Clinic Management System

Thank you for your interest in contributing to the Dental Clinic Management System! This document provides guidelines and information for contributors.

## 🤝 How to Contribute

### Reporting Issues
1. **Search existing issues** first to avoid duplicates
2. **Use issue templates** when available
3. **Provide detailed information** including:
   - Steps to reproduce
   - Expected vs actual behavior
   - System information (OS, PHP version, etc.)
   - Screenshots if applicable

### Feature Requests
1. **Check existing feature requests** to avoid duplicates
2. **Describe the feature** clearly and provide use cases
3. **Explain why** this feature would be valuable
4. **Consider the scope** - keep features focused and manageable

### Code Contributions

#### Before You Start
1. **Fork the repository** to your GitHub account
2. **Create a feature branch** from `main`
3. **Check existing pull requests** to avoid duplicate work

#### Development Setup
```bash
# Clone your fork
git clone https://github.com/YOUR_USERNAME/clinic.git
cd clinic

# Install dependencies
npm run install:all

# Set up environment files
cp backend/.env.example backend/.env
cp frontend/.env.example frontend/.env

# Set up database and run migrations
cd backend
php artisan migrate
php artisan db:seed
```

#### Coding Standards

##### Backend (Laravel/PHP)
- Follow **PSR-12** coding standards
- Use **PHP 8.1+** features appropriately
- Write **PHPDoc comments** for all methods
- Use **type hints** for parameters and return types
- Follow **Laravel best practices**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StorePatientRequest;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{
    /**
     * Store a newly created patient.
     */
    public function store(StorePatientRequest $request): JsonResponse
    {
        $patient = Patient::create($request->validated());
        
        return response()->json([
            'message' => 'Patient created successfully',
            'patient' => $patient,
        ], 201);
    }
}
```

##### Frontend (React/TypeScript)
- Use **TypeScript** for all new code
- Follow **React best practices** and hooks patterns
- Use **functional components** over class components
- Implement **proper error handling**
- Write **accessible** components

```typescript
interface PatientFormProps {
  onSubmit: (data: PatientData) => Promise<void>;
  initialData?: Partial<PatientData>;
  loading?: boolean;
}

const PatientForm: React.FC<PatientFormProps> = ({
  onSubmit,
  initialData,
  loading = false
}) => {
  // Component implementation
};
```

#### Testing
- **Write tests** for new features and bug fixes
- **Maintain test coverage** above 80%
- **Run existing tests** before submitting

```bash
# Backend tests
cd backend
php artisan test

# Frontend tests
cd frontend
npm test
```

#### Commit Guidelines
Use **conventional commits** format:

```
type(scope): description

feat(patients): add patient search functionality
fix(auth): resolve token expiration issue
docs(readme): update installation instructions
style(frontend): fix component styling
refactor(backend): optimize database queries
test(patients): add patient creation tests
```

Types:
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, etc.)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

#### Pull Request Process

1. **Create a descriptive title** and detailed description
2. **Reference related issues** using `Fixes #123` or `Closes #123`
3. **Include screenshots** for UI changes
4. **Update documentation** if needed
5. **Ensure all tests pass**
6. **Request review** from maintainers

##### Pull Request Template
```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
- [ ] Tests pass locally
- [ ] Added new tests for changes
- [ ] Manual testing completed

## Screenshots (if applicable)
[Add screenshots here]

## Checklist
- [ ] Code follows project style guidelines
- [ ] Self-review completed
- [ ] Documentation updated
- [ ] No new warnings or errors
```

## 🏗️ Project Structure

### Backend (Laravel)
```
backend/
├── app/
│   ├── Http/Controllers/Api/  # API Controllers
│   ├── Http/Requests/         # Form Validation
│   ├── Models/               # Eloquent Models
│   ├── Services/             # Business Logic
│   └── Policies/             # Authorization
├── database/
│   ├── migrations/           # Database Schema
│   ├── seeders/              # Sample Data
│   └── factories/            # Model Factories
├── routes/api.php           # API Routes
└── tests/                   # PHPUnit Tests
```

### Frontend (React)
```
frontend/
├── src/
│   ├── components/          # Reusable Components
│   │   ├── common/          # Common UI Components
│   │   └── layout/          # Layout Components
│   ├── pages/               # Page Components
│   ├── contexts/            # React Contexts
│   ├── services/            # API Services
│   ├── hooks/               # Custom Hooks
│   ├── types/               # TypeScript Types
│   └── utils/               # Utility Functions
└── public/                  # Static Assets
```

## 🔒 Security Guidelines

### Reporting Security Issues
- **Do not** create public issues for security vulnerabilities
- **Email security issues** to [security@example.com]
- **Provide detailed information** about the vulnerability
- **Allow time** for the issue to be addressed before disclosure

### Security Best Practices
- **Validate all inputs** on both client and server
- **Use parameterized queries** to prevent SQL injection
- **Implement proper authentication** and authorization
- **Sanitize output** to prevent XSS attacks
- **Use HTTPS** in production
- **Keep dependencies updated**

## 📚 Documentation

### Code Documentation
- **Comment complex logic** and business rules
- **Document API endpoints** with proper examples
- **Update README** for significant changes
- **Include JSDoc/PHPDoc** for all public methods

### API Documentation
- Use **OpenAPI/Swagger** specifications
- **Include request/response examples**
- **Document error responses**
- **Specify authentication requirements**

## 🎯 Areas for Contribution

### High Priority
- **Performance optimization** for large datasets
- **Mobile responsiveness** improvements
- **Accessibility** enhancements
- **Test coverage** expansion

### Feature Ideas
- **Appointment reminders** via SMS/Email
- **Patient portal** for self-service
- **Integration** with dental equipment
- **Advanced reporting** and analytics
- **Multi-language support**
- **Dark mode** implementation

### Documentation Needs
- **API documentation** improvements
- **Deployment guides** for different platforms
- **User manuals** for different roles
- **Video tutorials** for common tasks

## 💬 Community

### Communication Channels
- **GitHub Issues** - Bug reports and feature requests
- **GitHub Discussions** - General questions and ideas
- **Pull Request Reviews** - Code discussions

### Code of Conduct
- **Be respectful** and inclusive
- **Provide constructive feedback**
- **Help others learn** and grow
- **Focus on what's best** for the project

## 🏆 Recognition

Contributors will be recognized in:
- **README.md** contributors section
- **Release notes** for significant contributions
- **GitHub contributors** page

## 📄 License

By contributing to this project, you agree that your contributions will be licensed under the MIT License.

---

Thank you for contributing to the Dental Clinic Management System! Your efforts help improve healthcare technology and patient care. 🦷✨