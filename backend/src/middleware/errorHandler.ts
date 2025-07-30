import { Request, Response, NextFunction } from 'express';
import { Prisma } from '@prisma/client';
import { ValidationError } from 'joi';

export interface AppError extends Error {
  statusCode?: number;
  isOperational?: boolean;
}

export const errorHandler = (
  error: AppError | ValidationError | Prisma.PrismaClientKnownRequestError | Error,
  req: Request,
  res: Response,
  next: NextFunction
) => {
  let statusCode = 500;
  let message = 'Internal server error';
  let details: any = undefined;

  // Joi validation errors
  if ('isJoi' in error && error.isJoi) {
    const validationError = error as ValidationError;
    statusCode = 400;
    message = 'Validation error';
    details = validationError.details.map(detail => ({
      field: detail.path.join('.'),
      message: detail.message,
    }));
  }
  // Prisma errors
  else if (error instanceof Prisma.PrismaClientKnownRequestError) {
    switch (error.code) {
      case 'P2002':
        statusCode = 409;
        message = 'Unique constraint violation';
        details = {
          field: error.meta?.target,
          message: 'A record with this value already exists',
        };
        break;
      case 'P2025':
        statusCode = 404;
        message = 'Record not found';
        break;
      case 'P2003':
        statusCode = 400;
        message = 'Foreign key constraint violation';
        break;
      case 'P2014':
        statusCode = 400;
        message = 'Invalid ID provided';
        break;
      default:
        statusCode = 400;
        message = 'Database operation failed';
    }
  }
  // Custom application errors
  else if ('statusCode' in error && error.statusCode) {
    statusCode = error.statusCode;
    message = error.message;
  }
  // Default error handling
  else {
    message = error.message || 'Internal server error';
  }

  // Log error for debugging (in production, use proper logging service)
  console.error('Error:', {
    timestamp: new Date().toISOString(),
    url: req.url,
    method: req.method,
    statusCode,
    message,
    stack: error.stack,
    user: (req as any).user?.id || 'anonymous',
  });

  // Send error response
  const response: any = {
    error: message,
    timestamp: new Date().toISOString(),
  };

  if (details) {
    response.details = details;
  }

  // Include stack trace in development
  if (process.env.NODE_ENV === 'development') {
    response.stack = error.stack;
  }

  res.status(statusCode).json(response);
};

export const createError = (message: string, statusCode: number = 500): AppError => {
  const error = new Error(message) as AppError;
  error.statusCode = statusCode;
  error.isOperational = true;
  return error;
};

export const asyncHandler = (fn: Function) => {
  return (req: Request, res: Response, next: NextFunction) => {
    Promise.resolve(fn(req, res, next)).catch(next);
  };
};