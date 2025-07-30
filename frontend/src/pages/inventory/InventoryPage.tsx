import React from 'react';
import { Box, Typography } from '@mui/material';

const InventoryPage: React.FC = () => {
  return (
    <Box>
      <Typography variant="h4" component="h1" gutterBottom>
        Inventory Management
      </Typography>
      <Typography variant="body1" color="text.secondary">
        Dental supplies inventory management will be implemented here.
      </Typography>
    </Box>
  );
};

export default InventoryPage;