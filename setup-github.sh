#!/bin/bash

# Dental Clinic Management System - GitHub Setup Script
# This script helps prepare the project for GitHub repository

echo "🏥 Dental Clinic Management System - GitHub Setup"
echo "=================================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}✓${NC} $1"
}

print_info() {
    echo -e "${BLUE}ℹ${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

# Check if git is installed
if ! command -v git &> /dev/null; then
    print_error "Git is not installed. Please install Git first."
    exit 1
fi

print_status "Git is installed"

# Check if we're in the right directory
if [ ! -f "package.json" ] || [ ! -d "backend" ] || [ ! -d "frontend" ]; then
    print_error "Please run this script from the project root directory"
    exit 1
fi

print_status "In correct project directory"

# Initialize git repository if not already initialized
if [ ! -d ".git" ]; then
    print_info "Initializing Git repository..."
    git init
    print_status "Git repository initialized"
else
    print_status "Git repository already exists"
fi

# Create .env files from examples if they don't exist
if [ ! -f "backend/.env" ] && [ -f "backend/.env.example" ]; then
    print_info "Creating backend/.env from example..."
    cp backend/.env.example backend/.env
    print_status "Created backend/.env"
else
    print_warning "backend/.env already exists or .env.example not found"
fi

if [ ! -f "frontend/.env" ] && [ -f "frontend/.env.example" ]; then
    print_info "Creating frontend/.env from example..."
    cp frontend/.env.example frontend/.env
    print_status "Created frontend/.env"
else
    print_warning "frontend/.env already exists or .env.example not found"
fi

# Add all files to git
print_info "Adding files to Git..."
git add .

# Check if there are any changes to commit
if git diff --staged --quiet; then
    print_warning "No changes to commit"
else
    # Create initial commit
    print_info "Creating initial commit..."
    git commit -m "feat: initial commit - Dental Clinic Management System

- Laravel backend with comprehensive API
- React frontend with Material-UI
- Authentication with role-based access control
- Patient management system
- Appointment scheduling
- Treatment planning and records
- Billing and invoicing
- Inventory management
- Reporting and analytics
- Complete documentation and deployment guides"
    
    print_status "Initial commit created"
fi

# Get the repository URL from user
echo ""
print_info "Please provide your GitHub repository information:"
read -p "Enter your GitHub username: " github_username
read -p "Enter your repository name (default: clinic): " repo_name

# Set default repository name if not provided
if [ -z "$repo_name" ]; then
    repo_name="clinic"
fi

# Construct repository URL
repo_url="https://github.com/${github_username}/${repo_name}.git"

print_info "Repository URL: $repo_url"

# Add remote origin
if git remote get-url origin &> /dev/null; then
    print_warning "Remote 'origin' already exists. Updating..."
    git remote set-url origin "$repo_url"
else
    print_info "Adding remote origin..."
    git remote add origin "$repo_url"
fi

print_status "Remote origin configured"

# Set main branch
git branch -M main

echo ""
print_info "Setup complete! Here are the next steps:"
echo ""
echo "1. Make sure you have created the repository on GitHub:"
echo "   https://github.com/${github_username}/${repo_name}"
echo ""
echo "2. Push to GitHub with the following command:"
echo -e "   ${GREEN}git push -u origin main${NC}"
echo ""
echo "3. If you encounter authentication issues, you may need to:"
echo "   - Set up SSH keys: https://docs.github.com/en/authentication/connecting-to-github-with-ssh"
echo "   - Or use personal access token: https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/creating-a-personal-access-token"
echo ""
echo "4. After pushing, your repository will be available at:"
echo "   https://github.com/${github_username}/${repo_name}"
echo ""

# Ask if user wants to push now
read -p "Do you want to push to GitHub now? (y/N): " push_now

if [[ $push_now =~ ^[Yy]$ ]]; then
    print_info "Pushing to GitHub..."
    if git push -u origin main; then
        print_status "Successfully pushed to GitHub!"
        echo ""
        echo "🎉 Your Dental Clinic Management System is now on GitHub!"
        echo "   Repository: https://github.com/${github_username}/${repo_name}"
        echo ""
        echo "Next steps:"
        echo "- Set up your development environment (see README.md)"
        echo "- Configure your database and environment variables"
        echo "- Deploy to your preferred hosting platform (see DEPLOYMENT.md)"
    else
        print_error "Failed to push to GitHub"
        echo ""
        echo "This might be due to:"
        echo "- Repository doesn't exist on GitHub"
        echo "- Authentication issues"
        echo "- Network connectivity"
        echo ""
        echo "Please check the error message above and try again."
    fi
else
    print_info "You can push manually later with: git push -u origin main"
fi

echo ""
print_status "GitHub setup script completed!"
echo ""
echo "📚 Documentation available:"
echo "   - README.md - Main project documentation"
echo "   - backend/README.md - Laravel backend setup"
echo "   - CONTRIBUTING.md - Contribution guidelines"
echo "   - DEPLOYMENT.md - Deployment instructions"
echo ""
echo "🚀 Happy coding!"