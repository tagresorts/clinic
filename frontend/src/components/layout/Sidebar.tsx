import React from 'react';
import {
  Drawer,
  List,
  ListItem,
  ListItemButton,
  ListItemIcon,
  ListItemText,
  Divider,
  Box,
} from '@mui/material';
import {
  Dashboard,
  People,
  CalendarToday,
  LocalHospital,
  Receipt,
  Inventory,
  Assessment,
  SupervisorAccount,
  Event,
} from '@mui/icons-material';
import { useNavigate, useLocation } from 'react-router-dom';
import { useAuth } from '../../contexts/AuthContext';

const DRAWER_WIDTH = 240;

interface NavItem {
  text: string;
  icon: React.ReactElement;
  path: string;
  roles?: string[];
}

const navItems: NavItem[] = [
  {
    text: 'Dashboard',
    icon: <Dashboard />,
    path: '/dashboard',
  },
  {
    text: 'Patients',
    icon: <People />,
    path: '/patients',
  },
  {
    text: 'Appointments',
    icon: <CalendarToday />,
    path: '/appointments',
  },
  {
    text: 'Calendar',
    icon: <Event />,
    path: '/calendar',
  },
  {
    text: 'Treatments',
    icon: <LocalHospital />,
    path: '/treatments',
    roles: ['DENTIST', 'ADMINISTRATOR'],
  },
  {
    text: 'Billing',
    icon: <Receipt />,
    path: '/billing',
  },
  {
    text: 'Inventory',
    icon: <Inventory />,
    path: '/inventory',
    roles: ['ADMINISTRATOR'],
  },
  {
    text: 'Reports',
    icon: <Assessment />,
    path: '/reports',
    roles: ['ADMINISTRATOR'],
  },
  {
    text: 'Users',
    icon: <SupervisorAccount />,
    path: '/users',
    roles: ['ADMINISTRATOR'],
  },
];

const Sidebar: React.FC = () => {
  const navigate = useNavigate();
  const location = useLocation();
  const { user } = useAuth();

  const filteredNavItems = navItems.filter(item => 
    !item.roles || item.roles.includes(user?.role || '')
  );

  const handleNavigation = (path: string) => {
    navigate(path);
  };

  return (
    <Drawer
      variant="permanent"
      sx={{
        width: DRAWER_WIDTH,
        flexShrink: 0,
        '& .MuiDrawer-paper': {
          width: DRAWER_WIDTH,
          boxSizing: 'border-box',
          mt: 8, // Account for navbar height
          borderRight: '1px solid rgba(0, 0, 0, 0.12)',
        },
      }}
    >
      <Box sx={{ overflow: 'auto', pt: 1 }}>
        <List>
          {filteredNavItems.map((item) => (
            <ListItem key={item.text} disablePadding>
              <ListItemButton
                onClick={() => handleNavigation(item.path)}
                selected={location.pathname === item.path}
                sx={{
                  mx: 1,
                  borderRadius: 1,
                  '&.Mui-selected': {
                    backgroundColor: 'primary.main',
                    color: 'white',
                    '&:hover': {
                      backgroundColor: 'primary.dark',
                    },
                    '& .MuiListItemIcon-root': {
                      color: 'white',
                    },
                  },
                }}
              >
                <ListItemIcon
                  sx={{
                    color: location.pathname === item.path ? 'white' : 'text.secondary',
                  }}
                >
                  {item.icon}
                </ListItemIcon>
                <ListItemText
                  primary={item.text}
                  sx={{
                    '& .MuiListItemText-primary': {
                      fontWeight: location.pathname === item.path ? 600 : 400,
                    },
                  }}
                />
              </ListItemButton>
            </ListItem>
          ))}
        </List>
      </Box>
    </Drawer>
  );
};

export default Sidebar;