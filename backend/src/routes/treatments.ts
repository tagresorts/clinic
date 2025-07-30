import express from 'express';
import Joi from 'joi';
import { TreatmentPlanStatus, UserRole } from '@prisma/client';
import { prisma } from '../index';
import { authenticateToken, AuthenticatedRequest, requireDentist } from '../middleware/auth';
import { asyncHandler, createError } from '../middleware/errorHandler';

const router = express.Router();

// Apply authentication to all routes
router.use(authenticateToken);

// Treatment Plan Routes
const treatmentPlanSchema = Joi.object({
  patientId: Joi.string().required(),
  diagnosis: Joi.string().min(10).max(2000).required(),
  proposedProcedures: Joi.string().min(10).max(2000).required(),
  estimatedCost: Joi.number().positive().precision(2).required(),
  notes: Joi.string().max(2000).optional().allow(''),
});

// GET /api/treatments/plans - Get treatment plans
router.get('/plans', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const patientId = req.query.patientId as string;
  const status = req.query.status as TreatmentPlanStatus;
  
  const whereConditions: any = {};
  if (patientId) whereConditions.patientId = patientId;
  if (status) whereConditions.status = status;
  
  // For dentists, only show their own treatment plans
  if (req.user!.role === UserRole.DENTIST) {
    whereConditions.dentistId = req.user!.id;
  }

  const treatmentPlans = await prisma.treatmentPlan.findMany({
    where: whereConditions,
    include: {
      patient: {
        select: {
          firstName: true,
          lastName: true,
        },
      },
      dentist: {
        select: {
          firstName: true,
          lastName: true,
        },
      },
    },
    orderBy: { createdAt: 'desc' },
  });

  res.json({ treatmentPlans });
}));

// POST /api/treatments/plans - Create treatment plan
router.post('/plans', requireDentist, asyncHandler(async (req: AuthenticatedRequest, res) => {
  const { error, value } = treatmentPlanSchema.validate(req.body);
  if (error) throw error;

  const treatmentPlan = await prisma.treatmentPlan.create({
    data: {
      ...value,
      dentistId: req.user!.id,
      notes: value.notes || null,
    },
    include: {
      patient: {
        select: {
          firstName: true,
          lastName: true,
        },
      },
    },
  });

  res.status(201).json({
    message: 'Treatment plan created successfully',
    treatmentPlan,
  });
}));

// Treatment Record Routes
const treatmentRecordSchema = Joi.object({
  patientId: Joi.string().required(),
  appointmentId: Joi.string().optional(),
  treatmentPlanId: Joi.string().optional(),
  procedurePerformed: Joi.string().min(5).max(1000).required(),
  cost: Joi.number().positive().precision(2).required(),
  duration: Joi.number().integer().min(1).max(480).optional(),
  notes: Joi.string().max(2000).optional().allow(''),
});

// POST /api/treatments/records - Create treatment record
router.post('/records', requireDentist, asyncHandler(async (req: AuthenticatedRequest, res) => {
  const { error, value } = treatmentRecordSchema.validate(req.body);
  if (error) throw error;

  const treatmentRecord = await prisma.treatmentRecord.create({
    data: {
      ...value,
      dentistId: req.user!.id,
      date: new Date(),
      notes: value.notes || null,
    },
    include: {
      patient: {
        select: {
          firstName: true,
          lastName: true,
        },
      },
    },
  });

  res.status(201).json({
    message: 'Treatment record created successfully',
    treatmentRecord,
  });
}));

export default router;