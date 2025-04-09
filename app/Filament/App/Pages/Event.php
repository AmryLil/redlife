<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Event extends Page
{
    protected static string $view = 'filament.app.pages.coming-soon';
    public array $events          = [];

    public function mount()
    {
        $this->events = [
            [
                'nama'    => 'Donor Darah Makassar',
                'tanggal' => '20 Maret 2025',
                'lokasi'  => 'RS Wahidin, Makassar',
                'image'   => 'https://source.unsplash.com/400x300/?blood,donation'
            ],
            [
                'nama'    => 'Aksi Donor di Kampus UNHAS',
                'tanggal' => '25 Maret 2025',
                'lokasi'  => 'UNHAS, Makassar',
                'image'   => 'https://source.unsplash.com/400x300/?hospital'
            ],
            [
                'nama'    => 'Kegiatan Donor di Mall Panakkukang',
                'tanggal' => '30 Maret 2025',
                'lokasi'  => 'Mall Panakkukang, Makassar',
                'image'   => 'https://source.unsplash.com/400x300/?event'
            ],
        ];
    }
}
