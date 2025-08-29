<?php

return [
    'widgets' => [
        // Top row: Quick Actions | My Schedule | Patient Check-in
        'my_schedule' => [
            'component' => 'widgets.my-schedule',
            'default_layout' => ['x' => 3, 'y' => 0, 'w' => 6, 'h' => 4],
        ],
        'clinic_calendar' => [
            'component' => 'widgets.clinic-calendar',
            'default_layout' => ['x' => 3, 'y' => 7, 'w' => 6, 'h' => 5],
        ],
        // Third row (start at y=7): Quick Search | Clinic Calendar | Pending Treatment Plans
        'quick_search' => [
            'component' => 'widgets.quick-search',
            'default_layout' => ['x' => 0, 'y' => 7, 'w' => 3, 'h' => 2],
        ],
        // Second row (start at y=4): Appointment Statistics | Financial Summary | Pending Invoices
        'financial_summary' => [
            'component' => 'widgets.financial-summary',
            'default_layout' => ['x' => 4, 'y' => 4, 'w' => 4, 'h' => 3],
        ],
        'appointment_statistics' => [
            'component' => 'widgets.appointment-statistics',
            'default_layout' => ['x' => 0, 'y' => 4, 'w' => 4, 'h' => 3],
        ],
        // Bottom row (start at y=12): Inventory Alerts | New Patients Report | User Activity
        'new_patients_report' => [
            'component' => 'widgets.new-patients-report',
            'default_layout' => ['x' => 4, 'y' => 12, 'w' => 4, 'h' => 2],
        ],
        'inventory_alerts' => [
            'component' => 'widgets.inventory-alerts',
            'default_layout' => ['x' => 0, 'y' => 12, 'w' => 4, 'h' => 2],
        ],
        'user_activity' => [
            'component' => 'widgets.user-activity',
            'default_layout' => ['x' => 8, 'y' => 12, 'w' => 4, 'h' => 2],
        ],
        'patient_check_in' => [
            'component' => 'widgets.patient-check-in',
            'default_layout' => ['x' => 9, 'y' => 0, 'w' => 3, 'h' => 2],
        ],
        'pending_invoices' => [
            'component' => 'widgets.pending-invoices',
            'default_layout' => ['x' => 8, 'y' => 4, 'w' => 4, 'h' => 3],
        ],
        'pending_treatment_plans' => [
            'component' => 'widgets.pending-treatment-plans',
            'default_layout' => ['x' => 9, 'y' => 7, 'w' => 3, 'h' => 3],
        ],
        'quick_actions' => [
            'component' => 'widgets.quick-actions',
            'default_layout' => ['x' => 0, 'y' => 0, 'w' => 3, 'h' => 2],
        ],
    ],

    'kpis' => [
        'todays_appointments' => [
            'title' => "Today's Appointments",
            'icon' => 'fa-calendar-day',
        ],
        'active_patients' => [
            'title' => 'Active Patients',
            'icon' => 'fa-users',
        ],
        'daily_revenue' => [
            'title' => 'Daily Revenue',
            'icon' => 'fa-dollar-sign',
        ],
        'pending_payments' => [
            'title' => 'Pending Payments',
            'icon' => 'fa-file-invoice-dollar',
        ],
        'chair_utilization' => [
            'title' => 'Chair Utilization',
            'icon' => 'fa-chair',
        ],
    ],
];