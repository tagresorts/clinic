<?php

namespace App\Menus;

use Spatie\Menu\Menu;
use Spatie\Menu\Link;

class AdminMenu
{
    public static function build(): Menu
    {
        return Menu::new()
            ->add(Link::to(route('dashboard'), 'Dashboard'))
            ->submenu(
                Link::to('#', 'Patients'),
                Menu::new()
                    ->add(Link::to(route('patients.index'), 'View All'))
                    ->add(Link::to(route('patients.create'), 'Add Patient'))
            )
            ->submenuIf(auth()->check() && auth()->user()->hasRole(['dentist', 'administrator']),
                Link::to('#', 'Treatments'),
                Menu::new()
                    ->add(Link::to(route('treatment-plans.index'), 'Treatment Plans'))
                    ->addIf(auth()->check() && auth()->user()->hasRole('administrator'), Link::to(route('procedures.index'), 'Procedures'))
            )
            ->submenu(
                Link::to('#', 'Appointments'),
                Menu::new()
                    ->add(Link::to(route('appointments.index'), 'All Appointments'))
                    ->add(Link::to(route('appointments.calendar'), 'Calendar'))
                    ->add(Link::to(route('appointments.create'), 'Add Appointment'))
            )
            ->submenuIf(auth()->check() && auth()->user()->hasRole('administrator'),
                Link::to('#', 'Admin'),
                Menu::new()
                    ->submenu(
                        Link::to('#', 'User Management'),
                        Menu::new()
                            ->add(Link::to(route('users.index'), 'Users'))
                            ->add(Link::to(route('roles.index'), 'Roles'))
                            ->add(Link::to(route('permissions.index'), 'Permissions'))
                    )
                    // Add other admin modules here
            );
    }
}
