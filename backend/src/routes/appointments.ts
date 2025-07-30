import express from 'express';
import Joi from 'joi';
import { AppointmentType, AppointmentStatus, UserRole } from '@prisma/client';
import { prisma } from '../index';
import { authenticateToken, AuthenticatedRequest, requireAnyRole } from '../middleware/auth';
import { asyncHandler, createError } from '../middleware/errorHandler';

const router = express.Router();

// Validation schemas
const appointmentSchema = Joi.object({
  patientId: Joi.string().required(),
  dentistId: Joi.string().required(),
  dateTime: Joi.date().min('now').required(),
  duration: Joi.number().integer().min(15).max(480).default(60), // 15 min to 8 hours
  type: Joi.string().valid(...Object.values(AppointmentType)).required(),
  reason: Joi.string().max(500).optional().allow(''),
  notes: Joi.string().max(1000).optional().allow(''),
});

const updateAppointmentSchema = Joi.object({
  patientId: Joi.string().optional(),
  dentistId: Joi.string().optional(),
  dateTime: Joi.date().min('now').optional(),
  duration: Joi.number().integer().min(15).max(480).optional(),
  type: Joi.string().valid(...Object.values(AppointmentType)).optional(),
  status: Joi.string().valid(...Object.values(AppointmentStatus)).optional(),
  reason: Joi.string().max(500).optional().allow(''),
  notes: Joi.string().max(1000).optional().allow(''),
});

// Apply authentication to all routes
router.use(authenticateToken);
router.use(requireAnyRole);

// GET /api/appointments - Get appointments with filters
router.get('/', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const page = parseInt(req.query.page as string) || 1;
  const limit = parseInt(req.query.limit as string) || 50;
  const dentistId = req.query.dentistId as string;
  const patientId = req.query.patientId as string;
  const status = req.query.status as AppointmentStatus;
  const startDate = req.query.startDate as string;
  const endDate = req.query.endDate as string;
  const view = req.query.view as string || 'list'; // 'list', 'calendar'

  const skip = (page - 1) * limit;

  // Build filter conditions
  const whereConditions: any = {};

  if (dentistId) whereConditions.dentistId = dentistId;
  if (patientId) whereConditions.patientId = patientId;
  if (status) whereConditions.status = status;

  // Date range filter
  if (startDate || endDate) {
    whereConditions.dateTime = {};
    if (startDate) whereConditions.dateTime.gte = new Date(startDate);
    if (endDate) whereConditions.dateTime.lte = new Date(endDate);
  }

  // For non-admin users, restrict access based on role
  if (req.user!.role === UserRole.DENTIST) {
    whereConditions.dentistId = req.user!.id;
  }

  const [appointments, total] = await Promise.all([
    prisma.appointment.findMany({
      where: whereConditions,
      include: {
        patient: {
          select: {
            id: true,
            firstName: true,
            lastName: true,
            phone: true,
            email: true,
          },
        },
        dentist: {
          select: {
            id: true,
            firstName: true,
            lastName: true,
          },
        },
      },
      orderBy: { dateTime: 'asc' },
      skip: view === 'calendar' ? 0 : skip,
      take: view === 'calendar' ? undefined : limit,
    }),
    prisma.appointment.count({ where: whereConditions }),
  ]);

  res.json({
    appointments,
    pagination: view === 'calendar' ? undefined : {
      page,
      limit,
      total,
      pages: Math.ceil(total / limit),
    },
  });
}));

// GET /api/appointments/calendar - Get calendar view
router.get('/calendar', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const startDate = req.query.startDate as string;
  const endDate = req.query.endDate as string;
  const dentistId = req.query.dentistId as string;

  if (!startDate || !endDate) {
    throw createError('Start date and end date are required for calendar view', 400);
  }

  const whereConditions: any = {
    dateTime: {
      gte: new Date(startDate),
      lte: new Date(endDate),
    },
  };

  if (dentistId) whereConditions.dentistId = dentistId;

  // For dentists, only show their own appointments
  if (req.user!.role === UserRole.DENTIST) {
    whereConditions.dentistId = req.user!.id;
  }

  const appointments = await prisma.appointment.findMany({
    where: whereConditions,
    include: {
      patient: {
        select: {
          id: true,
          firstName: true,
          lastName: true,
          phone: true,
        },
      },
      dentist: {
        select: {
          id: true,
          firstName: true,
          lastName: true,
        },
      },
    },
    orderBy: { dateTime: 'asc' },
  });

  res.json({ appointments });
}));

