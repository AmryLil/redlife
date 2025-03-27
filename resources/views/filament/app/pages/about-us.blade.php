<div>
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
    <h1 class="absolute top-32 left-64 font-bold text-slate-50 text-6xl">About Us</h1>

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
