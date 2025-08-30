<?php

namespace App\Menus;

class AdminMenu
{
    public static function build(): array
    {
        return [
            [
                'title' => 'Dashboard',
                'url' => route('dashboard'),
                'icon' => '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l9.75-9.75L21.75 12M4.5 9.75V21h15V9.75"/></svg>',
                'active' => request()->routeIs('dashboard'),
                'children' => [],
            ],
            [
                'title' => 'Patients',
                'icon' => '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>',
                'active' => request()->routeIs('patients.*'),
                'children' => [
                    ['title' => 'View All', 'url' => route('patients.index'), 'active' => request()->routeIs('patients.index')],
                    ['title' => 'Add Patient', 'url' => route('patients.create'), 'active' => request()->routeIs('patients.create')],
                ],
            ],
            [
                'title' => 'Treatments',
                'icon' => '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h6m5 12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h7l5 5v11z"/></svg>',
                'active' => request()->routeIs('treatment-plans.*') || request()->routeIs('procedures.*'),
                'children' => [
                    ['title' => 'Treatment Plans', 'url' => route('treatment-plans.index'), 'active' => request()->routeIs('treatment-plans.index')],
                    ['title' => 'Procedures', 'url' => route('procedures.index'), 'active' => request()->routeIs('procedures.index'), 'can' => 'administrator'],
                ],
                'can' => ['dentist', 'administrator'],
            ],
            [
                'title' => 'Appointments',
                'icon' => '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 8.25h18M4.5 7.5A2.25 2.25 0 016.75 5.25h10.5A2.25 2.25 0 0119.5 7.5v10.5A2.25 2.25 0 0117.25 20.25H6.75A2.25 2.25 0 014.5 18V7.5z"/></svg>',
                'active' => request()->routeIs('appointments.*'),
                'children' => [
                    ['title' => 'All Appointments', 'url' => route('appointments.index'), 'active' => request()->routeIs('appointments.index')],
                    ['title' => 'Tentative', 'url' => route('appointments.tentative'), 'active' => request()->routeIs('appointments.tentative')],
                    ['title' => 'Calendar', 'url' => route('appointments.calendar'), 'active' => request()->routeIs('appointments.calendar')],
                    ['title' => 'Add Appointment', 'url' => route('appointments.create'), 'active' => request()->routeIs('appointments.create')],
                ],
            ],
            [
                'title' => 'Stock Management',
                'icon' => '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-4.5-9 4.5m18 0v9l-9 4.5m9-13.5L12 12m0 9V12m0 9l-9-4.5v-9M12 12L3 7.5"/></svg>',
                'active' => request()->routeIs('inventory.*') || request()->routeIs('suppliers.*') || request()->routeIs('purchase-orders.*') || request()->routeIs('stock-movements.*'),
                'children' => [
                    ['title' => 'Inventory', 'url' => route('inventory.index'), 'active' => request()->routeIs('inventory.index')],
                    ['title' => 'Suppliers', 'url' => route('suppliers.index'), 'active' => request()->routeIs('suppliers.index')],
                    ['title' => 'Purchase Orders', 'url' => route('purchase-orders.index'), 'active' => request()->routeIs('purchase-orders.index')],
                    ['title' => 'Stock Movements', 'url' => route('stock-movements.index'), 'active' => request()->routeIs('stock-movements.index')],
                ],
                'can' => 'administrator',
            ],
            [
                'title' => 'Admin',
                'icon' => '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94a.75.75 0 011.06 0l.53.53a.75.75 0 00.53.22h1.572a.75.75 0 01.707.518l.24.74c.061.187.2.34.38.42l.7.311a.75.75 0 01.4.964l-.29.773a.75.75 0 00.17.79l.53.53a.75.75 0 010 1.06l-.53.53a.75.75 0 00-.17.79l.29.773a.75.75 0 01-.4.964l-.7.311a.75.75 0 00-.38.42l-.24.74a.75.75 0 01-.707.518H11.714a.75.75 0 00-.53.22l-.53.53a.75.75 0 01-1.06 0l-.53-.53a.75.75 0 00-.53-.22H6.962a.75.75 0 01-.707-.518l-.24-.74a.75.75 0 00-.38-.42l-.7-.311a.75.75 0 01-.4-.964l.29-.773a.75.75 0 00-.17-.79l-.53-.53a.75.75 0 010-1.06l.53-.53a.75.75 0 00.17-.79l-.29-.773a.75.75 0 01.4-.964l.7-.311a.75.75 0 00.38-.42l.24-.74A.75.75 0 016.962 5.19h1.572a.75.75 0 00.53-.22l.53-.53zM12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z"/></svg>',
                'active' => request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('smtp.*') || request()->routeIs('email-templates.*') || request()->routeIs('ops-settings.*') || request()->routeIs('logs.*') || request()->routeIs('audit-logs.*') || request()->routeIs('admin.dashboard-widgets.*') || request()->routeIs('admin.quick-actions.*'),
                'children' => [
                    [
                        'title' => 'User Management',
                        'active' => request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*'),
                        'children' => [
                            ['title' => 'Users', 'url' => route('users.index'), 'active' => request()->routeIs('users.index')],
                            ['title' => 'Roles', 'url' => route('roles.index'), 'active' => request()->routeIs('roles.index')],
                            ['title' => 'Permissions', 'url' => route('permissions.index'), 'active' => request()->routeIs('permissions.index')],
                        ]
                    ],
                    [
                        'title' => 'System Configuration',
                        'active' => request()->routeIs('smtp.*') || request()->routeIs('email-templates.*') || request()->routeIs('ops-settings.*'),
                        'children' => [
                            ['title' => 'SMTP Settings', 'url' => route('smtp.index'), 'active' => request()->routeIs('smtp.index')],
                            ['title' => 'Email Templates', 'url' => route('email-templates.index'), 'active' => request()->routeIs('email-templates.index')],
                            ['title' => 'Operational Settings', 'url' => route('ops-settings.index'), 'active' => request()->routeIs('ops-settings.index')],
                        ]
                    ],
                    [
                        'title' => 'Dashboard Management',
                        'active' => request()->routeIs('admin.dashboard-widgets.*') || request()->routeIs('admin.quick-actions.*'),
                        'children' => [
                            ['title' => 'Dashboard Widgets', 'url' => route('admin.dashboard-widgets.edit'), 'active' => request()->routeIs('admin.dashboard-widgets.edit')],
                            ['title' => 'Quick Actions', 'url' => route('admin.quick-actions.edit'), 'active' => request()->routeIs('admin.quick-actions.*')],
                        ]
                    ],
                    [
                        'title' => 'System Monitoring',
                        'active' => request()->routeIs('logs.*') || request()->routeIs('audit-logs.*'),
                        'children' => [
                            ['title' => 'System Logs', 'url' => route('logs.index'), 'active' => request()->routeIs('logs.index')],
                            ['title' => 'Audit Logs', 'url' => route('audit-logs.index'), 'active' => request()->routeIs('audit-logs.index')],
                        ]
                    ],
                ],
                'can' => 'administrator',
            ],
        ];
    }
}
