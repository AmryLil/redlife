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
    public array $donationLocations      = [];
    public string $userLocationCoords    = '';
    public bool $locationDataLoaded      = false;
    public string $selectedLocationData  = '';
    // PERBAIKAN 1: Tambahkan property untuk tracking lokasi donor
    public ?string $selectedLocationName = null;
    public ?string $selectedLocationId   = null;

    public function handleLokasiUpdate(...$args)
    {
        Log::info('Event diterima', [
            'args'       => $args,
            'args_count' => count($args)
        ]);

        if (count($args) >= 2) {
            $userCoords = $args[0] ?? '';

            if (!empty($userCoords)) {
                $this->userLocationCoords      = $userCoords;
                $this->data['lokasi_pengguna'] = $userCoords;
            }
        }
    }

    // PERBAIKAN 2: Perbaiki method handleSelectedLocationUpdate

    public function handleSelectedLocationUpdate($locationData = null)
    {
        if ($locationData === null) {
            Log::warning('handleSelectedLocationUpdate called without locationData');
            return;
        }

        Log::info('=== LOCATION UPDATE DEBUG ===', [
            'locationData' => $locationData,
            'type'         => gettype($locationData)
        ]);

        $this->selectedLocationData = is_string($locationData) ? $locationData : json_encode($locationData);

        // PERBAIKAN: Handle berbagai format data
        if (is_array($locationData)) {
            // Jika data sudah array
            $selectedLocation = $locationData['lokasi_donor_terpilih'] ?? $locationData;
        } else {
            // Jika data string JSON
            $parsedData       = json_decode($this->selectedLocationData, true);
            $selectedLocation = $parsedData['lokasi_donor_terpilih'] ?? $parsedData ?? [];
        }

        if (!empty($selectedLocation)) {
            // Update form data dengan berbagai kemungkinan key
            $locationName = $selectedLocation['alamat']
                ?? $selectedLocation['name']
                ?? $selectedLocation['location_name']
                ?? 'PMI';

            $locationId = $selectedLocation['place_id']
                ?? $selectedLocation['id']
                ?? uniqid('loc_');

            // Update data form
            $this->data['selected_location_data'] = $this->selectedLocationData;
            $this->data['summary_lokasi']         = $locationName;
            $this->data['lokasi_donor_id']        = $locationId;

            // Update property tracking
            $this->selectedLocationName = $locationName;
            $this->selectedLocationId   = $locationId;

            Log::info('Location data updated', [
                'selectedLocationName' => $this->selectedLocationName,
                'selectedLocationId'   => $this->selectedLocationId,
                'form_data'            => [
                    'summary_lokasi'  => $this->data['summary_lokasi'],
                    'lokasi_donor_id' => $this->data['lokasi_donor_id']
                ]
            ]);

            // Refresh form
            $this->form->fill($this->data);

            // Dispatch event untuk update UI
            $this->dispatch('location-updated', [
                'name' => $this->selectedLocationName,
                'id'   => $this->selectedLocationId
            ]);
        } else {
            Log::warning('No valid location data found in selectedLocation');
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

    public function hydrate(): void
    {
        $this->loadLocationDataFromSession();
    }

    protected function getListeners()
    {
        return [
            'lokasiDiupdate'     => 'handleLokasiUpdate',
            'lokasiDonorDipilih' => 'handleSelectedLocationUpdate',
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
                                        ->readonly()
                                        ->default(function () {
                                            return $this->userLocationCoords ?: Session::get('user_location_coords', '');
                                        }),
                                    View::make('components.peta-leaflet'),
                                    Select::make('waktu_donor')
                                        ->label('Waktu Donor')
                                        ->options([
                                            '08:00' => '08:00 - 09:00',
                                            '09:00' => '09:00 - 10:00',
                                            '10:00' => '10:00 - 11:00',
                                        ])
                                        ->required(),
                                    // PERBAIKAN 3: Tambahkan hidden field untuk lokasi donor
                                    TextInput::make('lokasi_donor_id')
                                        ->label('ID Lokasi Donor')
                                        ->hidden()
                                        ->dehydrated(),
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
                                            $tanggal = $get('tanggal_donor');
                                            return $tanggal ? Carbon::parse($tanggal)->translatedFormat('d F Y') : '';
                                        }),
                                    // PERBAIKAN 4: Perbaiki field summary_lokasi
                                    TextInput::make('summary_lokasi')
                                        ->label('Lokasi Donor')
                                        ->disabled()
                                        ->dehydrated()
                                        ->default(function ($get) {
                                            // Coba ambil dari berbagai sumber
                                            if ($this->selectedLocationName) {
                                                return $this->selectedLocationName;
                                            }

                                            if (isset($this->data['summary_lokasi'])) {
                                                return $this->data['summary_lokasi'];
                                            }

                                            // Fallback ke data form
                                            return $get('summary_lokasi') ?: 'Belum dipilih';
                                        })
                                        ->extraAttributes([
                                            'id' => 'lokasi_terpilih',
                                        ]),
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
                ])
            ])
            ->statePath('data');
    }

    // PERBAIKAN 5: Tambahkan method untuk update data lokasi
    public function updatedDataLokasiDonorId($value)
    {
        if ($value) {
            Log::info('Lokasi donor ID updated', ['value' => $value]);
            // Trigger refresh jika perlu
            $this->dispatch('$refresh');
        }
    }

    public function refreshLocations()
    {
        $this->loadLocationDataFromSession();

        Notification::make()
            ->title('Lokasi di-refresh')
            ->body('Locations count: ' . count($this->donationLocations))
            ->info()
            ->send();

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
                'data.tanggal_donor' => 'required|date|after:today',
                'data.waktu_donor'   => 'required',
            ]);

            // PERBAIKAN: Validasi lokasi yang lebih fleksibel
            $hasLocationId   = !empty($this->data['lokasi_donor_id']) || !empty($this->selectedLocationId);
            $hasLocationName = !empty($this->selectedLocationName) || !empty($this->data['summary_lokasi']);

            Log::info('Step 2 validation', [
                'hasLocationId'        => $hasLocationId,
                'hasLocationName'      => $hasLocationName,
                'lokasi_donor_id'      => $this->data['lokasi_donor_id'] ?? 'not set',
                'selectedLocationId'   => $this->selectedLocationId ?? 'not set',
                'selectedLocationName' => $this->selectedLocationName ?? 'not set'
            ]);

            if (!$hasLocationId && !$hasLocationName) {
                Notification::make()
                    ->title('Lokasi Donor Belum Dipilih')
                    ->body('Silakan pilih lokasi donor dari peta terlebih dahulu')
                    ->warning()
                    ->persistent()
                    ->send();
                return;
            }

            // Pastikan data lokasi ada untuk step 3
            if (!empty($this->selectedLocationName)) {
                $this->data['summary_lokasi'] = $this->selectedLocationName;
            }

            $this->form->fill($this->data);
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
        if ($this->alreadyRegistered) {
            return;
        }

        $this->validate([
            'data.tanggal_donor' => 'required|date|after:today',
            'data.waktu_donor'   => 'required',
        ]);

        // DEBUGGING: Log semua data yang tersedia
        Log::info('=== DEBUG SUBMIT LOCATION ===', [
            'data'                 => $this->data,
            'selectedLocationName' => $this->selectedLocationName,
            'selectedLocationId'   => $this->selectedLocationId,
            'selectedLocationData' => $this->selectedLocationData,
            'userLocationCoords'   => $this->userLocationCoords
        ]);

        // Coba ambil lokasi dari berbagai sumber
        $lokasiDonorId = $this->data['lokasi_donor_id']
            ?? $this->selectedLocationId
            ?? $this->data['selected_location_data']
            ?? null;

        // PERBAIKAN: Jika tidak ada ID, coba gunakan nama lokasi
        $namaLokasi = $this->selectedLocationName
            ?? $this->data['summary_lokasi']
            ?? null;

        Log::info('Location validation', [
            'lokasiDonorId' => $lokasiDonorId,
            'namaLokasi'    => $namaLokasi
        ]);

        // PERBAIKAN: Validasi yang lebih fleksibel
        if (empty($lokasiDonorId) && empty($namaLokasi)) {
            // Tampilkan data debug ke user untuk troubleshooting
            $debugInfo = [
                'data keys'            => array_keys($this->data),
                'selectedLocationName' => $this->selectedLocationName,
                'selectedLocationId'   => $this->selectedLocationId,
            ];

            Notification::make()
                ->title('Error - Debug Info')
                ->body('Lokasi donor belum dipilih. Debug: ' . json_encode($debugInfo))
                ->danger()
                ->persistent()
                ->send();
            return;
        }

        try {
            // PERBAIKAN: Buat lokasi dengan data yang ada
            $locationName = $namaLokasi ?: 'PMI - ' . date('Y-m-d H:i:s');

            $donationLocation = DonationLocation::firstOrCreate(
                ['location_name' => $locationName],
                [
                    'location_detail' => 'Lokasi Donor Darah',
                    'address'         => $locationName,
                    'url_address'     => '',  // Simpan ID di url_address
                ]
            );

            Log::info('DonationLocation created/found', [
                'id'                   => $donationLocation->id,
                'location_name'        => $donationLocation->location_name,
                'was_recently_created' => $donationLocation->wasRecentlyCreated
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
                ->body($donationLocation->wasRecentlyCreated
                    ? 'Lokasi baru berhasil ditambahkan dan pendaftaran selesai!'
                    : 'Pendaftaran berhasil!')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Log::error('Error saat submit donation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            Notification::make()
                ->title('Error')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    // 3. Method helper untuk extract informasi lokasi
    private function extractLocationInfo($selectedLocationData): array
    {
        // Default values menggunakan field yang sudah ada
        $locationInfo = [
            'location_name'   => 'PMI',
            'location_detail' => 'Palang Merah Indonesia',
            'address'         => 'PMI',
            'latitude'        => null,
            'longitude'       => null,
            'city'            => null,
        ];

        // Coba ambil dari selectedLocationData
        if (!empty($selectedLocationData['lokasi_donor_terpilih'])) {
            $selected = $selectedLocationData['lokasi_donor_terpilih'];

            $locationInfo['location_name']   = $selected['alamat'] ?? $selected['name'] ?? $locationInfo['location_name'];
            $locationInfo['location_detail'] = $selected['detail'] ?? $selected['description'] ?? $locationInfo['location_detail'];
            $locationInfo['address']         = $selected['alamat'] ?? $selected['address'] ?? $locationInfo['address'];
            $locationInfo['latitude']        = $selected['lat'] ?? $selected['latitude'] ?? null;
            $locationInfo['longitude']       = $selected['lon'] ?? $selected['longitude'] ?? null;
            $locationInfo['city']            = $selected['city'] ?? null;
        }

        // Fallback ke property class
        if ($locationInfo['location_name'] === 'PMI' && $this->selectedLocationName) {
            $locationInfo['location_name'] = $this->selectedLocationName;
            $locationInfo['address']       = $this->selectedLocationName;
        }

        return $locationInfo;
    }

    // 4. ALTERNATIVE: Method submit yang lebih sederhana
    public function submitAlternative()
    {
        if ($this->alreadyRegistered)
            return;

        $this->validate([
            'data.tanggal_donor' => 'required|date|after:today',
            'data.waktu_donor'   => 'required',
        ]);

        $lokasiDonorId = $this->data['lokasi_donor_id'] ?? $this->selectedLocationId;
        if (empty($lokasiDonorId)) {
            Notification::make()
                ->title('Error')
                ->body('Lokasi donor belum dipilih')
                ->danger()
                ->send();
            return;
        }

        // CARA SEDERHANA: Gunakan location_name sebagai kunci utama
        $locationName = $this->selectedLocationName ?? 'PMI';

        $donationLocation = DonationLocation::firstOrCreate(
            ['location_name' => $locationName],
            [
                'location_detail' => 'Palang Merah Indonesia',
                'address'         => $locationName,
            ]
        );

        $donor = DonationsModel::create([
            'user_id'       => Auth::id(),
            'donation_date' => $this->data['tanggal_donor'],
            'location_id'   => $donationLocation->id,
            'time'          => $this->data['waktu_donor'],
            'status_id'     => 1,
        ]);

        $this->donorData         = $donor;
        $this->alreadyRegistered = true;

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

        if (app()->environment('local')) {
            $actions[] = Action::make('debug_refresh')
                ->label('Refresh Lokasi (Debug)')
                ->color('warning')
                ->action('refreshLocations');
        }

        return $actions;
    }
}
