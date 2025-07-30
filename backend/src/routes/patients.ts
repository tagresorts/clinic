import express from 'express';
import Joi from 'joi';
import { Gender } from '@prisma/client';
import { prisma } from '../index';
import { authenticateToken, AuthenticatedRequest, requireAnyRole } from '../middleware/auth';
import { asyncHandler, createError } from '../middleware/errorHandler';

const router = express.Router();

// Validation schemas
const patientSchema = Joi.object({
  firstName: Joi.string().min(2).max(50).required(),
  lastName: Joi.string().min(2).max(50).required(),
  dateOfBirth: Joi.date().max('now').required(),
  gender: Joi.string().valid(...Object.values(Gender)).required(),
  phone: Joi.string().pattern(/^[\+]?[1-9][\d]{0,15}$/).required(),
  email: Joi.string().email().optional().allow(''),
  address: Joi.string().min(5).max(200).required(),
  city: Joi.string().min(2).max(50).required(),
  state: Joi.string().min(2).max(50).optional().allow(''),
  zipCode: Joi.string().min(3).max(10).required(),
  emergencyContact: Joi.string().min(2).max(100).required(),
  emergencyPhone: Joi.string().pattern(/^[\+]?[1-9][\d]{0,15}$/).required(),
  medicalHistory: Joi.string().max(2000).optional().allow(''),
  dentalHistory: Joi.string().max(2000).optional().allow(''),
  allergies: Joi.string().max(1000).optional().allow(''),
  medications: Joi.string().max(1000).optional().allow(''),
  insuranceProvider: Joi.string().max(100).optional().allow(''),
  insuranceNumber: Joi.string().max(50).optional().allow(''),
  notes: Joi.string().max(2000).optional().allow(''),
});

const updatePatientSchema = patientSchema.fork(
  ['firstName', 'lastName', 'dateOfBirth', 'gender', 'phone', 'address', 'city', 'zipCode', 'emergencyContact', 'emergencyPhone'],
  (schema) => schema.optional()
);

// Apply authentication to all routes
router.use(authenticateToken);
router.use(requireAnyRole);

// GET /api/patients - Get all patients with pagination and search
router.get('/', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const page = parseInt(req.query.page as string) || 1;
  const limit = parseInt(req.query.limit as string) || 20;
  const search = req.query.search as string || '';
  const sortBy = req.query.sortBy as string || 'createdAt';
  const sortOrder = req.query.sortOrder as string || 'desc';

  const skip = (page - 1) * limit;

  // Build search conditions
  const searchConditions = search ? {
    OR: [
      { firstName: { contains: search, mode: 'insensitive' } },
      { lastName: { contains: search, mode: 'insensitive' } },
      { phone: { contains: search } },
      { email: { contains: search, mode: 'insensitive' } },
    ],
  } : {};

  const [patients, total] = await Promise.all([
    prisma.patient.findMany({
      where: {
        isActive: true,
        ...searchConditions,
      },
      select: {
        id: true,
        firstName: true,
        lastName: true,
        dateOfBirth: true,
        gender: true,
        phone: true,
        email: true,
        city: true,
        createdAt: true,
        _count: {
          select: {
            appointments: true,
            treatmentPlans: true,
          },
        },
      },
      orderBy: { [sortBy]: sortOrder },
      skip,
      take: limit,
    }),
    prisma.patient.count({
      where: {
        isActive: true,
        ...searchConditions,
      },
    }),
  ]);

  res.json({
    patients,
    pagination: {
      page,
      limit,
      total,
      pages: Math.ceil(total / limit),
    },
  });
}));

// GET /api/patients/:id - Get patient by ID
router.get('/:id', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const { id } = req.params;

  const patient = await prisma.patient.findUnique({
    where: { id, isActive: true },
    include: {
      appointments: {
        orderBy: { dateTime: 'desc' },
        take: 10,
        include: {
          dentist: {
            select: { firstName: true, lastName: true },
          },
        },
      },
      treatmentPlans: {
        orderBy: { createdAt: 'desc' },
        include: {
          dentist: {
            select: { firstName: true, lastName: true },
          },
        },
      },
      invoices: {
        orderBy: { dateIssued: 'desc' },
        take: 5,
        select: {
          id: true,
          invoiceNumber: true,
          totalAmount: true,
          paymentStatus: true,
          dateIssued: true,
        },
      },
      dentalCharts: {
        orderBy: { toothNumber: 'asc' },
      },
      patientFiles: {
        orderBy: { uploadedAt: 'desc' },
        select: {
          id: true,
          fileName: true,
          originalName: true,
          fileType: true,
          fileSize: true,
          description: true,
          uploadedAt: true,
        },
      },
    },
  });

  if (!patient) {
    throw createError('Patient not found', 404);
  }

  res.json({ patient });
}));

