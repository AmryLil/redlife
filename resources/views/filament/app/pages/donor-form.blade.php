<div class="min-h-screen bg-gray-50">
    <style>
        .fi-main {
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
    {{-- Hero Section --}}
    <svg class="w-screen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="rgb(220 38 38 / var(--tw-text-opacity, 1))" fill-opacity="1"
            d="M0,128L40,149.3C80,171,160,213,240,229.3C320,245,400,235,480,234.7C560,235,640,245,720,234.7C800,224,880,192,960,170.7C1040,149,1120,139,1200,133.3C1280,128,1360,128,1400,128L1440,128L1440,0L1400,0C1360,0,1280,0,1200,0C1120,0,1040,0,960,0C880,0,800,0,720,0C640,0,560,0,480,0C400,0,320,0,240,0C160,0,80,0,40,0L0,0Z">
        </path>
    </svg>
    <h1 class="absolute top-32 left-64 font-bold text-slate-50 text-6xl">Donations</h1>

    {{-- Form Section --}}
    <div class="w-screen md:px-36">
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
                            <div class="font-medium dark:text-white">
                                <div>Artia Jofi F.</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 font-light">Register at 29 Maret
                                    2025</div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <h1 class="font-semibold">Tracking your status donations</h1>
                            <p class="font-light text-sm">Lorem ipsum, dolor sit amet consectetur adipisicing elit.
                                Asperiores, nemo?</p>
                        </div>
                    </div>
                    <ol class="relative border-s border-gray-200 dark:border-gray-700 ">



                        <li class="mb-10 ms-6">
                            <span
                                class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -start-3 ring-8 ring-white dark:ring-gray-900 dark:bg-blue-900">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_1033_1995)">
                                        <circle cx="8" cy="8" r="8" fill="#042558" />
                                        <path
                                            d="M7.10111 9.46201L11.3463 5.2168L11.9994 5.8699L7.10111 10.7682L4.16211 7.82925L4.81522 7.17614L7.10111 9.46201Z"
                                            fill="#F3F3F3" stroke="#F3F3F3" stroke-width="0.5" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_1033_1995">
                                            <rect width="16" height="16" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>

                            </span>
                            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 dark:text-white">
                                Pending
                            </h3>

                            <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">Lorem ipsum dolor sit
                                amet consectetur adipisicing.</p>

                        </li>
                        <li class="mb-10 ms-6">
                            <span
                                class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -start-3 ring-8 ring-white dark:ring-gray-900 dark:bg-blue-900">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_1033_1995)">
                                        <circle cx="8" cy="8" r="8" fill="#042558" />
                                        <path
                                            d="M7.10111 9.46201L11.3463 5.2168L11.9994 5.8699L7.10111 10.7682L4.16211 7.82925L4.81522 7.17614L7.10111 9.46201Z"
                                            fill="#F3F3F3" stroke="#F3F3F3" stroke-width="0.5" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_1033_1995">
                                            <rect width="16" height="16" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>

                            </span>
                            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 dark:text-white">
                                Approve
                            </h3>

                            <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">Lorem ipsum dolor sit
                                amet consectetur adipisicing.</p>
                        </li>
                        <li class="ms-6">
                            <span
                                class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -start-3 ring-8 ring-white dark:ring-gray-900 dark:bg-blue-900">

                            </span>
                            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 dark:text-white">
                                Process
                            </h3>

                            <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">Lorem ipsum dolor sit
                                amet consectetur adipisicing.</p>
                        </li>
                        <li class="ms-6">
                            <span
                                class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -start-3 ring-8 ring-white dark:ring-gray-900 dark:bg-blue-900">

                            </span>
                            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 dark:text-white">
                                Completed
                            </h3>

                            <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">Lorem ipsum dolor sit
                                amet consectetur adipisicing.</p>
                        </li>
                    </ol>
                </div>




                <div class="w-1/2 mx-auto p-6 bg-white rounded-lg shadow">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-green-600">
                            <i class="fas fa-check-circle mr-2"></i>
                            Anda Sudah Terdaftar!
                        </h2>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between border-b pb-2">
                            <span class="font-medium">ID Pendaftaran:</span>
                            <span class="text-gray-600">DONOR-{{ str_pad($donorData->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        <!-- Tambahkan detail lainnya -->
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
                    Submit
                </x-filament::button>
            </div>
        @endif
    </div>
</div>
