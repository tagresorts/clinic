import React, { useState, useEffect } from 'react';
import {
  Box,
  Grid,
  Card,
  CardContent,
  Typography,
  Button,
  List,
  ListItem,
  ListItemText,
  ListItemIcon,
  Chip,
  Avatar,
  IconButton,
} from '@mui/material';
import {
  People,
  CalendarToday,
  AttachMoney,
  TrendingUp,
  Add,
  Phone,
  Event,
  Person,
} from '@mui/icons-material';
import { useAuth } from '../contexts/AuthContext';
import { apiService } from '../services/apiService';
import { useNavigate } from 'react-router-dom';
import LoadingSpinner from '../components/common/LoadingSpinner';

interface DashboardStats {
  totalPatients: number;
  totalAppointments: number;
  monthlyRevenue: number;
  yearlyRevenue: number;
  upcomingAppointments: number;
}

interface UpcomingAppointment {
  id: string;
  dateTime: string;
  type: string;
  patient: {
    firstName: string;
    lastName: string;
    phone: string;
  };
  dentist: {
    firstName: string;
    lastName: string;
  };
}

const DashboardPage: React.FC = () => {
  const { user } = useAuth();
  const navigate = useNavigate();
  const [stats, setStats] = useState<DashboardStats | null>(null);
  const [upcomingAppointments, setUpcomingAppointments] = useState<UpcomingAppointment[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchDashboardData = async () => {
      try {
        const [statsResponse, appointmentsResponse] = await Promise.all([
          user?.role === 'ADMINISTRATOR' ? apiService.getDashboardStats() : Promise.resolve({ data: null }),
          apiService.getUpcomingAppointments(5),
        ]);

        if (statsResponse.data) {
          setStats(statsResponse.data);
        }
        setUpcomingAppointments(appointmentsResponse.data.appointments);
      } catch (error) {
        console.error('Error fetching dashboard data:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchDashboardData();
  }, [user?.role]);

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
    }).format(amount);
  };

  const formatDateTime = (dateTime: string) => {
    return new Date(dateTime).toLocaleString('en-US', {
      month: 'short',
      day: 'numeric',
      hour: 'numeric',
      minute: '2-digit',
      hour12: true,
    });
  };

  const getAppointmentTypeColor = (type: string) => {
    const colors: { [key: string]: string } = {
      CONSULTATION: '#2196f3',
      CLEANING: '#4caf50',
      EXTRACTION: '#f44336',
      FILLING: '#ff9800',
      ROOT_CANAL: '#9c27b0',
      EMERGENCY: '#f44336',
    };
    return colors[type] || '#757575';
  };

  if (loading) {
    return <LoadingSpinner message="Loading dashboard..." />;
  }

  return (
    <Box>
      <Box mb={3}>
        <Typography variant="h4" component="h1" gutterBottom>
          Welcome back, {user?.firstName}!
        </Typography>
        <Typography variant="body1" color="text.secondary">
          Here's what's happening at your clinic today.
        </Typography>
      </Box>

      <Grid container spacing={3}>
        {/* Statistics Cards - Admin only */}
        {user?.role === 'ADMINISTRATOR' && stats && (
          <>
            <Grid item xs={12} sm={6} md={3}>
              <Card>
                <CardContent>
                  <Box display="flex" alignItems="center">
                    <Avatar sx={{ bgcolor: 'primary.main', mr: 2 }}>
                      <People />
                    </Avatar>
                    <Box>
                      <Typography variant="h4" component="div">
                        {stats.totalPatients}
                      </Typography>
                      <Typography color="text.secondary">
                        Total Patients
                      </Typography>
                    </Box>
                  </Box>
                </CardContent>
              </Card>
            </Grid>

            <Grid item xs={12} sm={6} md={3}>
              <Card>
                <CardContent>
                  <Box display="flex" alignItems="center">
                    <Avatar sx={{ bgcolor: 'success.main', mr: 2 }}>
                      <CalendarToday />
                    </Avatar>
                    <Box>
                      <Typography variant="h4" component="div">
                        {stats.upcomingAppointments}
                      </Typography>
                      <Typography color="text.secondary">
                        Upcoming Appointments
                      </Typography>
                    </Box>
                  </Box>
                </CardContent>
              </Card>
            </Grid>

            <Grid item xs={12} sm={6} md={3}>
              <Card>
                <CardContent>
                  <Box display="flex" alignItems="center">
                    <Avatar sx={{ bgcolor: 'warning.main', mr: 2 }}>
                      <AttachMoney />
                    </Avatar>
                    <Box>
                      <Typography variant="h4" component="div">
                        {formatCurrency(stats.monthlyRevenue)}
                      </Typography>
                      <Typography color="text.secondary">
                        Monthly Revenue
                      </Typography>
                    </Box>
                  </Box>
                </CardContent>
              </Card>
            </Grid>

            <Grid item xs={12} sm={6} md={3}>
              <Card>
                <CardContent>
                  <Box display="flex" alignItems="center">
                    <Avatar sx={{ bgcolor: 'error.main', mr: 2 }}>
                      <TrendingUp />
                    </Avatar>
                    <Box>
                      <Typography variant="h4" component="div">
                        {formatCurrency(stats.yearlyRevenue)}
                      </Typography>
                      <Typography color="text.secondary">
                        Yearly Revenue
                      </Typography>
                    </Box>
                  </Box>
                </CardContent>
              </Card>
            </Grid>
          </>
        )}

        {/* Quick Actions */}
        <Grid item xs={12} md={6}>
          <Card>
            <CardContent>
              <Typography variant="h6" gutterBottom>
                Quick Actions
              </Typography>
              <Grid container spacing={2}>
                <Grid item xs={12} sm={6}>
                  <Button
                    fullWidth
                    variant="contained"
                    startIcon={<Add />}
                    onClick={() => navigate('/patients')}
                    sx={{ mb: 1 }}
                  >
                    New Patient
                  </Button>
                </Grid>
                <Grid item xs={12} sm={6}>
                  <Button
                    fullWidth
                    variant="outlined"
                    startIcon={<Event />}
                    onClick={() => navigate('/appointments')}
                    sx={{ mb: 1 }}
                  >
                    Schedule Appointment
                  </Button>
                </Grid>
                <Grid item xs={12} sm={6}>
                  <Button
                    fullWidth
                    variant="outlined"
                    startIcon={<CalendarToday />}
                    onClick={() => navigate('/calendar')}
                  >
                    View Calendar
                  </Button>
                </Grid>
                <Grid item xs={12} sm={6}>
                  <Button
                    fullWidth
                    variant="outlined"
                    startIcon={<Person />}
                    onClick={() => navigate('/patients')}
                  >
                    Find Patient
                  </Button>
                </Grid>
              </Grid>
            </CardContent>
          </Card>
        </Grid>

        {/* Upcoming Appointments */}
        <Grid item xs={12} md={6}>
          <Card>
            <CardContent>
              <Box display="flex" justifyContent="space-between" alignItems="center" mb={2}>
                <Typography variant="h6">
                  Upcoming Appointments
                </Typography>
                <Button
                  size="small"
                  onClick={() => navigate('/appointments')}
                >
                  View All
                </Button>
              </Box>
              
              {upcomingAppointments.length === 0 ? (
                <Typography color="text.secondary" textAlign="center" py={2}>
                  No upcoming appointments
                </Typography>
              ) : (
                <List dense>
                  {upcomingAppointments.map((appointment) => (
                    <ListItem
                      key={appointment.id}
                      sx={{
                        border: '1px solid',
                        borderColor: 'divider',
                        borderRadius: 1,
                        mb: 1,
                      }}
                    >
                      <ListItemIcon>
                        <Avatar
                          sx={{
                            bgcolor: getAppointmentTypeColor(appointment.type),
                            width: 32,
                            height: 32,
                            fontSize: '0.75rem',
                          }}
                        >
                          {appointment.patient.firstName.charAt(0)}
                          {appointment.patient.lastName.charAt(0)}
                        </Avatar>
                      </ListItemIcon>
                      <ListItemText
                        primary={
                          <Box display="flex" alignItems="center" gap={1}>
                            <Typography variant="subtitle2">
                              {appointment.patient.firstName} {appointment.patient.lastName}
                            </Typography>
                            <Chip
                              label={appointment.type.replace('_', ' ')}
                              size="small"
                              sx={{
                                bgcolor: getAppointmentTypeColor(appointment.type),
                                color: 'white',
                                fontSize: '0.7rem',
                              }}
                            />
                          </Box>
                        }
                        secondary={
                          <Box>
                            <Typography variant="body2" color="text.secondary">
                              {formatDateTime(appointment.dateTime)}
                            </Typography>
                            <Typography variant="caption" color="text.secondary">
                              Dr. {appointment.dentist.firstName} {appointment.dentist.lastName}
                            </Typography>
                          </Box>
                        }
                      />
                      <IconButton
                        size="small"
                        href={`tel:${appointment.patient.phone}`}
                      >
                        <Phone fontSize="small" />
                      </IconButton>
                    </ListItem>
                  ))}
                </List>
              )}
            </CardContent>
          </Card>
        </Grid>
      </Grid>
    </Box>
  );
};

export default DashboardPage;