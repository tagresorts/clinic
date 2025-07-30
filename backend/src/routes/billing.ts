import express from 'express';
import Joi from 'joi';
import { PaymentMethod, PaymentStatus } from '@prisma/client';
import { prisma } from '../index';
import { authenticateToken, AuthenticatedRequest, requireAnyRole } from '../middleware/auth';
import { asyncHandler, createError } from '../middleware/errorHandler';

const router = express.Router();

// Apply authentication to all routes
router.use(authenticateToken);
router.use(requireAnyRole);

// GET /api/billing/invoices - Get invoices
router.get('/invoices', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const page = parseInt(req.query.page as string) || 1;
  const limit = parseInt(req.query.limit as string) || 20;
  const patientId = req.query.patientId as string;
  const status = req.query.status as PaymentStatus;

  const skip = (page - 1) * limit;
  const whereConditions: any = {};
  
  if (patientId) whereConditions.patientId = patientId;
  if (status) whereConditions.paymentStatus = status;

  const [invoices, total] = await Promise.all([
    prisma.invoice.findMany({
      where: whereConditions,
      include: {
        patient: {
          select: {
            firstName: true,
            lastName: true,
          },
        },
        items: true,
        payments: {
          orderBy: { paymentDate: 'desc' },
        },
      },
      orderBy: { dateIssued: 'desc' },
      skip,
      take: limit,
    }),
    prisma.invoice.count({ where: whereConditions }),
  ]);

  res.json({
    invoices,
    pagination: {
      page,
      limit,
      total,
      pages: Math.ceil(total / limit),
    },
  });
}));

// POST /api/billing/invoices - Create invoice
const invoiceSchema = Joi.object({
  patientId: Joi.string().required(),
  dueDate: Joi.date().min('now').required(),
  items: Joi.array().items(Joi.object({
    description: Joi.string().required(),
    quantity: Joi.number().integer().min(1).default(1),
    unitPrice: Joi.number().positive().precision(2).required(),
  })).min(1).required(),
  notes: Joi.string().max(1000).optional().allow(''),
});

router.post('/invoices', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const { error, value } = invoiceSchema.validate(req.body);
  if (error) throw error;

  const { patientId, dueDate, items, notes } = value;

  // Calculate total
  const totalAmount = items.reduce((sum: number, item: any) => 
    sum + (item.quantity * item.unitPrice), 0);

  // Generate invoice number
  const invoiceCount = await prisma.invoice.count();
  const invoiceNumber = `INV-${String(invoiceCount + 1).padStart(6, '0')}`;

  const invoice = await prisma.invoice.create({
    data: {
      invoiceNumber,
      patientId,
      dueDate: new Date(dueDate),
      totalAmount,
      outstandingBalance: totalAmount,
      notes: notes || null,
      createdById: req.user!.id,
      items: {
        create: items.map((item: any) => ({
          description: item.description,
          quantity: item.quantity,
          unitPrice: item.unitPrice,
          totalPrice: item.quantity * item.unitPrice,
        })),
      },
    },
    include: {
      patient: {
        select: {
          firstName: true,
          lastName: true,
        },
      },
      items: true,
    },
  });

  res.status(201).json({
    message: 'Invoice created successfully',
    invoice,
  });
}));

export default router;