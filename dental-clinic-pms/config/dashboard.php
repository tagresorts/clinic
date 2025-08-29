<?php

return [
    'widgets' => [
        'my_schedule' => [
            'component' => 'widgets.my-schedule',
            'default_layout' => ['x' => 0, 'y' => 0, 'w' => 7, 'h' => 4],
        ],
        'clinic_calendar' => [
            'component' => 'widgets.clinic-calendar',
            'default_layout' => ['x' => 7, 'y' => 0, 'w' => 5, 'h' => 4],
        ],
        'quick_search' => [
            'component' => 'widgets.quick-search',
            'default_layout' => ['x' => 8, 'y' => 4, 'w' => 4, 'h' => 1],
        ],
        'financial_summary' => [
            'component' => 'widgets.financial-summary',
            'default_layout' => ['x' => 0, 'y' => 4, 'w' => 8, 'h' => 1],
        ],
        'appointment_statistics' => [
            'component' => 'widgets.appointment-statistics',
            'default_layout' => ['x' => 0, 'y' => 5, 'w' => 6, 'h' => 3],
        ],
        'new_patients_report' => [
            'component' => 'widgets.new-patients-report',
            'default_layout' => ['x' => 6, 'y' => 5, 'w' => 6, 'h' => 3],
        ],
        'inventory_alerts' => [
            'component' => 'widgets.inventory-alerts',
            'default_layout' => ['x' => 8, 'y' => 8, 'w' => 4, 'h' => 3],
        ],
        'user_activity' => [
            'component' => 'widgets.user-activity',
            'default_layout' => ['x' => 0, 'y' => 8, 'w' => 8, 'h' => 3],
        ],
        'patient_check_in' => [
            'component' => 'widgets.patient-check-in',
            'default_layout' => ['x' => 8, 'y' => 11, 'w' => 4, 'h' => 2],
        ],
        'pending_invoices' => [
            'component' => 'widgets.pending-invoices',
            'default_layout' => ['x' => 0, 'y' => 11, 'w' => 4, 'h' => 2],
        ],
        'pending_treatment_plans' => [
            'component' => 'widgets.pending-treatment-plans',
            'default_layout' => ['x' => 4, 'y' => 11, 'w' => 4, 'h' => 2],
        ],
        'quick_actions' => [
            'component' => 'widgets.quick-actions',
            'default_layout' => ['x' => 8, 'y' => 12, 'w' => 4, 'h' => 2],
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