// GET /api/appointments/available-slots - Get available time slots
router.get('/available-slots', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const dentistId = req.query.dentistId as string;
  const date = req.query.date as string;
  const duration = parseInt(req.query.duration as string) || 60;

  if (!dentistId || !date) {
    throw createError('Dentist ID and date are required', 400);
  }

  const selectedDate = new Date(date);
  const startOfDay = new Date(selectedDate);
  startOfDay.setHours(8, 0, 0, 0); // 8 AM
  const endOfDay = new Date(selectedDate);
  endOfDay.setHours(18, 0, 0, 0); // 6 PM

  // Get existing appointments for the day
  const existingAppointments = await prisma.appointment.findMany({
    where: {
      dentistId,
      dateTime: {
        gte: startOfDay,
        lt: endOfDay,
      },
      status: {
        not: AppointmentStatus.CANCELLED,
      },
    },
    select: {
      dateTime: true,
      duration: true,
    },
    orderBy: { dateTime: 'asc' },
  });

  // Generate available slots (every 30 minutes from 8 AM to 6 PM)
  const availableSlots: Date[] = [];
  const slotInterval = 30; // minutes
  let currentTime = new Date(startOfDay);

  while (currentTime < endOfDay) {
    const slotEnd = new Date(currentTime.getTime() + duration * 60000);
    
    // Check if this slot conflicts with existing appointments
    const hasConflict = existingAppointments.some(apt => {
      const aptStart = new Date(apt.dateTime);
      const aptEnd = new Date(aptStart.getTime() + apt.duration * 60000);
      
      return (currentTime < aptEnd && slotEnd > aptStart);
    });

    if (!hasConflict && slotEnd <= endOfDay) {
      availableSlots.push(new Date(currentTime));
    }

    currentTime = new Date(currentTime.getTime() + slotInterval * 60000);
  }

  res.json({ availableSlots });
}));

// GET /api/appointments/:id - Get appointment by ID
router.get('/:id', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const { id } = req.params;

  const appointment = await prisma.appointment.findUnique({
    where: { id },
    include: {
      patient: {
        select: {
          id: true,
          firstName: true,
          lastName: true,
          phone: true,
          email: true,
          dateOfBirth: true,
          medicalHistory: true,
          allergies: true,
        },
      },
      dentist: {
        select: {
          id: true,
          firstName: true,
          lastName: true,
        },
      },
      treatmentRecords: {
        orderBy: { createdAt: 'desc' },
      },
    },
  });

  if (!appointment) {
    throw createError('Appointment not found', 404);
  }

  // Check access permissions
  if (req.user!.role === UserRole.DENTIST && appointment.dentistId !== req.user!.id) {
    throw createError('Access denied', 403);
  }

  res.json({ appointment });
}));

// POST /api/appointments - Create new appointment
router.post('/', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const { error, value } = appointmentSchema.validate(req.body);
  if (error) throw error;

  const { patientId, dentistId, dateTime, duration, type, reason, notes } = value;

  // Verify patient exists
  const patient = await prisma.patient.findUnique({
    where: { id: patientId, isActive: true },
  });
  if (!patient) {
    throw createError('Patient not found', 404);
  }

  // Verify dentist exists
  const dentist = await prisma.user.findUnique({
    where: { id: dentistId, role: UserRole.DENTIST, isActive: true },
  });
  if (!dentist) {
    throw createError('Dentist not found', 404);
  }

  // Check for conflicts
  const appointmentStart = new Date(dateTime);
  const appointmentEnd = new Date(appointmentStart.getTime() + duration * 60000);

  const conflictingAppointment = await prisma.appointment.findFirst({
    where: {
      dentistId,
      status: { not: AppointmentStatus.CANCELLED },
      AND: [
        { dateTime: { lt: appointmentEnd } },
        {
          OR: [
            {
              dateTime: { gte: appointmentStart },
            },
            {
              dateTime: {
                lt: appointmentStart,
              },
              // This is a complex condition to check if the existing appointment ends after our start time
              // We'll use a raw query or check this in application logic
            },
          ],
        },
      ],
    },
  });

  // Additional conflict check using raw query for precision
  const conflicts = await prisma.$queryRaw`
    SELECT id FROM appointments 
    WHERE dentist_id = ${dentistId}
    AND status != 'CANCELLED'
    AND date_time < ${appointmentEnd}
    AND (date_time + INTERVAL '1 minute' * duration) > ${appointmentStart}
  `;

  if (Array.isArray(conflicts) && conflicts.length > 0) {
    throw createError('Time slot is not available', 409);
  }

  const appointment = await prisma.appointment.create({
    data: {
      patientId,
      dentistId,
      dateTime: appointmentStart,
      duration,
      type,
      reason: reason || null,
      notes: notes || null,
    },
    include: {
      patient: {
        select: {
          firstName: true,
          lastName: true,
          phone: true,
          email: true,
        },
      },
      dentist: {
        select: {
          firstName: true,
          lastName: true,
        },
      },
    },
  });

  res.status(201).json({
    message: 'Appointment created successfully',
    appointment,
  });
}));

