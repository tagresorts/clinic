<?php

namespace App\Menus;

use Spatie\Menu\Menu;
use Spatie\Menu\Link;

class AdminMenu
{
    public static function build(): Menu
    {
        return Menu::new()
            ->add(Link::to(route('dashboard'), 'Dashboard')->addParentClass('flex items-center')->prepend('<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.5 1.5 0 012.122 0l8.954 8.955M11.25 3.75v16.5M3.75 21.75h16.5" /></svg>'))
            ->submenu('Patients',
                Menu::new()
                    ->link(route('patients.index'), 'View All')
                    ->link(route('patients.create'), 'Add Patient')
            )
            ->submenuIf(auth()->check() && auth()->user()->hasRole(['dentist', 'administrator']),
                'Treatments',
                Menu::new()
                    ->link(route('treatment-plans.index'), 'Treatment Plans')
                    ->linkIf(auth()->check() && auth()->user()->hasRole('administrator'), route('procedures.index'), 'Procedures')
            )
            ->submenu('Appointments',
                Menu::new()
                    ->link(route('appointments.index'), 'All Appointments')
                    ->link(route('appointments.tentative'), 'Tentative')
                    ->link(route('appointments.calendar'), 'Calendar')
                    ->link(route('appointments.create'), 'Add Appointment')
            )
            ->submenuIf(auth()->check() && auth()->user()->hasRole('administrator'),
                'Stock Management',
                Menu::new()
                    ->link(route('inventory.index'), 'Inventory')
                    ->link(route('suppliers.index'), 'Suppliers')
                    ->link(route('purchase-orders.index'), 'Purchase Orders')
                    ->link(route('stock-movements.index'), 'Stock Movements')
            )
            ->submenuIf(auth()->check() && auth()->user()->hasRole('administrator'),
                'Admin',
                Menu::new()
                    ->link(route('users.index'), 'Users')
                    ->link(route('roles.index'), 'Roles')
                    ->link(route('permissions.index'), 'Permissions')
                    ->link(route('smtp.index'), 'SMTP Settings')
                    ->link(route('email-templates.index'), 'Email Templates')
                    ->link(route('ops-settings.index'), 'Operational Settings')
            )
            ->each(function (Menu $menu) {
                if ($menu->isSubmenu()) {
                    $menu->prepend('<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>')
                        ->addParentClass('flex items-center');
                }
            });
    }
}
