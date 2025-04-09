<div>
    <link href="https://fonts.googleapis.com/css2?family=Boldonse&display=swap" rel="stylesheet">

    <style>
        .fi-main {
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
    <div class="min-h-screen w-screen bg-gray-50">

        {{-- Hero Section --}}
        <x-waves></x-waves>
        <h1 class="absolute top-40 font-boldonse left-32 font-bold text-slate-50 text-4xl uppercase">Donations</h1>

        {{-- Form Section --}}
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
                                    <div class="text-sm text-gray-500 dark:text-gray-400 font-light">Register at 29
                                        Maret
                                        2025</div>
                                </div>
                            </div>
                            <div class="my-8">
                                <h1 class="font-semibold text-xl">Tracking your status donations</h1>
                                <p class="font-light text-sm">Lorem ipsum, dolor sit amet consectetur adipisicing elit.
                                    Asperiores, nemo?</p>
                            </div>
                        </div>
                        <ol class="relative border-s border-gray-200 dark:border-gray-700">
                            @php
                                $statuses = [
                                    1 => [
                                        'name' => 'Pending',
                                        'description' => 'Waiting for approval from our team.',
                                    ],
                                    2 => [
                                        'name' => 'Approved',
                                        'description' =>
                                            'Your donation has been approved. Please proceed to the blood collection stage.',
                                    ],
                                    3 => [
                                        'name' => 'Rejected',
                                        'description' => 'Sorry, you do not meet the requirements to donate blood.',
                                    ],
                                    4 => [
                                        'name' => 'In Progress',
                                        'description' => 'The blood collection process is currently underway.',
                                    ],
                                    5 => [
                                        'name' => 'Collected',
                                        'description' =>
                                            'The blood has been successfully collected and is now entering the examination stage.',
                                    ],
                                    6 => [
                                        'name' => 'Screening & Process',
                                        'description' => 'The blood is currently undergoing screening and processing.',
                                    ],
                                    7 => [
                                        'name' => 'Rejected Blood',
                                        'description' =>
                                            'Sorry, your blood did not pass the screening and cannot be used.',
                                    ],
                                    8 => [
                                        'name' => 'Completed',
                                        'description' =>
                                            'All processes are complete, and the blood is ready for use or has been given to a patient.',
                                    ],
                                ];

                            @endphp

                            @foreach ($statuses as $id => $status)
                                @if ($id == 3 || $id == 7)
                                    @if ($donorData->status_id == $id)
                                        {{-- Tampilkan status 3 (Rejected) atau 7 (Rejected Blood), lalu hentikan perulangan --}}
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
                            <div class="bg-red-600 rounded-md py-4">
                                <h2 class="text-3xl font-bold text-slate-50 flex items-center justify-center gap-2">
                                    <i class="fas fa-check-circle"></i>
                                    DONOR-{{ str_pad($donorData->id, 6, '0', STR_PAD_LEFT) }}
                                </h2>
                            </div>
                            <p class="mt-2 text-gray-600 text-sm">
                                @switch($donorData->status_id)
                                    @case(1)
                                        <span class="text-yellow-500 text-lg">Pending</span> - Waiting for approval.
                                    @break

                                    @case(2)
                                        <span class="text-green-600">Approved</span> - Proceed to the next stage.
                                    @break

                                    @case(3)
                                        <span class="text-red-600">Rejected</span> - Does not meet the requirements.
                                    @break

                                    @case(4)
                                        <span class="text-blue-500">In Progress</span> - Currently in progress.
                                    @break

                                    @case(5)
                                        <span class="text-purple-500">Collected</span> - Entering the examination stage.
                                    @break

                                    @case(6)
                                        <span class="text-indigo-500">Screening</span> - Undergoing screening.
                                    @break

                                    @case(7)
                                        <span class="text-red-600">Rejected Blood</span> - Did not pass screening.
                                    @break

                                    @case(8)
                                        <span class="text-green-600">Completed</span> - Process completed.
                                    @break

                                    @default
                                        <span class="text-gray-500">Inactive</span>
                                @endswitch

                            </p>
                        </div>

                        <div class="space-y-4 text-sm">
                            {{-- If the status is 1 (Pending), show loading image --}}
                            @if ($donorData->status_id == 1)
                                <div class="flex flex-col items-center">
                                    <img src="{{ asset('images/tl (2).webp') }}" alt="Loading" class="w-80 h-72">
                                    <p class="text-yellow-500 font-semibold mt-2 text-xl">Waiting for approval...</p>
                                </div>
                                <p class="px-10 font-light text-lg">
                                    Your data is being verified by our team. Please wait for a moment...
                                </p>
                            @endif

                            {{-- If the status is 2 (Approved), display complete information --}}
                            @if ($donorData->status_id == 2)
                                <div class="w-full max-w-lg mx-auto p-6 bg-white rounded-xl shadow-md space-y-6">
                                    <div class="text-center">
                                        <h2 class="text-2xl font-bold text-green-600">Your Data Has Been Verified</h2>
                                        <p class="mt-2 text-gray-600">Please visit the donation location as per the
                                            details
                                            below.</p>
                                    </div>

                                    <div class="space-y-4 text-sm">
                                        <div class="flex justify-between border-b pb-2">
                                            <span class="font-semibold text-gray-700">Name:</span>
                                            <span class="text-gray-900">{{ $donorData->user->name }}</span>
                                        </div>

                                        <div class="flex justify-between border-b pb-2">
                                            <span class="font-semibold text-gray-700">Location:</span>
                                            <span
                                                class="text-gray-900">{{ $donorData->location->location_name }}</span>
                                        </div>

                                        <div class="flex justify-between border-b pb-2">
                                            <span class="font-semibold text-gray-700">Address:</span>
                                            <span class="text-gray-900">{{ $donorData->location->address }}</span>
                                        </div>

                                        <div class="flex justify-between border-b pb-2">
                                            <span class="font-semibold text-gray-700">Location Details:</span>
                                            <span
                                                class="text-gray-900">{{ $donorData->location->location_detail }}</span>
                                        </div>

                                        <div class="flex justify-between border-b pb-2">
                                            <span class="font-semibold text-gray-700">Time:</span>
                                            <span class="text-gray-900">{{ $donorData->time }}</span>
                                        </div>

                                        <div class="flex justify-between border-b pb-2">
                                            <span class="font-semibold text-gray-700">Registration Date:</span>
                                            <span
                                                class="text-gray-900">{{ $donorData->created_at->format('d M Y H:i') }}</span>
                                        </div>

                                        <p class="text-red-500 font-semibold mt-1">
                                            Note: Please show your Donor ID to our team at the location.
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

                            {{-- If the status is 3 (Rejected) --}}
                            @if ($donorData->status_id == 3)
                                <div class="flex flex-col items-center text-center">
                                    <img src="{{ asset('images/reject.webp') }}" alt="Rejected" class="w-72 h-72">
                                    <p class="text-red-600 font-semibold mt-4 text-xl">Sorry, You Do Not Meet the
                                        Requirements</p>
                                </div>
                                <p class="px-10 font-light text-lg text-gray-700">
                                    Thank you for registering as a donor. However, after the verification process, we
                                    regret
                                    to inform you that
                                    you do not meet the requirements to donate blood at this time.
                                    <br><br>
                                    If you have any further questions, please contact our team. Stay healthy and keep
                                    contributing to others!
                                </p>
                            @endif

                            {{-- If the status is 4 (In Progress) --}}
                            @if ($donorData->status_id == 4)
                                <div class="flex flex-col items-center text-center">
                                    <img src="{{ asset('images/process.webp') }}" alt="In Progress"
                                        class="w-72 h-72">
                                    <p class="text-blue-600 font-semibold mt-4 text-xl">Your Donation is in Progress
                                    </p>
                                </div>
                                <p class="px-10 font-light text-lg text-gray-700">
                                    Your donation process is currently in progress. Please follow the instructions given
                                    by
                                    our team at the donation site.
                                    <br><br>
                                    If you need further assistance, feel free to reach out to our support team. Thank
                                    you
                                    for your contribution!
                                </p>
                            @endif

                            @if ($donorData->status_id == 5)
                                <div class="flex flex-col items-center text-center">
                                    <img src="{{ asset('images/collect.webp') }}" alt="Collected" class="w-72 h-72">
                                    <p class="text-purple-600 font-semibold mt-4 text-xl">Your Blood Has Been Collected
                                    </p>
                                </div>
                                <p class="px-10 font-light text-lg text-gray-700">
                                    Your blood donation has been successfully collected and is now undergoing further
                                    examination.
                                    <br><br>
                                    Thank you for your valuable contribution. If you have any questions, please contact
                                    our
                                    support team.
                                </p>
                            @endif

                            @if ($donorData->status_id == 6)
                                <div class="flex flex-col items-center text-center">
                                    <img src="{{ asset('images/screnning.webp') }}" alt="Screening"
                                        class="w-72 h-72">
                                    <p class="text-indigo-600 font-semibold mt-4 text-xl">Screening & Process</p>
                                </div>
                                <p class="px-10 font-light text-lg text-gray-700">
                                    Your donated blood is currently undergoing a screening process to ensure it meets
                                    all
                                    health and safety standards.
                                    <br><br>
                                    This is a crucial step before your blood can be used for those in need. Thank you
                                    for
                                    your patience and generosity!
                                </p>
                            @endif

                            @if ($donorData->status_id == 7)
                                <div class="flex flex-col items-center text-center">
                                    <img src="{{ asset('images/reject_blood.webp') }}" alt="Rejected Blood"
                                        class="w-72 h-72">
                                    <p class="text-red-600 font-semibold mt-4 text-xl">Blood Rejected</p>
                                </div>
                                <p class="px-10 font-light text-lg text-gray-700">
                                    Unfortunately, after further screening and testing, your donated blood does not meet
                                    the
                                    required standards.
                                    <br><br>
                                    We appreciate your willingness to donate and encourage you to try again in the
                                    future.
                                    If you have any
                                    questions, please reach out to our team.
                                </p>
                            @endif


                            @if ($donorData->status_id == 8)
                                <div class="flex flex-col items-center text-center">
                                    <img src="{{ asset('images/completed.webp') }}" alt="Completed"
                                        class="w-72 h-72">
                                    <p class="text-green-600 font-semibold mt-4 text-xl">Donation Completed</p>
                                </div>
                                <p class="px-10 font-light text-lg text-gray-700">
                                    Congratulations! Your blood donation has been successfully processed and is now
                                    ready to
                                    help those in need.
                                    <br><br>
                                    Thank you for your kindness and contribution. Your donation makes a real difference
                                    in
                                    saving lives!
                                </p>

                                <div x-data="{ showModal: false }" class="w-full max-w-lg mx-auto p-6">
                                    <button @click="showModal = true; document.body.classList.add('overflow-hidden')"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 transition-all text-white font-semibold rounded-md shadow-md ">
                                        Show Details
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
                                                    Donor Details</h2>
                                                <div class="flex justify-between border-b pb-2">
                                                    <span class="font-semibold text-gray-600">Name:</span>
                                                    <span class="text-gray-900">{{ $donorData->user->name }}</span>
                                                </div>
                                                <div class="flex justify-between border-b pb-2">
                                                    <span class="font-semibold text-gray-600">Blood Type:</span>
                                                    <span
                                                        class="text-gray-900">{{ "{$bloodDetails->bloodType->group}{$bloodDetails->bloodType->rhesus}" }}</span>
                                                </div>
                                                <div class="flex justify-between border-b pb-2">
                                                    <span class="font-semibold text-gray-600">Expiry Date:</span>
                                                    <span
                                                        class="text-gray-900">{{ $bloodDetails->expiry_date }}</span>
                                                </div>
                                                <div class="flex justify-between border-b pb-2">
                                                    <span class="font-semibold text-gray-600">Blood Component:</span>
                                                    <span
                                                        class="text-gray-900">{{ $bloodDetails->blood_component }}</span>
                                                </div>
                                                <div class="flex justify-between border-b pb-2">
                                                    <span class="font-semibold text-gray-600">Storage Location:</span>
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
                                                Close
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
                        Submit
                    </x-filament::button>
                </div>
            @endif
        </div>
    </div>
    <x-footer></x-footer>

</div>
