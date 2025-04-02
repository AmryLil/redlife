<div>
    <link href="https://fonts.googleapis.com/css2?family=Boldonse&display=swap" rel="stylesheet">
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
    <h1 class="absolute top-40 font-boldonse left-64 font-bold text-slate-50 text-6xl">Blood Supply</h1>

    <div class=" mt-10 flex justify-center w-screen items-center px-28 -translate-y-10 flex-col">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 bg-white shadow-md rounded-md w-full mb-3">
            <!-- Filter Kota -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Pilih Kota</label>
                <select wire:model.live="selectedCity"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Pilih Kota</option>
                    @foreach ($this->cities as $city)
                        <option value="{{ $city }}">{{ $city }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Lokasi -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Pilih Lokasi</label>
                <select wire:model.live="selectedLocationId"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none disabled:bg-gray-200"
                    @if (!$this->selectedCity) disabled @endif>
                    <option value="">Semua Lokasi</option>
                    @foreach ($this->storageLocations as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Clear Button -->
            <div class="self-end">
                <button wire:click="clearFilters"
                    class="flex items-center justify-center gap-2 px-5 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg shadow-md transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    Clear
                </button>
            </div>
        </div>
        <div class="w-full h-full flex gap-4 ">
            <div class="w-full">
                {{ $this->table }}
            </div>
            <iframe src="{{ $this->selectedMapUrl }}" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                class="rounded-md shadow-md transition-all duration-300 {{ $this->selectedMapUrl ? 'block w-full md:w-1/3' : 'hidden' }}">
            </iframe>

        </div>
    </div>


</div>
