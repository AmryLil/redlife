<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class=" h-screen w-full relative">
    <div class="absolute inset-0 w-full h-full overflow-hidden">
        <div class=" absolute top-0 w-full">
            <div class="w-screen bg-red-700 h-28"></div>
            <svg class="w-full h-auto " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="rgb(185 28 28 / var(--tw-bg-opacity, 1))" fill-opacity="1"
                    d="M0,192L24,186.7C48,181,96,171,144,138.7C192,107,240,53,288,58.7C336,64,384,128,432,133.3C480,139,528,85,576,96C624,107,672,181,720,192C768,203,816,149,864,160C912,171,960,245,1008,282.7C1056,320,1104,320,1152,288C1200,256,1248,192,1296,170.7C1344,149,1392,171,1416,181.3L1440,192L1440,0L1416,0C1392,0,1344,0,1296,0C1248,0,1200,0,1152,0C1104,0,1056,0,1008,0C960,0,912,0,864,0C816,0,768,0,720,0C672,0,624,0,576,0C528,0,480,0,432,0C384,0,336,0,288,0C240,0,192,0,144,0C96,0,48,0,24,0L0,0Z">
                </path>
            </svg>
        </div>
        <div class="absolute top-10 w-full">
            <div class="w-screen bg-red-700 h-10"></div>
            <svg class="w-full h-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="rgb(185 28 28 / var(--tw-bg-opacity, 1))" fill-opacity="0.6"
                    d="M0,64L24,106.7C48,149,96,235,144,272C192,309,240,299,288,277.3C336,256,384,224,432,197.3C480,171,528,149,576,149.3C624,149,672,171,720,186.7C768,203,816,213,864,197.3C912,181,960,139,1008,112C1056,85,1104,75,1152,90.7C1200,107,1248,149,1296,186.7C1344,224,1392,256,1416,272L1440,288L1440,0L1416,0C1392,0,1344,0,1296,0C1248,0,1200,0,1152,0C1104,0,1056,0,1008,0C960,0,912,0,864,0C816,0,768,0,720,0C672,0,624,0,576,0C528,0,480,0,432,0C384,0,336,0,288,0C240,0,192,0,144,0C96,0,48,0,24,0L0,0Z">
                </path>
            </svg>
        </div>
        <div class="absolute top-16 w-full">
            <div class="w-screen bg-red-700 h-20"></div>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="rgb(185 28 28 / var(--tw-bg-opacity, 1))" fill-opacity="0.25"
                    d="M0,128L24,160C48,192,96,256,144,234.7C192,213,240,107,288,106.7C336,107,384,213,432,256C480,299,528,277,576,245.3C624,213,672,171,720,144C768,117,816,107,864,112C912,117,960,139,1008,149.3C1056,160,1104,160,1152,170.7C1200,181,1248,203,1296,192C1344,181,1392,139,1416,117.3L1440,96L1440,0L1416,0C1392,0,1344,0,1296,0C1248,0,1200,0,1152,0C1104,0,1056,0,1008,0C960,0,912,0,864,0C816,0,768,0,720,0C672,0,624,0,576,0C528,0,480,0,432,0C384,0,336,0,288,0C240,0,192,0,144,0C96,0,48,0,24,0L0,0Z">
                </path>
            </svg>
        </div>

    </div>

    <div
        class="w-full max-w-md absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-8  border border-slate-200 transform transition-all duration-300 rounded-md">


        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 p-3 rounded-md shadow-md">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>⚠ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="mb-4 bg-green-100 text-green-700 p-3 rounded-md shadow-md">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block font-medium text-gray-700" for="email">Email</label>
                <input type="email" id="email" name="email"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none shadow-sm"
                    value="{{ old('email') }}" required autofocus>
            </div>

            <div>
                <label class="block font-medium text-gray-700" for="password">Password</label>
                <input type="password" id="password" name="password"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none shadow-sm"
                    required>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-sm text-gray-600">Remember Me</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-red-500 hover:underline">Forgot
                        Password?</a>
                @endif
            </div>

            <button type="submit"
                class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition duration-200 shadow-md">
                Log in
            </button>
        </form>
    </div>
</body>

</html>
