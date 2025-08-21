<!DOCTYPE html>
<html>
<head>
    <title>Tentative Appointment Reminder</title>
</head>
<body>
    <h1>Tentative Appointment Reminder</h1>
    <p>Hello Dr. {{ $appointments->first()->dentist->name }},</p>
    <p>You have the following tentative appointments next week:</p>
    <ul>
        @foreach($appointments as $appointment)
            <li>
                <strong>Patient:</strong> {{ $appointment->patient->full_name }}<br>
                <strong>Date:</strong> {{ $appointment->appointment_datetime->format('M d, Y H:i A') }}<br>
                <strong>Reason:</strong> {{ $appointment->reason_for_visit }}
            </li>
        @endforeach
    </ul>
    <p>Please log in to the system to confirm, cancel, or reschedule these appointments.</p>
    <p>Thank you,</p>
    <p>The Clinic Management System</p>
</body>
</html>