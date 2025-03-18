<x-filament::page>
    <div class="relative w-full h-64 bg-cover bg-center"
        style="background-image: url('https://source.unsplash.com/1600x900/?blood,donation');">
        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <h1 class="text-white text-4xl font-bold">Event Donor Darah</h1>
        </div>
    </div>

    <div class="max-w-6xl mx-auto p-6">
        <p class="text-gray-600 text-center text-lg mb-6">
            Bergabunglah dalam event donor darah dan selamatkan nyawa orang lain!
        </p>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($events as $event)
                <div
                    class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition duration-300">
                    <img src="{{ $event['image'] }}" alt="Event Cover" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <h2 class="text-xl font-semibold text-gray-800">{{ $event['nama'] }}</h2>
                        <p class="text-sm text-gray-600">{{ $event['tanggal'] }}</p>
                        <p class="text-sm text-gray-600">Lokasi: {{ $event['lokasi'] }}</p>
                        <a href="#" class="block mt-4 text-red-600 font-bold hover:underline">Detail Event</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament::page>
