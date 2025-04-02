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
    <h1 class="absolute top-40 font-boldonse left-64 font-bold text-slate-50 text-6xl">Events</h1>

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
