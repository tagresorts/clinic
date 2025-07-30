import { prisma } from '../index';
import { AppointmentStatus } from '@prisma/client';

export const appointmentReminderService = {
  async sendDailyReminders() {
    try {
      // Get appointments for tomorrow
      const tomorrow = new Date();
      tomorrow.setDate(tomorrow.getDate() + 1);
      tomorrow.setHours(0, 0, 0, 0);
      
      const endOfTomorrow = new Date(tomorrow);
      endOfTomorrow.setHours(23, 59, 59, 999);

      const appointments = await prisma.appointment.findMany({
        where: {
          dateTime: {
            gte: tomorrow,
            lte: endOfTomorrow,
          },
          status: {
            in: [AppointmentStatus.SCHEDULED, AppointmentStatus.CONFIRMED],
          },
        },
        include: {
          patient: {
            select: {
              firstName: true,
              lastName: true,
              phone: true,
              email: true,
            },
          },
          dentist: {
            select: {
              firstName: true,
              lastName: true,
            },
          },
        },
      });

      console.log(`Found ${appointments.length} appointments for tomorrow requiring reminders`);

      // In a real implementation, you would send SMS/email here
      // For now, we'll just log the reminders
      for (const appointment of appointments) {
        console.log(`Reminder: ${appointment.patient.firstName} ${appointment.patient.lastName} has an appointment tomorrow at ${appointment.dateTime.toLocaleTimeString()} with Dr. ${appointment.dentist.firstName} ${appointment.dentist.lastName}`);
      }

      return appointments.length;
    } catch (error) {
      console.error('Error sending appointment reminders:', error);
      throw error;
    }
  },
};