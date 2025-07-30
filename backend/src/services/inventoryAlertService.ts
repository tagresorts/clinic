import { prisma } from '../index';

export const inventoryAlertService = {
  async checkLowStock() {
    try {
      // Find items with stock below reorder level
      const lowStockItems = await prisma.inventoryItem.findMany({
        where: {
          isActive: true,
          quantityInStock: {
            lte: prisma.inventoryItem.fields.reorderLevel,
          },
        },
        include: {
          supplier: {
            select: {
              name: true,
              contactPerson: true,
              phone: true,
              email: true,
            },
          },
        },
      });

      console.log(`Found ${lowStockItems.length} items with low stock`);

      // Create alerts for items that don't already have unresolved alerts
      for (const item of lowStockItems) {
        const existingAlert = await prisma.lowStockAlert.findFirst({
          where: {
            inventoryItemId: item.id,
            isResolved: false,
          },
        });

        if (!existingAlert) {
          await prisma.lowStockAlert.create({
            data: {
              inventoryItemId: item.id,
              currentStock: item.quantityInStock,
              reorderLevel: item.reorderLevel,
            },
          });

          console.log(`Low stock alert created for ${item.name}: ${item.quantityInStock} remaining (reorder at ${item.reorderLevel})`);
        }
      }

      return lowStockItems.length;
    } catch (error) {
      console.error('Error checking low stock:', error);
      throw error;
    }
  },
};