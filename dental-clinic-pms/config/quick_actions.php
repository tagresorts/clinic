<?php

return [
    'actions' => [
        // Core creation flows
        [ 'id' => 'patient_create', 'label' => 'Add Patient', 'route' => 'patients.create', 'enabled' => true ],
        [ 'id' => 'appointment_create', 'label' => 'Add Appointment', 'route' => 'appointments.create', 'enabled' => true ],
        [ 'id' => 'treatment_plan_create', 'label' => 'Add Treatment Plan', 'route' => 'treatment-plans.create', 'enabled' => true ],

        // Operations & utilities
        [ 'id' => 'calendar', 'label' => 'Calendar', 'route' => 'appointments.calendar', 'enabled' => true ],
        [ 'id' => 'patients_index', 'label' => 'Patients', 'route' => 'patients.index', 'enabled' => true ],

        // Admin / settings (enabled off by default)
        [ 'id' => 'operational_settings', 'label' => 'Operational Settings', 'route' => 'ops-settings.index', 'enabled' => false ],
        [ 'id' => 'inventory', 'label' => 'Inventory', 'route' => 'inventory.index', 'enabled' => false ],
        [ 'id' => 'suppliers', 'label' => 'Suppliers', 'route' => 'suppliers.index', 'enabled' => false ],
        [ 'id' => 'purchase_orders', 'label' => 'Purchase Orders', 'route' => 'purchase-orders.index', 'enabled' => false ],
        [ 'id' => 'users', 'label' => 'Users', 'route' => 'users.index', 'enabled' => false ],
        [ 'id' => 'roles', 'label' => 'Roles', 'route' => 'roles.index', 'enabled' => false ],
        [ 'id' => 'permissions', 'label' => 'Permissions', 'route' => 'permissions.index', 'enabled' => false ],
    ],
];

