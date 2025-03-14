<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $title ?? 'Page Title' }}</title>
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
@vite(['resources/css/app.css', 'resources/js/app.js'])


<div class="bg-gray-100">
    <!-- Hero Section -->
    <section class="relative w-full h-96 bg-red-600 flex flex-col justify-center items-center text-white text-center">
        <h1 class="text-5xl font-bold">Tentang Kami</h1>
        <p class="mt-4 text-lg max-w-2xl">Kami hadir untuk menyelamatkan nyawa melalui donasi darah yang mudah dan aman.
        </p>
    </section>

    <!-- Visi & Misi -->
    <section class="container mx-auto py-16 px-6 text-center">
        <h2 class="text-3xl font-bold text-gray-800">Misi Kami</h2>
        <p class="text-gray-600 mt-4 max-w-3xl mx-auto">Menyediakan platform digital yang memudahkan proses donasi
            darah, meningkatkan kesadaran masyarakat, dan membantu mereka yang membutuhkan darah secara cepat dan
            efisien.</p>
    </section>

    <!-- Statistik -->
    <section class="bg-white py-16">
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div>
                <h3 class="text-5xl font-bold text-red-600">10.000+</h3>
                <p class="text-gray-600 mt-2">Pendonor Terdaftar</p>
            </div>
            <div>
                <h3 class="text-5xl font-bold text-red-600">50.000+</h3>
                <p class="text-gray-600 mt-2">Kantong Darah Disalurkan</p>
            </div>
            <div>
                <h3 class="text-5xl font-bold text-red-600">5.000+</h3>
                <p class="text-gray-600 mt-2">Penerima Darah Tertolong</p>
            </div>
        </div>
    </section>

    <!-- Tim Kami -->
    <section class="container mx-auto py-16 px-6 text-center">
        <h2 class="text-3xl font-bold text-gray-800">Tim Kami</h2>
        <p class="text-gray-600 mt-4 max-w-3xl mx-auto">Kami adalah tim profesional yang berdedikasi untuk memudahkan
            proses donasi darah.</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
            <div class="bg-white p-6 shadow-lg rounded-lg">
                <img src="https://via.placeholder.com/150" alt="" class="w-24 h-24 rounded-full mx-auto">
                <h3 class="mt-4 text-xl font-semibold">Dina Rahma</h3>
                <p class="text-gray-600">CEO & Founder</p>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg">
                <img src="https://via.placeholder.com/150" alt="" class="w-24 h-24 rounded-full mx-auto">
                <h3 class="mt-4 text-xl font-semibold">Rizky Pratama</h3>
                <p class="text-gray-600">CTO & Developer</p>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg">
                <img src="https://via.placeholder.com/150" alt="" class="w-24 h-24 rounded-full mx-auto">
                <h3 class="mt-4 text-xl font-semibold">Nadia Putri</h3>
                <p class="text-gray-600">Marketing & Outreach</p>
            </div>
        </div>
    </section>
</div>
