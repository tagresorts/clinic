import express from 'express';
import bcrypt from 'bcryptjs';
import jwt from 'jsonwebtoken';
import Joi from 'joi';
import { UserRole } from '@prisma/client';
import { prisma } from '../index';
import { authenticateToken, AuthenticatedRequest, requireAdmin } from '../middleware/auth';
import { asyncHandler, createError } from '../middleware/errorHandler';

const router = express.Router();

// Validation schemas
const loginSchema = Joi.object({
  username: Joi.string().required(),
  password: Joi.string().required(),
});

const registerSchema = Joi.object({
  username: Joi.string().alphanum().min(3).max(30).required(),
  email: Joi.string().email().required(),
  password: Joi.string().min(8).pattern(new RegExp('^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])')).required()
    .messages({
      'string.pattern.base': 'Password must contain at least one lowercase letter, one uppercase letter, one number, and one special character'
    }),
  firstName: Joi.string().min(2).max(50).required(),
  lastName: Joi.string().min(2).max(50).required(),
  role: Joi.string().valid(...Object.values(UserRole)).required(),
  phone: Joi.string().optional(),
});

const changePasswordSchema = Joi.object({
  currentPassword: Joi.string().required(),
  newPassword: Joi.string().min(8).pattern(new RegExp('^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])')).required()
    .messages({
      'string.pattern.base': 'Password must contain at least one lowercase letter, one uppercase letter, one number, and one special character'
    }),
});

// Generate JWT token
const generateToken = (userId: string) => {
  return jwt.sign({ userId }, process.env.JWT_SECRET!, {
    expiresIn: process.env.JWT_EXPIRES_IN || '24h',
  });
};

// POST /api/auth/login
router.post('/login', asyncHandler(async (req, res) => {
  const { error, value } = loginSchema.validate(req.body);
  if (error) throw error;

  const { username, password } = value;

  // Find user
  const user = await prisma.user.findUnique({
    where: { username },
    select: {
      id: true,
      username: true,
      email: true,
      password: true,
      role: true,
      firstName: true,
      lastName: true,
      isActive: true,
    },
  });

  if (!user || !user.isActive) {
    throw createError('Invalid credentials', 401);
  }

  // Verify password
  const isValidPassword = await bcrypt.compare(password, user.password);
  if (!isValidPassword) {
    throw createError('Invalid credentials', 401);
  }

  // Generate token
  const token = generateToken(user.id);

  // Remove password from response
  const { password: _, ...userWithoutPassword } = user;

  res.json({
    message: 'Login successful',
    token,
    user: userWithoutPassword,
  });
}));

// POST /api/auth/register (Admin only)
router.post('/register', authenticateToken, requireAdmin, asyncHandler(async (req, res) => {
  const { error, value } = registerSchema.validate(req.body);
  if (error) throw error;

  const { username, email, password, firstName, lastName, role, phone } = value;

  // Hash password
  const saltRounds = parseInt(process.env.BCRYPT_ROUNDS!) || 12;
  const hashedPassword = await bcrypt.hash(password, saltRounds);

  // Create user
  const user = await prisma.user.create({
    data: {
      username,
      email,
      password: hashedPassword,
      firstName,
      lastName,
      role: role as UserRole,
      phone,
    },
    select: {
      id: true,
      username: true,
      email: true,
      role: true,
      firstName: true,
      lastName: true,
      phone: true,
      isActive: true,
      createdAt: true,
    },
  });

  res.status(201).json({
    message: 'User created successfully',
    user,
  });
}));

// POST /api/auth/change-password
router.post('/change-password', authenticateToken, asyncHandler(async (req: AuthenticatedRequest, res) => {
  const { error, value } = changePasswordSchema.validate(req.body);
  if (error) throw error;

  const { currentPassword, newPassword } = value;
  const userId = req.user!.id;

  // Get current user with password
  const user = await prisma.user.findUnique({
    where: { id: userId },
    select: { password: true },
  });

  if (!user) {
    throw createError('User not found', 404);
  }

  // Verify current password
  const isValidPassword = await bcrypt.compare(currentPassword, user.password);
  if (!isValidPassword) {
    throw createError('Current password is incorrect', 400);
  }

  // Hash new password
  const saltRounds = parseInt(process.env.BCRYPT_ROUNDS!) || 12;
  const hashedPassword = await bcrypt.hash(newPassword, saltRounds);

  // Update password
  await prisma.user.update({
    where: { id: userId },
    data: { password: hashedPassword },
  });

  res.json({
    message: 'Password changed successfully',
  });
}));

// GET /api/auth/me
router.get('/me', authenticateToken, asyncHandler(async (req: AuthenticatedRequest, res) => {
  const user = await prisma.user.findUnique({
    where: { id: req.user!.id },
    select: {
      id: true,
      username: true,
      email: true,
      role: true,
      firstName: true,
      lastName: true,
      phone: true,
      isActive: true,
      createdAt: true,
      updatedAt: true,
    },
  });

  if (!user) {
    throw createError('User not found', 404);
  }

  res.json({ user });
}));

// POST /api/auth/logout
router.post('/logout', authenticateToken, (req: AuthenticatedRequest, res) => {
  // In a real application, you might want to blacklist the token
  // For now, we'll just return a success message
  res.json({
    message: 'Logout successful',
  });
});

export default router;