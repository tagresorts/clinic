import express from 'express';
import { UserRole } from '@prisma/client';
import { prisma } from '../index';
import { authenticateToken, AuthenticatedRequest, requireAdmin } from '../middleware/auth';
import { asyncHandler } from '../middleware/errorHandler';

const router = express.Router();

// Apply authentication to all routes
router.use(authenticateToken);

// GET /api/users - Get all users (Admin only)
router.get('/', requireAdmin, asyncHandler(async (req: AuthenticatedRequest, res) => {
  const users = await prisma.user.findMany({
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
    orderBy: { createdAt: 'desc' },
  });

  res.json({ users });
}));

// GET /api/users/dentists - Get all dentists
router.get('/dentists', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const dentists = await prisma.user.findMany({
    where: {
      role: UserRole.DENTIST,
      isActive: true,
    },
    select: {
      id: true,
      firstName: true,
      lastName: true,
      email: true,
      phone: true,
    },
    orderBy: { firstName: 'asc' },
  });

  res.json({ dentists });
}));

export default router;