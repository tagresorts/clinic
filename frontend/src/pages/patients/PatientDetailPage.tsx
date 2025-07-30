import React from 'react';
import { Box, Typography } from '@mui/material';

const PatientDetailPage: React.FC = () => {
  return (
    <Box>
      <Typography variant="h4" component="h1" gutterBottom>
        Patient Details
      </Typography>
      <Typography variant="body1" color="text.secondary">
        Patient detail view will be implemented here.
      </Typography>
    </Box>
  );
};

export default PatientDetailPage;