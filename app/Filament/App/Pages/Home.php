<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\View\View;

class Home extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view            = 'filament.app.pages.home';
}
