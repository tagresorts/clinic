<?php

return [
    'widgets' => [
        'my_schedule' => [
            'component' => 'widgets.my-schedule',
            'permission' => 'dashboard.widget.view_my_schedule',
            'default_layout' => ['x' => 0, 'y' => 0, 'w' => 1, 'h' => 4],
        ],
        'clinic_calendar' => [
            'component' => 'widgets.clinic-calendar',
            'permission' => 'dashboard.widget.view_clinic_calendar',
            'default_layout' => ['x' => 1, 'y' => 0, 'w' => 1, 'h' => 4],
        ],
        'financial_summary' => [
            'component' => 'widgets.financial-summary',
            'permission' => 'dashboard.widget.view_financial_summary',
            'default_layout' => ['x' => 0, 'y' => 4, 'w' => 2, 'h' => 1],
        ],
        'appointment_statistics' => [
            'component' => 'widgets.appointment-statistics',
            'permission' => 'dashboard.widget.view_appointment_statistics',
            'default_layout' => ['x' => 0, 'y' => 5, 'w' => 1, 'h' => 3],
        ],
        'new_patients_report' => [
            'component' => 'widgets.new-patients-report',
            'permission' => 'dashboard.widget.view_new_patients_report',
            'default_layout' => ['x' => 1, 'y' => 5, 'w' => 1, 'h' => 3],
        ],
        'user_activity' => [
            'component' => 'widgets.user-activity',
            'permission' => 'dashboard.widget.view_user_activity',
            'default_layout' => ['x' => 0, 'y' => 8, 'w' => 2, 'h' => 3],
        ],
        'pending_invoices' => [
            'component' => 'widgets.pending-invoices',
            'permission' => 'dashboard.widget.view_pending_invoices',
            'default_layout' => ['x' => 0, 'y' => 11, 'w' => 1, 'h' => 2],
        ],
        'pending_treatment_plans' => [
            'component' => 'widgets.pending-treatment-plans',
            'permission' => 'dashboard.widget.view_pending_treatment_plans',
            'default_layout' => ['x' => 1, 'y' => 11, 'w' => 1, 'h' => 2],
        ],
        'inventory_alerts' => [
            'component' => 'widgets.inventory-alerts',
            'permission' => 'dashboard.widget.view_inventory_alerts',
            'default_layout' => ['x' => 0, 'y' => 13, 'w' => 1, 'h' => 3],
        ],
        'patient_check_in' => [
            'component' => 'widgets.patient-check-in',
            'permission' => 'dashboard.widget.view_patient_check_in',
            'default_layout' => ['x' => 1, 'y' => 13, 'w' => 1, 'h' => 2],
        ],
        'quick_search' => [
            'component' => 'widgets.quick-search',
            'permission' => 'dashboard.widget.view_quick_search',
            'default_layout' => ['x' => 1, 'y' => 15, 'w' => 1, 'h' => 1],
        ],
    ],
];