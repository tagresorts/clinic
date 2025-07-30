import React from 'react';
import { Box, Typography, Button } from '@mui/material';
import { Add } from '@mui/icons-material';

const AppointmentsPage: React.FC = () => {
  return (
    <Box>
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={3}>
        <Typography variant="h4" component="h1">
          Appointments
        </Typography>
        <Button variant="contained" startIcon={<Add />}>
          Schedule Appointment
        </Button>
      </Box>
      <Typography variant="body1" color="text.secondary">
        Appointment management functionality will be implemented here.
      </Typography>
    </Box>
  );
};

export default AppointmentsPage;