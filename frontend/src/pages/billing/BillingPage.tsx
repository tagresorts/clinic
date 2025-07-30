import React from 'react';
import { Box, Typography } from '@mui/material';

const BillingPage: React.FC = () => {
  return (
    <Box>
      <Typography variant="h4" component="h1" gutterBottom>
        Billing & Invoices
      </Typography>
      <Typography variant="body1" color="text.secondary">
        Billing and invoice management will be implemented here.
      </Typography>
    </Box>
  );
};

export default BillingPage;