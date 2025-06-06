<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class SearchLocation extends Page
{
    protected static ?string $navigationIcon        = 'heroicon-o-document-text';
    protected static string $view                   = 'filament.app.pages.search-location';
    protected static bool $shouldRegisterNavigation = false;
}
