import React from 'react';
import { Box, Typography, Button } from '@mui/material';
import { Add } from '@mui/icons-material';

const PatientsPage: React.FC = () => {
  return (
    <Box>
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={3}>
        <Typography variant="h4" component="h1">
          Patients
        </Typography>
        <Button variant="contained" startIcon={<Add />}>
          Add Patient
        </Button>
      </Box>
      <Typography variant="body1" color="text.secondary">
        Patient management functionality will be implemented here.
      </Typography>
    </Box>
  );
};

export default PatientsPage;