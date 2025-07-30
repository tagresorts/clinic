import React from 'react';
import { Box, Typography } from '@mui/material';

const CalendarPage: React.FC = () => {
  return (
    <Box>
      <Typography variant="h4" component="h1" gutterBottom>
        Calendar
      </Typography>
      <Typography variant="body1" color="text.secondary">
        Calendar view will be implemented here.
      </Typography>
    </Box>
  );
};

export default CalendarPage;