<div>
    <link href="https://fonts.googleapis.com/css2?family=Boldonse&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .fi-main {
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
    <div class="min-h-screen w-screen bg-gray-50">

        {{-- Bagian Hero --}}
        <x-waves></x-waves>
        <h1 class="absolute top-40 font-boldonse left-32 font-bold text-slate-50 text-4xl uppercase">Donasi</h1>

        {{-- Bagian Form --}}
        <div class="w-screen md:px-36 ">
            @if ($alreadyRegistered)
                <div class="flex gap-5 ">
                    <div class=" w-1/2">
                        <div class="bg -translate-x-4 mb-10">
                            <div class="flex items-center gap-4 ">
                                <div class="flex items-center justify-center rounded-full bg-red-600 p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        id="Blood-Bag-Cross--Streamline-Ultimate" height="24" width="24">
                                        <desc>Blood Bag Cross Streamline Icon: https://streamlinehq.com</desc>
                                        <path fill="#ffffff" fill-rule="evenodd"
                                            d="M5.971 1.526A4.524 4.524 0 0 1 9.17 0.201h5.66a4.524 4.524 0 0 1 4.524 4.524v9.434a4.523 4.523 0 0 1 -3.774 4.461v1.2a0.75 0.75 0 0 1 -0.75 0.75H13v2.375a1 1 0 0 1 -2 0V20.57H9.17a0.75 0.75 0 0 1 -0.75 -0.75v-1.2a4.524 4.524 0 0 1 -3.774 -4.46V4.724c0 -1.2 0.477 -2.35 1.325 -3.199Zm4.755 3.717h2.753a0.5 0.5 0 0 1 0.5 0.5v2.118l2.015 0a0.5 0.5 0 0 1 0.5 0.5v2.753a0.5 0.5 0 0 1 -0.5 0.5H13.98v2.118a0.5 0.5 0 0 1 -0.5 0.5h-2.753a0.5 0.5 0 0 1 -0.5 -0.5l0 -2.118 -2.22 0a0.5 0.5 0 0 1 -0.5 -0.5l0 -2.753a0.5 0.5 0 0 1 0.5 -0.5h2.22l0 -2.118a0.5 0.5 0 0 1 0.5 -0.5Z"
                                            clip-rule="evenodd" stroke-width="1"></path>
                                    </svg>
                                </div>
                                <div class="font-semibold text-lg dark:text-white">
                                    <div>{{ $donorData->user->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 font-light">Terdaftar pada 29
                                        Maret
                                        2025</div>
                                </div>
                            </div>
                            <div class="my-8">
                                <h1 class="font-semibold text-xl">Lacak Status Donasi Anda</h1>
                                <p class="font-light text-sm">Pantau perkembangan proses donasi darah Anda dari
                                    pendaftaran hingga selesai.</p>
                            </div>
                        </div>
                        <ol class="relative border-s border-gray-200 dark:border-gray-700">
                            @php
                                $statuses = [
                                    1 => [
                                        'name' => 'Menunggu',
                                        'description' => 'Menunggu persetujuan dari tim kami.',
                                    ],
                                    2 => [
                                        'name' => 'Disetujui',
                                        'description' =>
                                            'Donasi Anda telah disetujui. Silakan lanjutkan ke tahap pengambilan darah.',
                                    ],
                                    3 => [
                                        'name' => 'Ditolak',
                                        'description' => 'Maaf, Anda tidak memenuhi persyaratan untuk mendonor darah.',
                                    ],
                                    4 => [
                                        'name' => 'Sedang Berlangsung',
                                        'description' => 'Proses pengambilan darah sedang berlangsung.',
                                    ],
                                    5 => [
                                        'name' => 'Terkumpul',
                                        'description' =>
                                            'Darah telah berhasil terkumpul dan sekarang memasuki tahap pemeriksaan.',
                                    ],
                                    6 => [
                                        'name' => 'Skrining & Proses',
                                        'description' => 'Darah sedang dalam proses skrining dan pengolahan.',
                                    ],
                                    7 => [
                                        'name' => 'Darah Ditolak',
                                        'description' =>
                                            'Maaf, darah Anda tidak lolos skrining dan tidak dapat digunakan.',
                                    ],
                                    8 => [
                                        'name' => 'Selesai',
                                        'description' =>
                                            'Semua proses selesai, dan darah siap digunakan atau telah diberikan kepada pasien.',
                                    ],
                                ];

                            @endphp

                            @foreach ($statuses as $id => $status)
                                @if ($id == 3 || $id == 7)
                                    @if ($donorData->status_id == $id)
                                        {{-- Tampilkan status 3 (Ditolak) atau 7 (Darah Ditolak), lalu hentikan perulangan --}}
                                        <li class="mb-10 ms-6">
                                            <span
                                                class="absolute flex items-center justify-center w-6 h-6 bg-red-600 
                    rounded-full -start-3 ring-8 ring-white dark:ring-gray-900 dark:bg-blue-900">
                                                <svg width="24" height="24" viewBox="0 0 16 16" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="8" cy="8" r="8" fill="#dc2626" />
                                                    <path
                                                        d="M7.10111 9.46201L11.3463 5.2168L11.9994 5.8699L7.10111 10.7682L4.16211 7.82925L4.81522 7.17614L7.10111 9.46201Z"
                                                        fill="#F3F3F3" stroke="#F3F3F3" stroke-width="0.5" />
                                                </svg>
                                            </span>

                                            <h3
                                                class="-translate-y-1 flex items-center mb-1 text-lg font-semibold text-red-600">
                                                {{ $status['name'] }}
                                            </h3>
                                            <p
                                                class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400 text-red-600">
                                                {{ $status['description'] }}
                                            </p>
                                        </li>
                                        @break

                                        {{-- Hentikan perulangan setelah status 3 atau 7 --}}
                                    @endif
                                @else
                                    {{-- Status lain tetap ditampilkan jika status pendonor tidak 3 atau 7 --}}
                                    <li class="mb-10 ms-6">
                                        <span
                                            class="absolute flex items-center justify-center w-6 h-6 
                {{ $donorData->status_id >= $id ? '' : 'bg-gray-300' }} 
                rounded-full -start-3 ring-8 ring-white dark:ring-gray-900 dark:bg-blue-900">
                                            @if ($donorData->status_id >= $id)
                                                <svg width="24" height="24" viewBox="0 0 16 16" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="8" cy="8" r="8" fill="#dc2626" />
                                                    <path
                                                        d="M7.10111 9.46201L11.3463 5.2168L11.9994 5.8699L7.10111 10.7682L4.16211 7.82925L4.81522 7.17614L7.10111 9.46201Z"
                                                        fill="#F3F3F3" stroke="#F3F3F3" stroke-width="0.5" />
                                                </svg>
                                            @endif
                                        </span>

                                        <h3
                                            class="-translate-y-1 flex items-center mb-1 text-lg font-semibold 
                {{ $donorData->status_id == $id ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                                            {{ $status['name'] }}
                                        </h3>
                                        <p
                                            class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400 
                {{ $donorData->status_id == $id ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                                            {{ $status['description'] }}
                                        </p>
                                    </li>
                                @endif
                            @endforeach

                        </ol>

                    </div>




                    <div class="w-1/2 mx-auto p-6 bg-white rounded-xl shadow-md space-y-6">
                        <div class="text-center">
                            <div class="gradient-custom rounded-md py-4">
                                <h2 class="text-3xl font-bold text-slate-50 flex items-center justify-center gap-2">
                                    <i class="fas fa-check-circle"></i>
                                    DONOR-{{ str_pad($donorData->id, 6, '0', STR_PAD_LEFT) }}
                                </h2>
                            </div>
                            <p class="mt-2 text-gray-600 text-sm">
                                @switch($donorData->status_id)
                                    @case(1)
                                        <span class="text-yellow-500 text-lg">Menunggu</span> - Menunggu persetujuan.
                                    @break

                                    @case(2)
                                        <span class="text-green-600">Disetujui</span> - Lanjutkan ke tahap berikutnya.
                                    @break

                                    @case(3)
                                        <span class="text-red-600">Ditolak</span> - Tidak memenuhi persyaratan.
                                    @break

                                    @case(4)
                                        <span class="text-blue-500">Sedang Berlangsung</span> - Saat ini sedang berlangsung.
                                    @break

                                    @case(5)
                                        <span class="text-purple-500">Terkumpul</span> - Memasuki tahap pemeriksaan.
                                    @break

                                    @case(6)
                                        <span class="text-indigo-500">Skrining</span> - Sedang dalam skrining.
                                    @break

                                    @case(7)
                                        <span class="text-red-600">Darah Ditolak</span> - Tidak lolos skrining.
                                    @break

                                    @case(8)
                                        <span class="text-green-600">Selesai</span> - Proses selesai.
                                    @break

                                    @default
                                        <span class="text-gray-500">Tidak Aktif</span>
                                @endswitch

                            </p>
                        </div>

                        <div class="space-y-4 text-sm">
                            {{-- Jika status 1 (Menunggu), tampilkan gambar loading --}}
                            @if ($donorData->status_id == 1)
                                <div class="flex flex-col items-center">
                                    <img src="{{ asset('images/tl (2).webp') }}" alt="Loading" class="w-80 h-72">
                                    <p class="text-yellow-500 font-semibold mt-2 text-xl">Menunggu persetujuan...</p>
                                </div>
                                <p class="px-10 font-light text-lg">
                                    Data Anda sedang diverifikasi oleh tim kami. Mohon tunggu sebentar...
                                </p>
                            @endif

                            {{-- Jika status 2 (Disetujui), tampilkan informasi lengkap --}}
                            @if ($donorData->status_id == 2)
                                <div class="w-full max-w-lg mx-auto p-6 bg-white rounded-xl shadow-md space-y-6">
                                    <div class="text-center">
                                        <h2 class="text-2xl font-bold text-green-600">Data Anda Telah Diverifikasi</h2>
                                        <p class="mt-2 text-gray-600">Silakan kunjungi lokasi donasi sesuai dengan
                                            detail
                                            di bawah ini.</p>
                                    </div>

                                    <div class="space-y-4 text-sm">
                                        <div class="flex justify-between border-b pb-2">
                                            <span class="font-semibold text-gray-700">Nama:</span>
                                            <span class="text-gray-900">{{ $donorData->user->name }}</span>
                                        </div>

                                        <div class="flex justify-between border-b pb-2">
                                            <span class="font-semibold text-gray-700">Lokasi:</span>
                                            <span
                                                class="text-gray-900">{{ $donorData->location->location_name }}</span>
                                        </div>

                                        <div class="flex justify-between border-b pb-2">
                                            <span class="font-semibold text-gray-700">Alamat:</span>
                                            <span class="text-gray-900">{{ $donorData->location->address }}</span>
                                        </div>

                                        <div class="flex justify-between border-b pb-2">
                                            <span class="font-semibold text-gray-700">Detail Lokasi:</span>
                                            <span
                                                class="text-gray-900">{{ $donorData->location->location_detail }}</span>
                                        </div>

                                        <div class="flex justify-between border-b pb-2">
                                            <span class="font-semibold text-gray-700">Waktu:</span>
                                            <span class="text-gray-900">{{ $donorData->time }}</span>
                                        </div>

                                        <div class="flex justify-between border-b pb-2">
                                            <span class="font-semibold text-gray-700">Tanggal Pendaftaran:</span>
                                            <span
                                                class="text-gray-900">{{ $donorData->created_at->format('d M Y H:i') }}</span>
                                        </div>

                                        <p class="text-red-500 font-semibold mt-1">
                                            Catatan: Harap tunjukkan ID Donor Anda kepada tim kami di lokasi.
                                        </p>

                                        <div class="w-full h-64 mt-4 rounded-lg overflow-hidden shadow-lg">
                                            <iframe
                                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3973.8333120298153!2d119.42171447428473!3d-5.130534451910331!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbefd5bd367e6db%3A0xfe472cb906daaccf!2sUTD%20PMI%20Kota%20Makassar!5e0!3m2!1sen!2sen!4v1743325462585!5m2!1sen!2sen"
                                                width="100%" height="100%" style="border:0;" allowfullscreen=""
                                                loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                                            </iframe>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Jika status 3 (Ditolak) --}}
                            @if ($donorData->status_id == 3)
                                <div class="flex flex-col items-center text-center">
                                    <img src="{{ asset('images/reject.webp') }}" alt="Rejected" class="w-72 h-72">
                                    <p class="text-red-600 font-semibold mt-4 text-xl">Maaf, Anda Tidak Memenuhi
                                        Persyaratan</p>
                                </div>
                                <p class="px-10 font-light text-lg text-gray-700">
                                    Terima kasih telah mendaftar sebagai pendonor. Namun, setelah proses verifikasi,
                                    kami
                                    menyesal
                                    memberitahukan bahwa
                                    Anda tidak memenuhi persyaratan untuk mendonor darah pada saat ini.
                                    <br><br>
                                    Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi tim kami. Tetap sehat
                                    dan terus
                                    berkontribusi untuk orang lain!
                                </p>
                            @endif

                            {{-- Jika status 4 (Sedang Berlangsung) --}}
                            @if ($donorData->status_id == 4)
                                <div class="flex flex-col items-center text-center">
                                    <img src="{{ asset('images/process.webp') }}" alt="In Progress"
                                        class="w-72 h-72">
                                    <p class="text-blue-600 font-semibold mt-4 text-xl">Donasi Anda Sedang Berlangsung
                                    </p>
                                </div>
                                <p class="px-10 font-light text-lg text-gray-700">
                                    Proses donasi Anda saat ini sedang berlangsung. Silakan ikuti instruksi yang
                                    diberikan
                                    oleh
                                    tim kami di lokasi donasi.
                                    <br><br>
                                    Jika Anda memerlukan bantuan lebih lanjut, jangan ragu untuk menghubungi tim
                                    dukungan kami. Terima
                                    kasih
                                    atas kontribusi Anda!
                                </p>
                            @endif

                            @if ($donorData->status_id == 5)
                                <div class="flex flex-col items-center text-center">
                                    <img src="{{ asset('images/collect.webp') }}" alt="Collected" class="w-72 h-72">
                                    <p class="text-purple-600 font-semibold mt-4 text-xl">Darah Anda Telah Terkumpul
                                    </p>
                                </div>
                                <p class="px-10 font-light text-lg text-gray-700">
                                    Donasi darah Anda telah berhasil terkumpul dan sekarang sedang menjalani pemeriksaan
                                    lebih lanjut.
                                    <br><br>
                                    Terima kasih atas kontribusi berharga Anda. Jika Anda memiliki pertanyaan, silakan
                                    hubungi
                                    tim
                                    dukungan kami.
                                </p>
                            @endif

                            @if ($donorData->status_id == 6)
                                <div class="flex flex-col items-center text-center">
                                    <img src="{{ asset('images/screnning.webp') }}" alt="Screening"
                                        class="w-72 h-72">
                                    <p class="text-indigo-600 font-semibold mt-4 text-xl">Skrining & Proses</p>
                                </div>
                                <p class="px-10 font-light text-lg text-gray-700">
                                    Darah yang Anda donorkan saat ini sedang menjalani proses skrining untuk memastikan
                                    memenuhi
                                    semua
                                    standar kesehatan dan keamanan.
                                    <br><br>
                                    Ini adalah langkah penting sebelum darah Anda dapat digunakan untuk mereka yang
                                    membutuhkan. Terima
                                    kasih
                                    atas kesabaran dan kebaikan hati Anda!
                                </p>
                            @endif

                            @if ($donorData->status_id == 7)
                                <div class="flex flex-col items-center text-center">
                                    <img src="{{ asset('images/reject_blood.webp') }}" alt="Rejected Blood"
                                        class="w-72 h-72">
                                    <p class="text-red-600 font-semibold mt-4 text-xl">Darah Ditolak</p>
                                </div>
                                <p class="px-10 font-light text-lg text-gray-700">
                                    Sayangnya, setelah skrining dan pengujian lebih lanjut, darah yang Anda donorkan
                                    tidak memenuhi
                                    standar
                                    yang diperlukan.
                                    <br><br>
                                    Kami menghargai kemauan Anda untuk mendonor dan mendorong Anda untuk mencoba lagi di
                                    masa
                                    depan.
                                    Jika Anda memiliki
                                    pertanyaan, silakan hubungi tim kami.
                                </p>
                            @endif


                            @if ($donorData->status_id == 8)
                                <div class="flex flex-col items-center text-center">
                                    <img src="{{ asset('images/completed.webp') }}" alt="Completed"
                                        class="w-72 h-72">
                                    <p class="text-green-600 font-semibold mt-4 text-xl">Donasi Selesai</p>
                                </div>
                                <p class="px-10 font-light text-lg text-gray-700">
                                    Selamat! Donasi darah Anda telah berhasil diproses dan sekarang siap
                                    membantu mereka yang membutuhkan.
                                    <br><br>
                                    Terima kasih atas kebaikan dan kontribusi Anda. Donasi Anda membuat perbedaan nyata
                                    dalam
                                    menyelamatkan nyawa!
                                </p>

                                <div x-data="{ showModal: false }" class="w-full max-w-lg mx-auto p-6">
                                    <button @click="showModal = true; document.body.classList.add('overflow-hidden')"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 transition-all text-white font-semibold rounded-md shadow-md ">
                                        Tampilkan Detail
                                    </button>

                                    <div x-show="showModal" x-transition.opacity
                                        class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 backdrop-blur-md">
                                        <div x-show="showModal" x-transition.scale.origin.center
                                            class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-lg transform transition-all relative">
                                            <button
                                                @click="showModal = false; document.body.classList.remove('overflow-hidden')"
                                                class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 transition-all">
                                                ✕
                                            </button>
                                            <div class="space-y-6 text-sm">
                                                <h2
                                                    class="text-2xl font-extrabold text-gray-800 border-b pb-4 text-center">
                                                    Detail Pendonor</h2>
                                                <div class="flex justify-between border-b pb-2">
                                                    <span class="font-semibold text-gray-600">Nama:</span>
                                                    <span class="text-gray-900">{{ $donorData->user->name }}</span>
                                                </div>
                                                <div class="flex justify-between border-b pb-2">
                                                    <span class="font-semibold text-gray-600">Golongan Darah:</span>
                                                    <span
                                                        class="text-gray-900">{{ "{$bloodDetails->bloodType->group}{$bloodDetails->bloodType->rhesus}" }}</span>
                                                </div>
                                                <div class="flex justify-between border-b pb-2">
                                                    <span class="font-semibold text-gray-600">Tanggal
                                                        Kedaluwarsa:</span>
                                                    <span
                                                        class="text-gray-900">{{ $bloodDetails->expiry_date }}</span>
                                                </div>
                                                <div class="flex justify-between border-b pb-2">
                                                    <span class="font-semibold text-gray-600">Komponen Darah:</span>
                                                    <span
                                                        class="text-gray-900">{{ $bloodDetails->blood_component }}</span>
                                                </div>
                                                <div class="flex justify-between border-b pb-2">
                                                    <span class="font-semibold text-gray-600">Lokasi
                                                        Penyimpanan:</span>
                                                    <span
                                                        class="text-gray-900">{{ $bloodDetails->storageLocation->name }}</span>
                                                </div>
                                                <div class="flex justify-between border-b pb-2">
                                                    <span class="font-semibold text-gray-600">Status:</span>
                                                    <span class="text-gray-900">{{ $bloodDetails->status }}</span>
                                                </div>
                                            </div>
                                            <button
                                                @click="showModal = false; document.body.classList.remove('overflow-hidden')"
                                                class="mt-6 px-5 py-3 bg-red-500 hover:bg-red-600 transition-all text-white font-semibold rounded-xl w-full shadow-lg">
                                                Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif






                        </div>

                    </div>



                </div>
            @else
                {{-- Tampilan Form Registrasi --}}
                <div class=" mt-10">
                    {{ $this->form }}
                </div>
                <div class="flex justify-end gap-4 mt-8 border-t pt-6">
                    <x-filament::button type="submit" wire:click="submit" size="lg" class="!text-lg">
                        Kirim
                    </x-filament::button>
                </div>
            @endif
        </div>
    </div>
    <x-footer></x-footer>

</div>
