<?php

namespace App\Menus;

use Spatie\Menu\Laravel\Menu;
use Spatie\Menu\Link;

class AdminMenu
{
    public static function build(): Menu
    {
        return Menu::new()
            ->add(Link::toRoute('dashboard', 'Dashboard'))
            ->submenu(
                Link::to('#', 'Patients'),
                Menu::new()
                    ->add(Link::toRoute('patients.index', 'View All'))
                    ->add(Link::toRoute('patients.create', 'Add Patient'))
            )
            ->submenuIf(auth()->check() && auth()->user()->hasRole(['dentist', 'administrator']),
                Link::to('#', 'Treatments'),
                Menu::new()
                    ->add(Link::toRoute('treatment-plans.index', 'Treatment Plans'))
                    ->addIf(auth()->check() && auth()->user()->hasRole('administrator'), Link::toRoute('procedures.index', 'Procedures'))
            )
            ->submenu(
                Link::to('#', 'Appointments'),
                Menu::new()
                    ->add(Link::toRoute('appointments.index', 'All Appointments'))
                    ->add(Link::toRoute('appointments.calendar', 'Calendar'))
                    ->add(Link::toRoute('appointments.create', 'Add Appointment'))
            )
            ->submenuIf(auth()->check() && auth()->user()->hasRole('administrator'),
                Link::to('#', 'Admin'),
                Menu::new()
                    ->submenu(
                        Link::to('#', 'User Management'),
                        Menu::new()
                            ->add(Link::toRoute('users.index', 'Users'))
                            ->add(Link::toRoute('roles.index', 'Roles'))
                            ->add(Link::toRoute('permissions.index', 'Permissions'))
                    )
                    // Add other admin modules here
            );
    }
}
