<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.dashboard';

    protected static ?string $title = 'Dashboard';

    public function getViewData(): array
    {
        return [
            'totalUsers' => 0,
            'totalProducts' => 0,
            'totalOrders' => 0,
        ];
    }
}
