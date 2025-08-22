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
            ->submenu(
                Link::to('#', 'Patients')->addParentClass('flex items-center')->prepend('<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-4.663M12 12a4.5 4.5 0 014.5 4.5m-9 0a4.5 4.5 0 014.5-4.5m0-9a4.5 4.5 0 014.5 4.5m-9 0a4.5 4.5 0 014.5-4.5" /></svg>'),
                Menu::new()
                    ->add(Link::to(route('patients.index'), 'View All'))
                    ->add(Link::to(route('patients.create'), 'Add Patient'))
            )
            ->submenuIf(auth()->check() && auth()->user()->hasRole(['dentist', 'administrator']),
                Link::to('#', 'Treatments')->addParentClass('flex items-center')->prepend('<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.08.828.23 1.216M15.9 5.337A48.36 48.36 0 0012 5.25c-2.651 0-5.198.468-7.5 1.372M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.08.828.23 1.216M15.9 5.337A48.36 48.36 0 0012 5.25c-2.651 0-5.198.468-7.5 1.372" /></svg>'),
                Menu::new()
                    ->add(Link::to(route('treatment-plans.index'), 'Treatment Plans'))
                    ->addIf(auth()->check() && auth()->user()->hasRole('administrator'), Link::to(route('procedures.index'), 'Procedures'))
            )
            ->submenu(
                Link::to('#', 'Appointments')->addParentClass('flex items-center')->prepend('<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0h18M12 14.25h.008v.008H12v-.008z" /></svg>'),
                Menu::new()
                    ->add(Link::to(route('appointments.index'), 'All Appointments'))
                    ->add(Link::to(route('appointments.tentative'), 'Tentative'))
                    ->add(Link::to(route('appointments.calendar'), 'Calendar'))
                    ->add(Link::to(route('appointments.create'), 'Add Appointment'))
            )
            ->submenuIf(auth()->check() && auth()->user()->hasRole('administrator'),
                Link::to('#', 'Stock Management')->addParentClass('flex items-center')->prepend('<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 15.353 16.556 17.25 12 17.25s-8.25-1.897-8.25-4.125V10.125" /></svg>'),
                Menu::new()
                    ->add(Link::to(route('inventory.index'), 'Inventory'))
                    ->add(Link::to(route('suppliers.index'), 'Suppliers'))
                    ->add(Link::to(route('purchase-orders.index'), 'Purchase Orders'))
                    ->add(Link::to(route('stock-movements.index'), 'Stock Movements'))
            )
            ->submenuIf(auth()->check() && auth()->user()->hasRole('administrator'),
                Link::to('#', 'Admin')->addParentClass('flex items-center')->prepend('<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-1.007 1.11-1.226m-2.22 2.452a11.95 11.95 0 00-3.832 4.122m5.051-4.336a11.95 11.95 0 013.832 4.122m-5.051-4.336c.362-1.03.813-1.99 1.364-2.882m-1.364 2.882A11.95 11.95 0 007.5 9.25m3.832 4.122a11.95 11.95 0 01-5.051 4.336m5.051-4.336a11.95 11.95 0 013.832-4.122m3.832 4.122a11.95 11.95 0 00-5.051 4.336m5.051-4.336c-.362 1.03-.813 1.99-1.364 2.882m1.364-2.882a11.95 11.95 0 015.051-4.336m-5.051 4.336a11.95 11.95 0 003.832-4.122" /></svg>'),
                Menu::new()
                    ->add(Link::to(route('users.index'), 'Users'))
                    ->add(Link::to(route('roles.index'), 'Roles'))
                    ->add(Link::to(route('permissions.index'), 'Permissions'))
                    ->add(Link::to(route('smtp.index'), 'SMTP Settings'))
                    ->add(Link::to(route('email-templates.index'), 'Email Templates'))
                    ->add(Link::to(route('ops-settings.index'), 'Operational Settings'))
            );
    }
}
