import React from 'react';
import { Box, Typography } from '@mui/material';

const ReportsPage: React.FC = () => {
  return (
    <Box>
      <Typography variant="h4" component="h1" gutterBottom>
        Reports & Analytics
      </Typography>
      <Typography variant="body1" color="text.secondary">
        Financial and operational reports will be implemented here.
      </Typography>
    </Box>
  );
};

export default ReportsPage;