import express from 'express';
import Joi from 'joi';
import { prisma } from '../index';
import { authenticateToken, AuthenticatedRequest, requireAdmin } from '../middleware/auth';
import { asyncHandler } from '../middleware/errorHandler';

const router = express.Router();

// Apply authentication to all routes
router.use(authenticateToken);

// GET /api/inventory/items - Get inventory items
router.get('/items', asyncHandler(async (req: AuthenticatedRequest, res) => {
  const items = await prisma.inventoryItem.findMany({
    where: { isActive: true },
    include: {
      supplier: {
        select: {
          name: true,
        },
      },
    },
    orderBy: { name: 'asc' },
  });

  res.json({ items });
}));

// POST /api/inventory/items - Create inventory item (Admin only)
const inventoryItemSchema = Joi.object({
  name: Joi.string().min(2).max(100).required(),
  description: Joi.string().max(500).optional().allow(''),
  category: Joi.string().max(50).optional().allow(''),
  quantityInStock: Joi.number().integer().min(0).default(0),
  unitCost: Joi.number().positive().precision(2).required(),
  reorderLevel: Joi.number().integer().min(0).default(10),
  supplierId: Joi.string().optional(),
  sku: Joi.string().max(50).optional().allow(''),
});

router.post('/items', requireAdmin, asyncHandler(async (req: AuthenticatedRequest, res) => {
  const { error, value } = inventoryItemSchema.validate(req.body);
  if (error) throw error;

  const item = await prisma.inventoryItem.create({
    data: {
      ...value,
      description: value.description || null,
      category: value.category || null,
      supplierId: value.supplierId || null,
      sku: value.sku || null,
    },
  });

  res.status(201).json({
    message: 'Inventory item created successfully',
    item,
  });
}));

export default router;