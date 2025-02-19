    <nav class="bg-slate-50 p-4 text-white flex justify-between items-center px-44">
        <h1 class="text-lg font-bold text-red-600">RedLife</h1>
        <ul class="flex space-x-4 ">
            <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                Home
            </x-nav-link>
            <x-nav-link href="{{ route('about') }}" :active="request()->routeIs('#')">
                Donate
            </x-nav-link>
            <x-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">
                Event
            </x-nav-link>
            <x-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">
                About
            </x-nav-link>
            <x-nav-link href="{{ route('contact') }}" :active="request()->routeIs('contact')">
                Contact
            </x-nav-link>

        </ul>
        <ul class="flex space-x-4 ">
            @guest
                <x-button class="bg-red-600 rounded-2xl">
                    <a href="{{ route('register') }}" class="flex gap-2">
                        <span>Register</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f8fafc"
                            id="Heart--Streamline-Tabler-Filled" height="16" width="16">
                            <desc>Heart Streamline Icon: https://streamlinehq.com</desc>
                            <path
                                d="M4.652666666666667 2.0493333333333332a4 4 0 0 1 3.3253333333333335 0.95l0.024666666666666663 0.022 0.02266666666666667 -0.019999999999999997a4 4 0 0 1 3.155333333333333 -0.96l0.16399999999999998 0.023999999999999997a4 4 0 0 1 2.2426666666666666 6.671999999999999l-0.12 0.12333333333333332 -0.032 0.027333333333333334 -4.966666666666667 4.9193333333333324a0.6666666666666666 0.6666666666666666 0 0 1 -0.8753333333333333 0.05466666666666667l-0.06266666666666666 -0.05466666666666667 -4.995333333333333 -4.9479999999999995A4 4 0 0 1 4.652666666666667 2.0493333333333332z"
                                stroke-width="0.6667"></path>
                        </svg>
                    </a>
                </x-button>
            @else
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-white">
                        Logout
                    </button>
                </form>
            @endguest
        </ul>
    </nav>
