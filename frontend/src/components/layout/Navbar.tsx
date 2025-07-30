import React, { useState } from 'react';
import {
  AppBar,
  Toolbar,
  Typography,
  IconButton,
  Menu,
  MenuItem,
  Avatar,
  Box,
  Divider,
} from '@mui/material';
import {
  AccountCircle,
  Settings,
  Logout,
  Notifications,
} from '@mui/icons-material';
import { useAuth } from '../../contexts/AuthContext';
import { useNavigate } from 'react-router-dom';

const Navbar: React.FC = () => {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const [anchorEl, setAnchorEl] = useState<null | HTMLElement>(null);

  const handleMenuOpen = (event: React.MouseEvent<HTMLElement>) => {
    setAnchorEl(event.currentTarget);
  };

  const handleMenuClose = () => {
    setAnchorEl(null);
  };

  const handleLogout = () => {
    logout();
    handleMenuClose();
    navigate('/login');
  };

  const handleSettings = () => {
    navigate('/settings');
    handleMenuClose();
  };

  const getRoleColor = (role: string) => {
    switch (role) {
      case 'ADMINISTRATOR':
        return '#f44336';
      case 'DENTIST':
        return '#4caf50';
      case 'RECEPTIONIST':
        return '#2196f3';
      default:
        return '#757575';
    }
  };

  return (
    <AppBar
      position="fixed"
      sx={{
        zIndex: (theme) => theme.zIndex.drawer + 1,
        backgroundColor: 'white',
        color: 'text.primary',
        boxShadow: '0 2px 8px rgba(0,0,0,0.1)',
      }}
    >
      <Toolbar>
        <Typography
          variant="h6"
          component="div"
          sx={{
            flexGrow: 1,
            fontWeight: 600,
            color: 'primary.main',
          }}
        >
          Dental Clinic Management System
        </Typography>

        <Box sx={{ display: 'flex', alignItems: 'center', gap: 1 }}>
          <IconButton
            size="large"
            color="inherit"
          >
            <Notifications />
          </IconButton>

          <IconButton
            size="large"
            onClick={handleMenuOpen}
            color="inherit"
          >
            <Avatar
              sx={{
                width: 32,
                height: 32,
                bgcolor: getRoleColor(user?.role || ''),
                fontSize: '0.875rem',
              }}
            >
              {user?.firstName?.charAt(0)}{user?.lastName?.charAt(0)}
            </Avatar>
          </IconButton>

          <Menu
            anchorEl={anchorEl}
            open={Boolean(anchorEl)}
            onClose={handleMenuClose}
            onClick={handleMenuClose}
            PaperProps={{
              elevation: 3,
              sx: {
                mt: 1.5,
                minWidth: 220,
                '& .MuiMenuItem-root': {
                  px: 2,
                  py: 1,
                },
              },
            }}
          >
            <Box sx={{ px: 2, py: 1 }}>
              <Typography variant="subtitle1" fontWeight={600}>
                {user?.firstName} {user?.lastName}
              </Typography>
              <Typography variant="body2" color="text.secondary">
                {user?.role?.toLowerCase().replace('_', ' ')}
              </Typography>
              <Typography variant="body2" color="text.secondary">
                {user?.email}
              </Typography>
            </Box>
            <Divider />
            <MenuItem onClick={handleSettings}>
              <Settings sx={{ mr: 2 }} />
              Settings
            </MenuItem>
            <MenuItem onClick={handleLogout}>
              <Logout sx={{ mr: 2 }} />
              Logout
            </MenuItem>
          </Menu>
        </Box>
      </Toolbar>
    </AppBar>
  );
};

export default Navbar;