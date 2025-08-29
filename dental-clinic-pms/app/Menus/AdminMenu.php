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
                'icon' => '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.5 1.5 0 012.122 0l8.954 8.955M11.25 3.75v16.5M3.75 21.75h16.5" /></svg>',
                'active' => request()->routeIs('dashboard'),
                'children' => [],
            ],
            [
                'title' => 'Patients',
                'icon' => '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-4.663M12 12a4.5 4.5 0 014.5 4.5m-9 0a4.5 4.5 0 014.5-4.5m0-9a4.5 4.5 0 014.5 4.5m-9 0a4.5 4.5 0 014.5-4.5" /></svg>',
                'active' => request()->routeIs('patients.*'),
                'children' => [
                    ['title' => 'View All', 'url' => route('patients.index'), 'active' => request()->routeIs('patients.index')],
                    ['title' => 'Add Patient', 'url' => route('patients.create'), 'active' => request()->routeIs('patients.create')],
                ],
            ],
            [
                'title' => 'Treatments',
                'icon' => '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.08.828.23 1.216M15.9 5.337A48.36 48.36 0 0012 5.25c-2.651 0-5.198.468-7.5 1.372M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.08.828.23 1.216M15.9 5.337A48.36 48.36 0 0012 5.25c-2.651 0-5.198.468-7.5 1.372" /></svg>',
                'active' => request()->routeIs('treatment-plans.*') || request()->routeIs('procedures.*'),
                'children' => [
                    ['title' => 'Treatment Plans', 'url' => route('treatment-plans.index'), 'active' => request()->routeIs('treatment-plans.index')],
                    ['title' => 'Procedures', 'url' => route('procedures.index'), 'active' => request()->routeIs('procedures.index'), 'can' => 'administrator'],
                ],
                'can' => ['dentist', 'administrator'],
            ],
            [
                'title' => 'Appointments',
                'icon' => '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0h18M12 14.25h.008v.008H12v-.008z" /></svg>',
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
                'icon' => '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 15.353 16.556 17.25 12 17.25s-8.25-1.897-8.25-4.125V10.125" /></svg>',
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
                'icon' => '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-1.007 1.11-1.226m-2.22 2.452a11.95 11.95 0 00-3.832 4.122m5.051-4.336a11.95 11.95 0 013.832 4.122m-5.051-4.336c.362-1.03.813-1.99 1.364-2.882m-1.364 2.882A11.95 11.95 0 007.5 9.25m3.832 4.122a11.95 11.95 0 01-5.051 4.336m5.051-4.336a11.95 11.95 0 013.832-4.122m3.832 4.122a11.95 11.95 0 00-5.051 4.336m5.051-4.336c-.362 1.03-.813 1.99-1.364 2.882m1.364-2.882a11.95 11.95 0 015.051-4.336m-5.051 4.336a11.95 11.95 0 003.832-4.122" /></svg>',
                'active' => request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('smtp.*') || request()->routeIs('email-templates.*') || request()->routeIs('ops-settings.*') || request()->routeIs('logs.*') || request()->routeIs('audit-logs.*'),
                'children' => [
                    ['title' => 'Users', 'url' => route('users.index'), 'active' => request()->routeIs('users.index')],
                    ['title' => 'Roles', 'url' => route('roles.index'), 'active' => request()->routeIs('roles.index')],
                    ['title' => 'Permissions', 'url' => route('permissions.index'), 'active' => request()->routeIs('permissions.index')],
                    ['title' => 'SMTP Settings', 'url' => route('smtp.index'), 'active' => request()->routeIs('smtp.index')],
                    ['title' => 'Email Templates', 'url' => route('email-templates.index'), 'active' => request()->routeIs('email-templates.index')],
                    ['title' => 'Operational Settings', 'url' => route('ops-settings.index'), 'active' => request()->routeIs('ops-settings.index')],
                    ['title' => 'Dashboard Widgets', 'url' => route('admin.dashboard-widgets.edit'), 'active' => request()->routeIs('admin.dashboard-widgets.edit')],
                    ['title' => 'System Logs', 'url' => route('logs.index'), 'active' => request()->routeIs('logs.index')],
                    ['title' => 'Audit Logs', 'url' => route('audit-logs.index'), 'active' => request()->routeIs('audit-logs.index')],
                ],
                'can' => 'administrator',
            ],
        ];
    }
}
