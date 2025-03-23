<div class="min-h-screen bg-gray-50">
    <style>
        .fi-main {
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
    {{-- Hero Section --}}
    <div class="w-full h-64 md:h-96 relative bg-gray-900">
        <img src="{{ asset('images/donor.jpg') }}" alt="Ilustrasi donor darah"
            class="w-full h-full object-cover object-[0px_12%] opacity-75">
        <div class="absolute inset-0 flex items-center justify-center md:justify-start md:px-32 bg-black/30">
            <div class="text-white text-center md:text-left text-2xl md:text-4xl font-bold max-w-2xl px-4">
                Setetes darahmu, selamatkan hidup mereka. Jadi pahlawan tanpa jubah hari ini!
            </div>
        </div>
    </div>

    {{-- Form Section --}}
    <div class="max-w-7xl mx-auto md:px-32">
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
            <div class="max-w-7xl mx-auto md:px-32 mt-10">
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
