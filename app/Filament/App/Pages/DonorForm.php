<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Livewire\Attributes\Validate;

class DonorForm extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static string $view            = 'filament.app.pages.donor-form';
    public array $data                       = [];
    public int $currentStep                  = 1;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('1. Skrining Kesehatan')
                        ->schema([
                            Section::make('Persyaratan Donor')
                                ->description('Pastikan Anda memenuhi semua kriteria')
                                ->schema([
                                    Checkbox::make('usia')
                                        ->label('Usia 17-65 tahun')
                                        ->required()
                                        ->validationMessages([
                                            'required' => 'Anda harus memenuhi syarat usia',
                                        ]),
                                    Checkbox::make('berat_badan')
                                        ->label('Berat badan minimal 45 kg')
                                        ->required(),
                                    Checkbox::make('sehat')
                                        ->label('Tidak sedang sakit atau mengonsumsi obat'),
                                    Checkbox::make('persetujuan')
                                        ->label('Menyetujui syarat dan ketentuan donor darah')
                                        ->required(),
                                ]),
                        ]),
                    Wizard\Step::make('2. Jadwal Donor')
                        ->schema([
                            Section::make('Pilih Jadwal')
                                ->schema([
                                    DatePicker::make('tanggal_donor')
                                        ->label('Tanggal Donor')
                                        ->minDate(now()->addDay())
                                        ->required(),
                                    Select::make('lokasi_donor')
                                        ->options([
                                            'PMI Jakarta Pusat'  => 'PMI Jakarta Pusat',
                                            'RS Siloam'          => 'RS Siloam',
                                            'UTD Kota Tangerang' => 'UTD Kota Tangerang',
                                        ])
                                        ->required(),
                                    Select::make('waktu_donor')
                                        ->options([
                                            '08:00' => '08:00 - 09:00',
                                            '09:00' => '09:00 - 10:00',
                                            '10:00' => '10:00 - 11:00',
                                        ])
                                        ->required(),
                                ]),
                        ]),
                    Wizard\Step::make('3. Konfirmasi')
                        ->schema([
                            Section::make('Ringkasan Pendaftaran')
                                ->schema([
                                    TextInput::make('nama_lengkap')
                                        ->disabled()
                                        ->dehydrated(),
                                    TextInput::make('summary_tanggal')
                                        ->label('Tanggal Donor')
                                        ->disabled(),
                                    TextInput::make('summary_lokasi')
                                        ->label('Lokasi Donor')
                                        ->disabled(),
                                ]),
                        ]),
                ])
            ])
            ->statePath('data');
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'data.usia'        => 'accepted',
                'data.berat_badan' => 'accepted',
                'data.persetujuan' => 'accepted',
            ]);
        }

        if ($this->currentStep === 2) {
            $this->validate([
                'data.tanggal_donor' => 'required|date',
                'data.lokasi_donor'  => 'required',
                'data.waktu_donor'   => 'required',
            ]);
        }

        if ($this->currentStep < 3) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submit()
    {
        // Simpan data ke database
        \App\Models\Donor::create([
            'nama'    => $this->data['nama_lengkap'],
            'tanggal' => $this->data['tanggal_donor'],
            'lokasi'  => $this->data['lokasi_donor'],
            'waktu'   => $this->data['waktu_donor'],
        ]);

        $this->form->fill();
        $this->currentStep = 1;
        $this->notify('success', 'Pendaftaran donor berhasil!');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('previous')
                ->label('Kembali')
                ->visible($this->currentStep > 1)
                ->action('previousStep'),
            Action::make('next')
                ->label('Lanjut')
                ->visible($this->currentStep < 3)
                ->action('nextStep'),
            Action::make('submit')
                ->label('Daftar Sekarang')
                ->visible($this->currentStep === 3)
                ->action('submit'),
        ];
    }
}
