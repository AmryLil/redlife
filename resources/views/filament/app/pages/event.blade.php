<div>
    <link href="https://fonts.googleapis.com/css2?family=Boldonse&display=swap" rel="stylesheet">
    <style>
        .fi-main {
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
    <x-waves></x-waves>
    <h1 class="absolute top-40 font-boldonse left-32 font-bold text-slate-50 text-4xl uppercase">Events</h1>

    <div class="min-h-screen w-screen">

        <!-- Hero Section -->
        <div class=" relative z-10 pt-28 pb-20 px-6 sm:px-12 lg:px-24">



            <!-- Events Grid -->
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($events as $event)
                    <div
                        class="group relative bg-white/5 backdrop-blur-lg rounded-2xl overflow-hidden shadow hover:shadow-red-900/30 transition-all duration-500 hover:-translate-y-2">
                        <!-- Image with Gradient Overlay -->
                        <div class="relative h-56 overflow-hidden">
                            <img src="{{ $event['image'] }}" alt="Event Cover"
                                class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-red-900/70 to-transparent"></div>
                            <div
                                class="absolute top-4 right-4 bg-red-600 text-white px-3 py-1 rounded-full text-sm shadow-lg">
                                {{ $event['tanggal'] }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <h2 class="text-2xl font-semibold mb-2">{{ $event['nama'] }}</h2>
                            <div class="flex items-center mb-4">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $event['lokasi'] }}
                            </div>

                            <!-- Animated Button -->
                            <a href="#"
                                class="inline-flex items-center px-6 py-2.5 bg-red-600 hover:bg-white/20 text-white rounded-full transition-all duration-300 group-hover:gap-2 gap-0">
                                <span>Detail Event</span>
                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


    </div>

    <style>
        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 0.1;
                transform: scale(1);
            }

            50% {
                opacity: 0.15;
                transform: scale(1.05);
            }
        }

        .animate-pulse-slow {
            animation: pulse-slow 8s infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .font-boldonse {
            font-family: 'Boldonse', sans-serif;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
    </style>

</div>
