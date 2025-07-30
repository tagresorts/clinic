import React from 'react';
import { Box, Typography } from '@mui/material';

const TreatmentsPage: React.FC = () => {
  return (
    <Box>
      <Typography variant="h4" component="h1" gutterBottom>
        Treatments
      </Typography>
      <Typography variant="body1" color="text.secondary">
        Treatment planning and records will be implemented here.
      </Typography>
    </Box>
  );
};

export default TreatmentsPage;