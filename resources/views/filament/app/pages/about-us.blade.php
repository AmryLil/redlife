<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $title ?? 'Page Title' }}</title>
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
@vite(['resources/css/app.css', 'resources/js/app.js'])


<!-- Main Content -->
<div class="max-w-3xl mx-auto px-4 py-8">
    <!-- Progress Indicator -->
    <div class="flex justify-center mb-8">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center text-white">1</div>
            <div class="w-16 h-1 bg-red-600"></div>
            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">2</div>
            <div class="w-16 h-1 bg-gray-300"></div>
            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">3</div>
        </div>
    </div>

    <!-- Donation Form -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <!-- Step 1: Pre-Screening -->
        <!-- Step 1: Pre-Screening - Expanded Version -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Cek Kelayakan Donor</h2>

            <div class="bg-red-50 p-4 rounded-lg mb-6">
                <p class="text-red-800 text-sm">
                    🩸 Pastikan Anda memenuhi semua syarat sebelum melanjutkan
                </p>
            </div>

            <!-- Basic Requirements -->
            <div class="space-y-4">
                <!-- Personal Health -->
                <div class="border-b pb-4">
                    <h3 class="font-medium text-gray-700 mb-3">Kesehatan Pribadi</h3>
                    <div class="space-y-3">
                        <label class="flex items-start gap-3">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-red-600 border-gray-300 rounded">
                            <span class="text-gray-700">
                                Usia 17-65 tahun
                                <span class="text-gray-500 text-sm">(di bawah 17 tahun perlu izin orang tua)</span>
                            </span>
                        </label>

                        <label class="flex items-start gap-3">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-red-600 border-gray-300 rounded">
                            <span class="text-gray-700">
                                Berat badan minimal 45 kg
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Medical History -->
                <div class="border-b pb-4">
                    <h3 class="font-medium text-gray-700 mb-3">Riwayat Kesehatan</h3>
                    <div class="space-y-3">
                        <label class="flex items-start gap-3">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-red-600 border-gray-300 rounded">
                            <span class="text-gray-700">
                                Tidak sedang hamil/menyusui
                                <span class="text-gray-500 text-sm">(untuk wanita)</span>
                            </span>
                        </label>

                        <label class="flex items-start gap-3">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-red-600 border-gray-300 rounded">
                            <span class="text-gray-700">
                                Tidak memiliki penyakit menular
                                <span class="text-gray-500 text-sm">(HIV, Hepatitis B/C, dll)</span>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="pb-4">
                    <h3 class="font-medium text-gray-700 mb-3">Aktivitas Terakhir</h3>
                    <div class="space-y-3">
                        <label class="flex items-start gap-3">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-red-600 border-gray-300 rounded">
                            <span class="text-gray-700">
                                Tidak melakukan tato/tindik dalam 6 bulan terakhir
                            </span>
                        </label>

                        <label class="flex items-start gap-3">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-red-600 border-gray-300 rounded">
                            <span class="text-gray-700">
                                Tidak bepergian ke daerah endemik malaria dalam 1 tahun terakhir
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Validation Message -->
            <div id="errorMessage" class="hidden mt-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm"></div>

            <!-- Navigation -->
            <div class="mt-6 flex justify-between">
                <button class="px-4 py-2 text-gray-600 hover:text-red-600">
                    ← Kembali
                </button>
                <button
                    class="bg-red-600 text-white py-2 px-6 rounded-lg hover:bg-red-700 transition disabled:opacity-50"
                    id="checkEligibility">
                    Cek Kelayakan →
                </button>
            </div>
        </div>

        <!-- Step 2: Schedule -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Pilih Jadwal & Lokasi</h2>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-2">Tanggal Donor</label>
                    <input type="date" class="w-full p-2 border rounded-lg">
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Lokasi Donor</label>
                    <div class="space-y-2">
                        <button class="w-full p-3 border rounded-lg hover:border-red-600 text-left">
                            PMI Jakarta Pusat
                        </button>
                        <button class="w-full p-3 border rounded-lg hover:border-red-600 text-left">
                            RS Siloam Kemang
                        </button>
                    </div>
                </div>
            </div>

            <!-- Map Placeholder -->
            <div class="mt-4 bg-gray-100 h-48 rounded-lg flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>

        <!-- Step 3: Confirmation -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Konfirmasi Donor</h2>

            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Tanggal:</span>
                    <span class="font-medium">25 Agustus 2023</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Lokasi:</span>
                    <span class="font-medium">PMI Jakarta Pusat</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Jam:</span>
                    <span class="font-medium">10:00 - 11:00 WIB</span>
                </div>
            </div>

            <button class="mt-6 w-full bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition">
                Konfirmasi Jadwal
            </button>
        </div>
    </div>

    <!-- Preparation Tips -->
    <div class="mt-8 bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">Persiapan Sebelum Donor</h3>
        <ul class="list-disc pl-6 space-y-2 text-gray-600">
            <li>Tidur minimal 6 jam sebelumnya</li>
            <li>Makan makanan bergizi 2-3 jam sebelum donor</li>
            <li>Bawa identitas diri (KTP/SIM)</li>
        </ul>
    </div>
</div>
