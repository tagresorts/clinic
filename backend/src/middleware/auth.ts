import { Request, Response, NextFunction } from 'express';
import jwt from 'jsonwebtoken';
import { UserRole } from '@prisma/client';
import { prisma } from '../index';

export interface AuthenticatedRequest extends Request {
  user?: {
    id: string;
    username: string;
    email: string;
    role: UserRole;
    firstName: string;
    lastName: string;
  };
}

export const authenticateToken = async (
  req: AuthenticatedRequest,
  res: Response,
  next: NextFunction
) => {
  try {
    const authHeader = req.headers['authorization'];
    const token = authHeader && authHeader.split(' ')[1]; // Bearer TOKEN

    if (!token) {
      return res.status(401).json({
        error: 'Access denied',
        message: 'No token provided',
      });
    }

    const decoded = jwt.verify(token, process.env.JWT_SECRET!) as any;
    
    // Fetch user from database to ensure they still exist and are active
    const user = await prisma.user.findUnique({
      where: { id: decoded.userId },
      select: {
        id: true,
        username: true,
        email: true,
        role: true,
        firstName: true,
        lastName: true,
        isActive: true,
      },
    });

    if (!user || !user.isActive) {
      return res.status(401).json({
        error: 'Access denied',
        message: 'Invalid or inactive user',
      });
    }

    req.user = user;
    next();
  } catch (error) {
    return res.status(403).json({
      error: 'Access denied',
      message: 'Invalid token',
    });
  }
};

export const requireRole = (...roles: UserRole[]) => {
  return (req: AuthenticatedRequest, res: Response, next: NextFunction) => {
    if (!req.user) {
      return res.status(401).json({
        error: 'Access denied',
        message: 'Authentication required',
      });
    }

    if (!roles.includes(req.user.role)) {
      return res.status(403).json({
        error: 'Access denied',
        message: 'Insufficient permissions',
      });
    }

    next();
  };
};

// Specific role middleware
export const requireAdmin = requireRole(UserRole.ADMINISTRATOR);
export const requireDentist = requireRole(UserRole.DENTIST, UserRole.ADMINISTRATOR);
export const requireReceptionist = requireRole(UserRole.RECEPTIONIST, UserRole.ADMINISTRATOR);
export const requireAnyRole = requireRole(
  UserRole.ADMINISTRATOR,
  UserRole.DENTIST,
  UserRole.RECEPTIONIST
);