import axios, { AxiosInstance, AxiosResponse } from 'axios';

class ApiService {
  private api: AxiosInstance;

  constructor() {
    this.api = axios.create({
      baseURL: process.env.REACT_APP_API_URL || 'http://localhost:3001/api',
      timeout: 10000,
      headers: {
        'Content-Type': 'application/json',
      },
    });

    // Request interceptor to add auth token
    this.api.interceptors.request.use(
      (config) => {
        const token = localStorage.getItem('token');
        if (token) {
          config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
      },
      (error) => {
        return Promise.reject(error);
      }
    );

    // Response interceptor to handle errors
    this.api.interceptors.response.use(
      (response) => response,
      (error) => {
        if (error.response?.status === 401) {
          // Token expired or invalid
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          window.location.href = '/login';
        }
        return Promise.reject(error);
      }
    );
  }

  // Generic HTTP methods
  async get<T = any>(url: string, config?: any): Promise<AxiosResponse<T>> {
    return this.api.get(url, config);
  }

  async post<T = any>(url: string, data?: any, config?: any): Promise<AxiosResponse<T>> {
    return this.api.post(url, data, config);
  }

  async put<T = any>(url: string, data?: any, config?: any): Promise<AxiosResponse<T>> {
    return this.api.put(url, data, config);
  }

  async patch<T = any>(url: string, data?: any, config?: any): Promise<AxiosResponse<T>> {
    return this.api.patch(url, data, config);
  }

  async delete<T = any>(url: string, config?: any): Promise<AxiosResponse<T>> {
    return this.api.delete(url, config);
  }

  // Set auth token
  setAuthToken(token: string | null) {
    if (token) {
      this.api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    } else {
      delete this.api.defaults.headers.common['Authorization'];
    }
  }

  // Auth endpoints
  async login(username: string, password: string) {
    return this.post('/auth/login', { username, password });
  }

  async logout() {
    return this.post('/auth/logout');
  }

  async getMe() {
    return this.get('/auth/me');
  }

  async changePassword(currentPassword: string, newPassword: string) {
    return this.post('/auth/change-password', { currentPassword, newPassword });
  }

  // Patient endpoints
  async getPatients(params?: any) {
    return this.get('/patients', { params });
  }

  async getPatient(id: string) {
    return this.get(`/patients/${id}`);
  }

  async createPatient(data: any) {
    return this.post('/patients', data);
  }

  async updatePatient(id: string, data: any) {
    return this.put(`/patients/${id}`, data);
  }

  async deletePatient(id: string) {
    return this.delete(`/patients/${id}`);
  }

  async getPatientDentalChart(id: string) {
    return this.get(`/patients/${id}/dental-chart`);
  }

  async updateDentalChart(id: string, data: any) {
    return this.post(`/patients/${id}/dental-chart`, data);
  }

  // Appointment endpoints
  async getAppointments(params?: any) {
    return this.get('/appointments', { params });
  }

  async getAppointment(id: string) {
    return this.get(`/appointments/${id}`);
  }

  async createAppointment(data: any) {
    return this.post('/appointments', data);
  }

  async updateAppointment(id: string, data: any) {
    return this.put(`/appointments/${id}`, data);
  }

  async cancelAppointment(id: string) {
    return this.delete(`/appointments/${id}`);
  }

  async getCalendarAppointments(params: any) {
    return this.get('/appointments/calendar', { params });
  }

  async getAvailableSlots(params: any) {
    return this.get('/appointments/available-slots', { params });
  }

  async getUpcomingAppointments(limit?: number) {
    return this.get('/appointments/upcoming/list', { params: { limit } });
  }

  // Treatment endpoints
  async getTreatmentPlans(params?: any) {
    return this.get('/treatments/plans', { params });
  }

  async createTreatmentPlan(data: any) {
    return this.post('/treatments/plans', data);
  }

  async createTreatmentRecord(data: any) {
    return this.post('/treatments/records', data);
  }

  // Billing endpoints
  async getInvoices(params?: any) {
    return this.get('/billing/invoices', { params });
  }

  async createInvoice(data: any) {
    return this.post('/billing/invoices', data);
  }

  // Inventory endpoints
  async getInventoryItems() {
    return this.get('/inventory/items');
  }

  async createInventoryItem(data: any) {
    return this.post('/inventory/items', data);
  }

  // User endpoints
  async getUsers() {
    return this.get('/users');
  }

  async getDentists() {
    return this.get('/users/dentists');
  }

  async createUser(data: any) {
    return this.post('/auth/register', data);
  }

  // Reports endpoints
  async getDashboardStats() {
    return this.get('/reports/dashboard');
  }
}

export const apiService = new ApiService();