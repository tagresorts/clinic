import { Request, Response, NextFunction } from 'express';

interface RateLimitStore {
  [key: string]: {
    count: number;
    resetTime: number;
  };
}

const store: RateLimitStore = {};

// Clean up expired entries every 5 minutes
setInterval(() => {
  const now = Date.now();
  Object.keys(store).forEach(key => {
    if (store[key].resetTime < now) {
      delete store[key];
    }
  });
}, 5 * 60 * 1000);

export const rateLimiter = (
  windowMs: number = 15 * 60 * 1000, // 15 minutes
  maxRequests: number = 100 // limit each IP to 100 requests per windowMs
) => {
  return (req: Request, res: Response, next: NextFunction) => {
    const key = req.ip || 'unknown';
    const now = Date.now();
    
    // Initialize or reset if window has passed
    if (!store[key] || store[key].resetTime < now) {
      store[key] = {
        count: 1,
        resetTime: now + windowMs,
      };
      return next();
    }
    
    // Increment count
    store[key].count++;
    
    // Check if limit exceeded
    if (store[key].count > maxRequests) {
      const resetTimeSeconds = Math.ceil((store[key].resetTime - now) / 1000);
      
      res.set({
        'X-RateLimit-Limit': maxRequests.toString(),
        'X-RateLimit-Remaining': '0',
        'X-RateLimit-Reset': Math.ceil(store[key].resetTime / 1000).toString(),
      });
      
      return res.status(429).json({
        error: 'Too many requests',
        message: `Rate limit exceeded. Try again in ${resetTimeSeconds} seconds.`,
        retryAfter: resetTimeSeconds,
      });
    }
    
    // Set rate limit headers
    res.set({
      'X-RateLimit-Limit': maxRequests.toString(),
      'X-RateLimit-Remaining': (maxRequests - store[key].count).toString(),
      'X-RateLimit-Reset': Math.ceil(store[key].resetTime / 1000).toString(),
    });
    
    next();
  };
};