// POST /api/patients - Create new patient
router.post('/', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const { error, value } = patientSchema.validate(req.body);
  if (error) throw error;

  const patient = await prisma.patient.create({
    data: {
      ...value,
      email: value.email || null,
      state: value.state || null,
      medicalHistory: value.medicalHistory || null,
      dentalHistory: value.dentalHistory || null,
      allergies: value.allergies || null,
      medications: value.medications || null,
      insuranceProvider: value.insuranceProvider || null,
      insuranceNumber: value.insuranceNumber || null,
      notes: value.notes || null,
    },
    select: {
      id: true,
      firstName: true,
      lastName: true,
      dateOfBirth: true,
      gender: true,
      phone: true,
      email: true,
      address: true,
      city: true,
      state: true,
      zipCode: true,
      emergencyContact: true,
      emergencyPhone: true,
      createdAt: true,
    },
  });

  res.status(201).json({
    message: 'Patient created successfully',
    patient,
  });
}));

// PUT /api/patients/:id - Update patient
router.put('/:id', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const { id } = req.params;
  const { error, value } = updatePatientSchema.validate(req.body);
  if (error) throw error;

  // Check if patient exists
  const existingPatient = await prisma.patient.findUnique({
    where: { id, isActive: true },
  });

  if (!existingPatient) {
    throw createError('Patient not found', 404);
  }

  const patient = await prisma.patient.update({
    where: { id },
    data: {
      ...value,
      email: value.email || null,
      state: value.state || null,
      medicalHistory: value.medicalHistory || null,
      dentalHistory: value.dentalHistory || null,
      allergies: value.allergies || null,
      medications: value.medications || null,
      insuranceProvider: value.insuranceProvider || null,
      insuranceNumber: value.insuranceNumber || null,
      notes: value.notes || null,
    },
    select: {
      id: true,
      firstName: true,
      lastName: true,
      dateOfBirth: true,
      gender: true,
      phone: true,
      email: true,
      address: true,
      city: true,
      state: true,
      zipCode: true,
      emergencyContact: true,
      emergencyPhone: true,
      medicalHistory: true,
      dentalHistory: true,
      allergies: true,
      medications: true,
      insuranceProvider: true,
      insuranceNumber: true,
      notes: true,
      updatedAt: true,
    },
  });

  res.json({
    message: 'Patient updated successfully',
    patient,
  });
}));

// DELETE /api/patients/:id - Soft delete patient
router.delete('/:id', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const { id } = req.params;

  // Check if patient exists
  const existingPatient = await prisma.patient.findUnique({
    where: { id, isActive: true },
  });

  if (!existingPatient) {
    throw createError('Patient not found', 404);
  }

  // Soft delete by setting isActive to false
  await prisma.patient.update({
    where: { id },
    data: { isActive: false },
  });

  res.json({
    message: 'Patient deleted successfully',
  });
}));

// GET /api/patients/:id/dental-chart - Get patient dental chart
router.get('/:id/dental-chart', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const { id } = req.params;

  // Verify patient exists
  const patient = await prisma.patient.findUnique({
    where: { id, isActive: true },
    select: { id: true, firstName: true, lastName: true },
  });

  if (!patient) {
    throw createError('Patient not found', 404);
  }

  const dentalChart = await prisma.dentalChart.findMany({
    where: { patientId: id },
    orderBy: { toothNumber: 'asc' },
  });

  res.json({
    patient,
    dentalChart,
  });
}));

// POST /api/patients/:id/dental-chart - Update dental chart
router.post('/:id/dental-chart', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const { id } = req.params;
  const { toothNumber, condition, notes } = req.body;

  // Validation
  const schema = Joi.object({
    toothNumber: Joi.number().integer().min(1).max(32).required(),
    condition: Joi.string().min(1).max(200).required(),
    notes: Joi.string().max(500).optional().allow(''),
  });

  const { error, value } = schema.validate({ toothNumber, condition, notes });
  if (error) throw error;

  // Verify patient exists
  const patient = await prisma.patient.findUnique({
    where: { id, isActive: true },
  });

  if (!patient) {
    throw createError('Patient not found', 404);
  }

  // Upsert dental chart entry
  const dentalChartEntry = await prisma.dentalChart.upsert({
    where: {
      patientId_toothNumber: {
        patientId: id,
        toothNumber: value.toothNumber,
      },
    },
    update: {
      condition: value.condition,
      notes: value.notes || null,
    },
    create: {
      patientId: id,
      toothNumber: value.toothNumber,
      condition: value.condition,
      notes: value.notes || null,
    },
  });

  res.json({
    message: 'Dental chart updated successfully',
    dentalChartEntry,
  });
}));

export default router;