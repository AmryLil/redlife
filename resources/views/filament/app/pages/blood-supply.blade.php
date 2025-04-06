<div>
    <link href="https://fonts.googleapis.com/css2?family=Boldonse&display=swap" rel="stylesheet">
    <style>
        .fi-main {
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
    <x-waves></x-waves>
    <h1 class="absolute top-32 font-boldonse left-64 font-bold text-slate-50 text-4xl">Blood Supply</h1>

    <div class=" mt-10 flex justify-center w-screen items-center px-28 -translate-y-10 flex-col">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 bg-white shadow-md rounded-md w-full mb-3">
            <!-- Filter Kota -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">District/City</label>
                <select wire:model.live="selectedCity"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">All</option>
                    @foreach ($this->cities as $city)
                        <option value="{{ $city }}">{{ $city }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Lokasi -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Storage Location</label>
                <select wire:model.live="selectedLocationId"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none disabled:bg-gray-200"
                    @if (!$this->selectedCity) disabled @endif>
                    <option value="">All</option>
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
