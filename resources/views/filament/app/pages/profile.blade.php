<div class="w-full h-full">
    <link href="https://fonts.googleapis.com/css2?family=Boldonse&display=swap" rel="stylesheet">
    <style>
        .fi-main {
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
    <x-waves></x-waves>
    <h1 class="absolute top-32 font-boldonse left-64 font-bold text-slate-50 text-4xl">Profile</h1>

    <div class=" mx-auto w-screen p-6 px-32 -translate-y-14">

        <x-filament-panels::form wire:submit="submit">
            {{ $this->form }}

            <x-filament::button type="submit" icon="heroicon-o-check" color="primary">
                Simpan Perubahan
            </x-filament::button>
        </x-filament-panels::form>
    </div>

</div>
