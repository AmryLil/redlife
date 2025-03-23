<?php

namespace App\Filament\Pages;

use App\Models\DonationLocation;
use App\Models\Donations;
use App\Models\Donor;
use Carbon\Carbon;
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
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class DonorForm extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view  = 'filament.app.pages.donor-form';
    public array $data             = [];
    public int $currentStep        = 1;
    public bool $alreadyRegistered = false;
    public $donorData;

    public function mount(): void
    {
        // Cek apakah user sudah terdaftar
        $this->donorData         = Donations::where('user_id', Auth::id())->first();
        $this->alreadyRegistered = (bool) $this->donorData;

        if (!$this->alreadyRegistered) {
            $this->form->fill();
        }
    }

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
                                    TextInput::make('nama_lengkap')
                                        ->label('Nama Lengkap')
                                        ->required(),
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
                                        ->required()
                                        ->reactive(),
                                    Select::make('lokasi_pengguna')
                                        ->label('Pilih Kota atau Wilayah')
                                        ->options(DonationLocation::all()->pluck('city', 'city')->unique())
                                        ->required()
                                        ->reactive(),
                                    Select::make('lokasi_donor')
                                        ->label('Tempat Donor Darah Terdekat')
                                        ->options(function (callable $get) {
                                            $kota = $get('lokasi_pengguna');
                                            return DonationLocation::where('city', $kota)
                                                ->pluck('location_name', 'id');
                                        })
                                        ->required()
                                        ->reactive(),
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
                                        ->dehydrated()
                                        ->default(function ($get) {
                                            return $get('nama_lengkap');
                                        }),
                                    TextInput::make('summary_tanggal')
                                        ->label('Tanggal Donor')
                                        ->disabled()
                                        ->dehydrated()
                                        ->default(function ($get) {
                                            return Carbon::parse($get('tanggal_donor'))->translatedFormat('d F Y');
                                        }),
                                    TextInput::make('summary_lokasi')
                                        ->label('Lokasi Donor')
                                        ->disabled()
                                        ->dehydrated()
                                        ->default(function ($get) {
                                            $location = DonationLocation::find($get('lokasi_donor'));
                                            return $location ? "{$location->nama_lokasi} - {$location->alamat}" : '';
                                        }),
                                    TextInput::make('waktu_donor')
                                        ->label('Waktu Donor')
                                        ->disabled()
                                        ->dehydrated()
                                        ->default(function ($get) {
                                            return $get('waktu_donor');
                                        }),
                                ]),
                        ]),
                ])
            ])
            ->statePath('data');
    }

    public function nextStep()
    {
        if ($this->alreadyRegistered)
            return;

        // Validasi step 1
        if ($this->currentStep === 1) {
            $this->validate([
                'data.nama_lengkap' => 'required|string|max:255',
                'data.usia'         => 'accepted',
                'data.berat_badan'  => 'accepted',
                'data.persetujuan'  => 'accepted',
            ]);
        }

        // Validasi step 2
        if ($this->currentStep === 2) {
            $this->validate([
                'data.tanggal_donor' => 'required|date|after:today',
                'data.lokasi_donor'  => 'required|exists:donation_locations,id',
                'data.waktu_donor'   => 'required',
            ]);
        }

        if ($this->currentStep < 3) {
            $this->currentStep++;
        }
    }

    public function submit()
    {
        if ($this->alreadyRegistered)
            return;

        $this->validate([
            'data.tanggal_donor' => 'required|date|after:today',
            'data.lokasi_donor'  => 'required|exists:donation_locations,id',
            'data.waktu_donor'   => 'required',
        ]);

        // Simpan data
        $donor = Donations::create([
            'user_id'       => Auth::id(),
            'donation_date' => $this->data['tanggal_donor'],
            'location_id'   => $this->data['lokasi_donor'],
            'time'          => $this->data['waktu_donor'],
            'status_id'     => 1,
        ]);

        // Update data dan status
        $this->donorData         = $donor;
        $this->alreadyRegistered = true;
        $this->form->fill();

        Notification::make()
            ->title('Pendaftaran Berhasil!')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        if ($this->alreadyRegistered)
            return [];

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
