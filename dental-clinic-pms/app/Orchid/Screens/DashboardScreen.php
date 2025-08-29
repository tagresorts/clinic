<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Orchid\Dashboard;

class DashboardScreen extends Screen
{
    /**
     * The screen\'s layout elements.
     *
     * @return \\Orchid\\Screen\\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Dashboard::class,
        ];
    }
}
