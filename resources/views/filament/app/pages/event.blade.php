<div>
    <link href="https://fonts.googleapis.com/css2?family=Boldonse&display=swap" rel="stylesheet">
    <style>
        .fi-main {
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
    <x-waves></x-waves>
    <h1 class="absolute top-32 font-boldonse left-64 font-bold text-slate-50 text-4xl">Events</h1>

    <div class=" mx-auto p-6 px-32 w-screen">
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

</div>
