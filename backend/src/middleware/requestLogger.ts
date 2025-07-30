import { Request, Response, NextFunction } from 'express';
import { AuthenticatedRequest } from './auth';

export const requestLogger = (req: AuthenticatedRequest, res: Response, next: NextFunction) => {
  const startTime = Date.now();
  
  // Log request
  console.log(`📥 ${new Date().toISOString()} - ${req.method} ${req.url} - IP: ${req.ip} - User: ${req.user?.username || 'anonymous'}`);
  
  // Override res.end to log response
  const originalEnd = res.end;
  res.end = function(chunk?: any, encoding?: any) {
    const duration = Date.now() - startTime;
    const statusCode = res.statusCode;
    const statusEmoji = statusCode >= 400 ? '❌' : statusCode >= 300 ? '⚠️' : '✅';
    
    console.log(`📤 ${new Date().toISOString()} - ${req.method} ${req.url} - ${statusEmoji} ${statusCode} - ${duration}ms`);
    
    // Call original end method
    originalEnd.call(this, chunk, encoding);
  };
  
  next();
};