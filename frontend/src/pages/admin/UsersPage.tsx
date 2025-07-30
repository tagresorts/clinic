import React from 'react';
import { Box, Typography } from '@mui/material';

const UsersPage: React.FC = () => {
  return (
    <Box>
      <Typography variant="h4" component="h1" gutterBottom>
        User Management
      </Typography>
      <Typography variant="body1" color="text.secondary">
        User and role management will be implemented here.
      </Typography>
    </Box>
  );
};

export default UsersPage;