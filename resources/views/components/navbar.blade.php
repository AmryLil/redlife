<nav class="bg-blue-600 p-4 text-white flex justify-between items-center">
    <h1 class="text-lg font-bold">MyApp</h1>
    <ul class="flex space-x-4">
        <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
            Home
        </x-nav-link>
        <x-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">
            About
        </x-nav-link>
        <x-nav-link href="{{ route('contact') }}" :active="request()->routeIs('contact')">
            Contact
        </x-nav-link>
    </ul>
</nav>
