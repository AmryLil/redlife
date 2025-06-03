<?php

namespace App\Filament\App\Pages;

use App\Models\BloodStock;
use App\Models\BloodStockDetail;
use App\Models\DonationLocation;
use App\Models\Donations as DonationsModel;
use App\Models\Donor;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\HtmlString;

class Donations extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view  = 'filament.app.pages.donor-form';
    public array $data             = [];
    public int $currentStep        = 1;
    public bool $alreadyRegistered = false;
    public $donorData;
    public $bloodDetails;
    public array $donationLocations   = [];
    public string $userLocationCoords = '';
    public bool $locationDataLoaded   = false;

    // FIX 1: Tambahkan computed property untuk options

    // FIX 2: Method helper untuk mendapatkan data lokasi

    public function handleLokasiUpdate(...$args)
    {
        Log::info('Event diterima', [
            'args'       => $args,
            'args_count' => count($args)
        ]);

        if (count($args) >= 2) {
            $userCoords = $args[0] ?? '';

            if (!empty($userCoords)) {
                $this->userLocationCoords = $userCoords;
            }
        }
    }

    public function mount(): void
    {
        if (!Auth::check()) {
            redirect()->route('login');
            return;
        }

        $this->loadLocationDataFromSession();
        $this->donorData         = DonationsModel::where('user_id', Auth::id())->first();
        $this->alreadyRegistered = (bool) $this->donorData;

        if ($this->alreadyRegistered) {
            $this->bloodDetails = BloodStock::where('donation_id', $this->donorData->id)->first();
        } else {
            $this->form->fill();
        }
    }

    protected function loadLocationDataFromSession(): void
    {
        if (Session::has('donation_locations')) {
            $this->userLocationCoords = Session::get('user_location_coords', '');

            if (!empty($this->userLocationCoords)) {
                $this->data['lokasi_pengguna'] = $this->userLocationCoords;
            }
        }
    }

    // FIX 4: Pastikan hydrate berjalan dengan benar
    public function hydrate(): void
    {
        $this->loadLocationDataFromSession();
    }

    protected function getListeners()
    {
        return [
            'lokasiDiupdate' => 'handleLokasiUpdate',
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('1. Skrining Kesehatan')
                        ->schema([
                            Section::make('Persyaratan Donor')
                                ->description(function () {
                                    if (!auth()->user()->isProfileComplete()) {
                                        return new HtmlString('
                                        <div class="flex items-center gap-2 text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Data Pribadi Anda belum lengkap, Tolong lengkapi terlebih dahulu!</span>
                                        </div>
                                    ');
                                    }
                                    return 'Silahkan lanjutkan';
                                })
                                ->schema([
                                    TextInput::make('nama_lengkap')
                                        ->label('Nama Lengkap')
                                        ->default(auth()->user()->name)
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
                        ])
                        ->beforeValidation(function () {
                            if (!auth()->user()->isProfileComplete()) {
                                Notification::make()
                                    ->title('Profil Tidak Lengkap')
                                    ->body('Lengkapi data diri di halaman profil')
                                    ->warning()
                                    ->persistent()
                                    ->send();
                                return redirect()->route('filament.app.pages.profile');
                            }
                        }),
                    Wizard\Step::make('2. Jadwal Donor')
                        ->schema([
                            Section::make('Pilih Jadwal')
                                ->schema([
                                    DatePicker::make('tanggal_donor')
                                        ->label('Tanggal Donor')
                                        ->minDate(now()->addDay())
                                        ->required(),
                                    TextInput::make('lokasi_pengguna')
                                        ->label('Lokasi Anda')
                                        ->extraAttributes([
                                            'id' => 'lokasi_pengguna',
                                        ])
                                        ->disabled()
                                        ->default(function () {
                                            return $this->userLocationCoords ?: Session::get('user_location_coords', '');
                                        }),
                                    // FIX 5: Perbaiki Select component
                                    View::make('components.peta-leaflet'),
                                    Select::make('waktu_donor')
                                        ->label('Waktu Donor')
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
                                        ->extraAttributes([
                                            'id' => 'lokasi_terpilih',
                                        ]),
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

    public function updatedDataLokasiDonorId($value)
    {
        if ($value) {
            $locations        = $this->getLocationData();
            $selectedLocation = collect($locations)->firstWhere('place_id', $value);

            if ($selectedLocation) {
                $this->data['selected_location_data'] = json_encode($selectedLocation);

                Log::info('Lokasi dipilih', [
                    'place_id' => $value,
                    'location' => $selectedLocation['display_name'] ?? 'unknown'
                ]);
            }
        }
    }

    // FIX 7: Method untuk debug dan refresh manual
    public function refreshLocations()
    {
        $this->loadLocationDataFromSession();

        Notification::make()
            ->title('Lokasi di-refresh')
            ->body('Locations count: ' . count($this->donationLocations))
            ->info()
            ->send();

        // Force refresh form
        $this->dispatch('$refresh');
    }

    public function nextStep()
    {
        if ($this->alreadyRegistered)
            return;

        if ($this->currentStep === 1) {
            $this->validate([
                'data.nama_lengkap' => 'required|string|max:255',
                'data.usia'         => 'accepted',
                'data.berat_badan'  => 'accepted',
                'data.persetujuan'  => 'accepted',
            ]);
        }

        if ($this->currentStep === 2) {
            $this->validate([
                'data.tanggal_donor'   => 'required|date|after:today',
                'data.lokasi_donor_id' => 'required',
                'data.waktu_donor'     => 'required',
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
        if ($this->alreadyRegistered)
            return;

        $this->validate([
            'data.tanggal_donor'   => 'required|date|after:today',
            'data.lokasi_donor_id' => 'required',
            'data.waktu_donor'     => 'required',
        ]);

        $selectedLocationData = json_decode($this->data['selected_location_data'] ?? '{}', true);

        $donationLocation = DonationLocation::firstOrCreate([
            'place_id' => $this->data['lokasi_donor_id']
        ], [
            'nama_lokasi' => $selectedLocationData['display_name'] ?? 'PMI',
            'alamat'      => $selectedLocationData['display_name'] ?? '',
            'latitude'    => $selectedLocationData['lat'] ?? null,
            'longitude'   => $selectedLocationData['lon'] ?? null,
        ]);

        $donor = DonationsModel::create([
            'user_id'       => Auth::id(),
            'donation_date' => $this->data['tanggal_donor'],
            'location_id'   => $donationLocation->id,
            'time'          => $this->data['waktu_donor'],
            'status_id'     => 1,
        ]);

        $this->donorData         = $donor;
        $this->alreadyRegistered = true;
        $this->form->fill();

        Session::forget(['donation_locations', 'location_data_loaded', 'user_location_coords']);

        Notification::make()
            ->title('Pendaftaran Berhasil!')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        if ($this->alreadyRegistered)
            return [];

        $actions = [
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

        // FIX 8: Tambahkan tombol debug (hapus di production)
        if (app()->environment('local')) {
            $actions[] = Action::make('debug_refresh')
                ->label('Refresh Lokasi (Debug)')
                ->color('warning')
                ->action('refreshLocations');
        }

        return $actions;
    }
}
