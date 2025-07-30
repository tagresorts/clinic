import express from 'express';
import { prisma } from '../index';
import { authenticateToken, AuthenticatedRequest, requireAdmin } from '../middleware/auth';
import { asyncHandler } from '../middleware/errorHandler';

const router = express.Router();

// Apply authentication to all routes
router.use(authenticateToken);
router.use(requireAdmin);

// GET /api/reports/dashboard - Get dashboard statistics
router.get('/dashboard', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const today = new Date();
  const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
  const startOfYear = new Date(today.getFullYear(), 0, 1);

  const [
    totalPatients,
    totalAppointments,
    monthlyRevenue,
    yearlyRevenue,
    upcomingAppointments,
  ] = await Promise.all([
    prisma.patient.count({ where: { isActive: true } }),
    prisma.appointment.count(),
    prisma.invoice.aggregate({
      where: {
        dateIssued: { gte: startOfMonth },
        paymentStatus: 'PAID',
      },
      _sum: { totalAmount: true },
    }),
    prisma.invoice.aggregate({
      where: {
        dateIssued: { gte: startOfYear },
        paymentStatus: 'PAID',
      },
      _sum: { totalAmount: true },
    }),
    prisma.appointment.count({
      where: {
        dateTime: { gte: today },
        status: { in: ['SCHEDULED', 'CONFIRMED'] },
      },
    }),
  ]);

  res.json({
    totalPatients,
    totalAppointments,
    monthlyRevenue: monthlyRevenue._sum.totalAmount || 0,
    yearlyRevenue: yearlyRevenue._sum.totalAmount || 0,
    upcomingAppointments,
  });
}));

export default router;