// PUT /api/appointments/:id - Update appointment
router.put('/:id', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const { id } = req.params;
  const { error, value } = updateAppointmentSchema.validate(req.body);
  if (error) throw error;

  // Check if appointment exists
  const existingAppointment = await prisma.appointment.findUnique({
    where: { id },
  });

  if (!existingAppointment) {
    throw createError('Appointment not found', 404);
  }

  // Check permissions
  if (req.user!.role === UserRole.DENTIST && existingAppointment.dentistId !== req.user!.id) {
    throw createError('Access denied', 403);
  }

  // If updating date/time or duration, check for conflicts
  if (value.dateTime || value.duration || value.dentistId) {
    const newDateTime = value.dateTime ? new Date(value.dateTime) : existingAppointment.dateTime;
    const newDuration = value.duration || existingAppointment.duration;
    const newDentistId = value.dentistId || existingAppointment.dentistId;
    const appointmentEnd = new Date(newDateTime.getTime() + newDuration * 60000);

    const conflicts = await prisma.$queryRaw`
      SELECT id FROM appointments 
      WHERE dentist_id = ${newDentistId}
      AND id != ${id}
      AND status != 'CANCELLED'
      AND date_time < ${appointmentEnd}
      AND (date_time + INTERVAL '1 minute' * duration) > ${newDateTime}
    `;

    if (Array.isArray(conflicts) && conflicts.length > 0) {
      throw createError('Time slot is not available', 409);
    }
  }

  const appointment = await prisma.appointment.update({
    where: { id },
    data: {
      ...value,
      reason: value.reason || null,
      notes: value.notes || null,
    },
    include: {
      patient: {
        select: {
          firstName: true,
          lastName: true,
          phone: true,
          email: true,
        },
      },
      dentist: {
        select: {
          firstName: true,
          lastName: true,
        },
      },
    },
  });

  res.json({
    message: 'Appointment updated successfully',
    appointment,
  });
}));

// DELETE /api/appointments/:id - Cancel appointment
router.delete('/:id', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const { id } = req.params;

  const existingAppointment = await prisma.appointment.findUnique({
    where: { id },
  });

  if (!existingAppointment) {
    throw createError('Appointment not found', 404);
  }

  // Check permissions
  if (req.user!.role === UserRole.DENTIST && existingAppointment.dentistId !== req.user!.id) {
    throw createError('Access denied', 403);
  }

  await prisma.appointment.update({
    where: { id },
    data: { status: AppointmentStatus.CANCELLED },
  });

  res.json({
    message: 'Appointment cancelled successfully',
  });
}));

// GET /api/appointments/upcoming - Get upcoming appointments
router.get('/upcoming/list', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const limit = parseInt(req.query.limit as string) || 10;
  const whereConditions: any = {
    dateTime: { gte: new Date() },
    status: { in: [AppointmentStatus.SCHEDULED, AppointmentStatus.CONFIRMED] },
  };

  // For dentists, only show their appointments
  if (req.user!.role === UserRole.DENTIST) {
    whereConditions.dentistId = req.user!.id;
  }

  const appointments = await prisma.appointment.findMany({
    where: whereConditions,
    include: {
      patient: {
        select: {
          firstName: true,
          lastName: true,
          phone: true,
        },
      },
      dentist: {
        select: {
          firstName: true,
          lastName: true,
        },
      },
    },
    orderBy: { dateTime: 'asc' },
    take: limit,
  });

  res.json({ appointments });
}));

export default router;