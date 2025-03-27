<div class="min-h-screen bg-gray-50">
    <style>
        .fi-main {
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
    {{-- Hero Section --}}
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="rgb(220 38 38 / var(--tw-text-opacity, 1))" fill-opacity="1"
            d="M0,128L40,149.3C80,171,160,213,240,229.3C320,245,400,235,480,234.7C560,235,640,245,720,234.7C800,224,880,192,960,170.7C1040,149,1120,139,1200,133.3C1280,128,1360,128,1400,128L1440,128L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z">
        </path>
    </svg>
    <h1 class="absolute top-32 left-64 font-bold text-slate-50 text-6xl">Donations</h1>

    {{-- Form Section --}}
    <div class="mx-auto md:px-32">
        @if ($alreadyRegistered)
            <div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-green-600">
                        <i class="fas fa-check-circle mr-2"></i>
                        Anda Sudah Terdaftar!
                    </h2>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between border-b pb-2">
                        <span class="font-medium">ID Pendaftaran:</span>
                        <span class="text-gray-600">DONOR-{{ str_pad($donorData->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    <!-- Tambahkan detail lainnya -->
                </div>
            </div>
        @else
            {{-- Tampilan Form Registrasi --}}
            <div class=" mt-10">
                {{ $this->form }}
            </div>
            <div class="flex justify-end gap-4 mt-8 border-t pt-6">
                <x-filament::button type="submit" wire:click="submit" size="lg" class="!text-lg">
                    Submit
                </x-filament::button>
            </div>
        @endif
    </div>
</div>
