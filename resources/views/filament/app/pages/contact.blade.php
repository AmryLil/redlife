<div>
    <link href="https://fonts.googleapis.com/css2?family=Boldonse&display=swap" rel="stylesheet">

    <style>
        .fi-main {
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
    <div class="min-h-screen w-screen">
        <x-waves></x-waves>
        <h1 class="absolute top-40 font-boldonse left-32 font-bold text-slate-50 text-4xl uppercase">Contact</h1>


        <!-- Hero Section -->
        <div class="relative z-10 pt-28 pb-20 px-6 sm:px-12 lg:px-24">


            <!-- Contact Content -->
            <div class="grid lg:grid-cols-2 gap-12 items-start">
                <!-- Contact Info -->
                <div class="space-y-8">
                    <!-- Contact Card 1 -->
                    <div
                        class="group relative bg-white/5 backdrop-blur-lg rounded-2xl p-8 shadow hover:shadow-red-900/30 transition-all duration-500">
                        <div class="flex items-center gap-6">
                            <div class="p-4 bg-red-600/20 rounded-xl">
                                <svg class="w-8 h-8 text-slate-950" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold ">Phone</h3>
                                <p class="text-slate-950 text-lg">021-1234-5678</p>
                                <p class="text-slate-950">24/7 Available</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Card 2 -->
                    <div
                        class="group relative bg-white/5 backdrop-blur-lg rounded-2xl p-8 shadow hover:shadow-red-900/30 transition-all duration-500">
                        <div class="flex items-center gap-6">
                            <div class="p-4 bg-red-600/20 rounded-xl">
                                <svg class="w-8 h-8 text-slate-950" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold ">Email</h3>
                                <p class="text-slate-950 text-lg">support@donordarah.id</p>
                                <p class="text-slate-950">Respon dalam 1 jam</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Card 3 -->
                    <div
                        class="group relative bg-white/5 backdrop-blur-lg rounded-2xl p-8 shadow hover:shadow-red-900/30 transition-all duration-500">
                        <div class="flex items-center gap-6">
                            <div class="p-4 bg-red-600/20 rounded-xl">
                                <svg class="w-8 h-8 text-slate-950" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold ">Address</h3>
                                <p class="text-slate-950 text-lg">Jl. Kesehatan No. 123</p>
                                <p class="text-slate-950">Jakarta Pusat</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-white/5 backdrop-blur-lg rounded-2xl p-8 shadow border border-white/10">
                    <form class="space-y-6">
                        <div>
                            <label class="block  text-lg mb-2">Full Name</label>
                            <input type="text"
                                class="w-full bg-white/10 border  rounded-lg px-4 py-3  placeholder-red-200 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block  text-lg mb-2">Email</label>
                            <input type="email"
                                class="w-full bg-white/10 border  rounded-lg px-4 py-3  placeholder-red-200 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block  text-lg mb-2">Subject</label>
                            <input type="text"
                                class="w-full bg-white/10 border  rounded-lg px-4 py-3  placeholder-red-200 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block  text-lg mb-2">Message</label>
                            <textarea rows="5"
                                class="w-full bg-white/10 border  rounded-lg px-4 py-3  placeholder-red-200 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent"></textarea>
                        </div>

                        <button
                            class="w-full py-3.5 gradient-custom text-slate-50 hover:bg-red-700 rounded-lg font-semibold text-lg transition-all duration-300 transform hover:scale-[1.02]">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- Map Section -->

        </div>
    </div>

    <x-footer></x-footer>



</div>
