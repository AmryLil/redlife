<div>
    <link href="https://fonts.googleapis.com/css2?family=Boldonse&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .fi-main {
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
    <x-waves></x-waves>
    <h1 class="absolute top-40 font-boldonse left-32 font-bold text-slate-50 text-4xl uppercase">About Us</h1>

    <!-- Section Tentang Kami -->

    <section class="relative w-screen min-h-screen px-28">
        <!-- Hero Section -->
        <div class="container mx-auto  py-24">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <!-- Animated Blood Cells Visualization -->
                <div class="lg:w-1/2 relative">
                    <img src="https://img.freepik.com/free-photo/world-blood-donor-day-creative-collage_23-2149378367.jpg?t=st=1744212691~exp=1744216291~hmac=32c9cba3c29c17a3e36b87ff4d2f149b1f443963e630c2917ec16971ab7468b8&w=740"
                        alt="" class="w-full h-full">
                </div>

                <!-- Hero Content -->
                <div class="lg:w-1/2 mt-16 lg:mt-0">
                    <h1 class="text-5xl font-bold text-gray-900 mb-8 leading-tight">
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-red-400">Life</span>
                        flows through
                        <span class="italic font-light">every donation</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-12 leading-relaxed">
                        In the silent language of compassion, each blood drop writes a story of hope. We architect the
                        bridge between
                        selfless generosity and critical need with surgical precision.
                    </p>

                    <!-- Impact Stats -->
                    <div class="grid grid-cols-3 gap-8 text-center">
                        <div class="border-l-2 border-red-500 pl-4">
                            <div class="text-4xl font-bold text-red-600" data-counter="12000">12k+</div>
                            <div class="text-sm text-gray-500 mt-2">Lives Impacted</div>
                        </div>
                        <div class="border-l-2 border-red-500 pl-4">
                            <div class="text-4xl font-bold text-red-600" data-counter="85">85</div>
                            <div class="text-sm text-gray-500 mt-2">Partner Hospitals</div>
                        </div>
                        <div class="border-l-2 border-red-500 pl-4">
                            <div class="text-4xl font-bold text-red-600" data-counter="99">99%</div>
                            <div class="text-sm text-gray-500 mt-2">Success Rate</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Philosophy Section -->
        <div class="bg-white py-24">
            <div class="container mx-auto px-6">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="text-red-500 text-sm mb-4 tracking-widest">OUR PHILOSOPHY</div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-12">
                        Precision in every <span class="text-red-600">drop</span>, care in every <span
                            class="text-red-600">process</span>
                    </h2>

                    <!-- Process Timeline -->
                    <div class="relative">
                        <div class="absolute left-1/2 top-0 w-1 h-full bg-red-100 transform -translate-x-1/2"></div>
                        <div class="space-y-24">
                            <div class="relative flex items-center gap-8 even:flex-row-reverse">
                                <div class="w-1/2">
                                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">Intelligent Matching</h3>
                                    <p class="text-gray-600">AI-powered blood type compatibility system ensures perfect
                                        donor-recipient pairing</p>
                                </div>
                                <div class="w-1/2 text-center">
                                    <div
                                        class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-600 text-white text-2xl">
                                        ①
                                    </div>
                                </div>
                            </div>

                            <!-- Repeat similar blocks for other steps -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonial Carousel -->
        <div class="py-24 bg-red-50/30">
            <div class="container mx-auto px-6">
                <div class="max-w-2xl mx-auto text-center mb-16">
                    <div class="text-red-500 text-sm mb-4 tracking-widest">VOICES OF IMPACT</div>
                    <h2 class="text-4xl font-bold text-gray-900">Stories that pulse with <span
                            class="text-red-600">life</span></h2>
                </div>

                <div class="grid md:grid-cols-2 gap-12">
                    <!-- Testimonial Card 1 -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg transition-transform hover:scale-105">
                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                                ❤️
                            </div>
                            <div class="ml-4">
                                <div class="font-bold text-gray-900">Sarah Johnson</div>
                                <div class="text-sm text-red-600">Blood Recipient</div>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">
                            "The seamless coordination during my critical need was nothing short of miraculous.
                            This platform redefines emergency healthcare."
                        </p>
                    </div>

                    <!-- Testimonial Card 2 -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg transition-transform hover:scale-105">
                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                                ⚕️
                            </div>
                            <div class="ml-4">
                                <div class="font-bold text-gray-900">Dr. Michael Tan</div>
                                <div class="text-sm text-red-600">Chief Surgeon</div>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">
                            "Real-time inventory tracking has revolutionized our emergency response capabilities.
                            A game-changer in transfusion medicine."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-30px);
            }
        }

        .animate-float {
            animation: float 6s infinite;
        }

        .animate-float-delayed {
            animation: float 6s infinite 1.5s;
        }
    </style>

    <x-footer></x-footer>


</div>
