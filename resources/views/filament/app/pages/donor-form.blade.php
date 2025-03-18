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
        <div class="py-10">
            {{ $this->form }}

            {{-- Form Actions --}}
            <div class="flex justify-end gap-4 mt-8 border-t pt-6">
                <x-filament::button type="submit" wire:click="submit" icon="heroicon-o-heart" size="lg"
                    class="!text-lg">
                    Daftar Donor Sekarang
                </x-filament::button>
            </div>
        </div>
    </div>
</div>
