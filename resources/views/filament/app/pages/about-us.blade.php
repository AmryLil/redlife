<div>
    <link href="https://fonts.googleapis.com/css2?family=Boldonse&display=swap" rel="stylesheet">
    <style>
        .fi-main {
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
    <x-waves></x-waves>
    <h1 class="absolute top-32 font-boldonse left-64 font-bold text-slate-50 text-4xl">About Us</h1>

    <!-- Section Tentang Kami -->
    <div x-data="{ showSection: false }" x-intersect="showSection = true"
        class="py-10 pt-0 px-6 md:px-32 space-y-16 -translate-y-5 w-screen">

        <!-- Visi Misi -->
        <div class="grid md:grid-cols-2 gap-12" x-show="showSection"
            x-transition:enter="transition ease-out duration-500 delay-200"
            x-transition:enter-start="opacity-0 transform translate-x-12"
            x-transition:enter-end="opacity-100 transform translate-x-0">

            <div class="space-y-6">
                <h2 class="text-4xl font-bold text-red-600">Visi Kami</h2>
                <p class="text-gray-600 text-lg leading-relaxed">
                    Menjadi platform penghubung donor darah terpercaya yang menyelamatkan jutaan nyawa
                    melalui teknologi inovatif dan komunitas yang peduli.
                </p>
            </div>

            <div class="space-y-6">
                <h2 class="text-4xl font-bold text-red-600">Misi Kami</h2>
                <ul class="space-y-4 text-gray-600">
                    <li class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        <span>Menyediakan akses donor darah yang cepat dan transparan</span>
                    </li>
                    <!-- Tambahkan poin misi lainnya -->
                </ul>
            </div>
        </div>

        <!-- Fitur Aplikasi -->
        <div class="grid md:grid-cols-3 gap-8" x-data="{
            features: [
                { icon: '🚑', title: 'Respons Cepat', desc: 'Notifikasi real-time kebutuhan darah' },
                { icon: '📱', title: 'Akses Mudah', desc: 'Booking donor melalui smartphone' },
                { icon: '🏆', title: 'Reward System', desc: 'Poin untuk donor setia' }
            ]
        }">

            <template x-for="(feature, index) in features" :key="index">
                <div class="p-8 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow"
                    x-intersect="$el.style.opacity = 1; $el.style.transform = 'translateY(0)'"
                    style="opacity: 0; transform: translateY(20px); transition: all 0.5s ease">
                    <div class="text-6xl mb-6" x-text="feature.icon"></div>
                    <h3 class="text-2xl font-bold mb-4" x-text="feature.title"></h3>
                    <p class="text-gray-600" x-text="feature.desc"></p>
                </div>
            </template>
        </div>

        <!-- Statistik -->
        <div class="text-center py-16" x-data="{ donors: 0, livesSaved: 0 }" x-init="animateNumber($el.querySelector('#donors'), 0, 12543, 2000);
        animateNumber($el.querySelector('#lives'), 0, 37892, 2500);">

            <h2 class="text-4xl font-bold mb-16">Dampak Kami</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="p-8 bg-red-100 rounded-2xl">
                    <div class="text-6xl font-bold text-red-600 mb-4">
                        <span id="donors" x-text="donors"></span>+
                    </div>
                    <p class="text-xl">Donor Terdaftar</p>
                </div>
                <div class="p-8 bg-red-100 rounded-2xl">
                    <div class="text-6xl font-bold text-red-600 mb-4">
                        <span id="lives" x-text="livesSaved"></span>+
                    </div>
                    <p class="text-xl">Nyawa Tertolong</p>
                </div>
            </div>
        </div>
    </div>

</div>
