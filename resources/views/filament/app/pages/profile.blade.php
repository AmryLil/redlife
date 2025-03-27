<div class="w-full h-full">
    <style>
        .fi-main {
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
    <svg class="w-screen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="rgb(220 38 38 / var(--tw-text-opacity, 1))" fill-opacity="1"
            d="M0,128L40,149.3C80,171,160,213,240,229.3C320,245,400,235,480,234.7C560,235,640,245,720,234.7C800,224,880,192,960,170.7C1040,149,1120,139,1200,133.3C1280,128,1360,128,1400,128L1440,128L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z">
        </path>
    </svg>
    <h1 class="absolute top-32 left-64 font-bold text-slate-50 text-6xl">Profile</h1>

    <div class=" mx-auto w-screen p-6 px-32 -translate-y-14">

        <x-filament-panels::form wire:submit="submit">
            {{ $this->form }}

            <x-filament::button type="submit" icon="heroicon-o-check" color="primary">
                Simpan Perubahan
            </x-filament::button>
        </x-filament-panels::form>
    </div>

</div>
