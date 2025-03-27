<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600&family=Tektur:wght@400..900&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap"
        rel="stylesheet">
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
        class="w-full max-w-md mx-auto bg-white p-8 border border-gray-200 rounded-2xl shadow-lg relative top-1/2 -translate-y-1/2">

        <!-- Logo -->
        <div class="w-full flex flex-col items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-20 h-20 object-cover mb-2">
            <h1 class="text-2xl font-semibold">Welcome back</h1>
            <p class="text-gray-500 text-sm">Please enter your details to sign in.</p>
        </div>

        <!-- Social Login Buttons -->
        <div class="flex justify-center space-x-4 mt-6">
            <button
                class="w-16 h-12 flex items-center justify-center border border-gray-300 rounded-lg shadow-sm hover:shadow-md transition">
                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/apple/apple-original.svg" class="w-5 h-5"
                    alt="Apple">
            </button>

            <button
                class="w-16 h-12 flex items-center justify-center border border-gray-300 rounded-lg shadow-sm hover:shadow-md transition">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" class="w-5 h-5"
                    alt="Google">
            </button>

            <button
                class="w-16 h-12 flex items-center justify-center border border-gray-300 rounded-lg shadow-sm hover:shadow-md transition">
                <img src="https://upload.wikimedia.org/wikipedia/en/6/60/Twitter_Logo_as_of_2021.svg" class="w-5 h-5"
                    alt="Twitter">
            </button>
        </div>

        <!-- Separator -->
        <div class="flex items-center my-6">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="mx-3 text-gray-500 text-sm">OR</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700" for="email">E-Mail Address</label>
                <input type="email" id="email" name="email"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none shadow-sm"
                    placeholder="Enter your email..." required>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700" for="password">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none shadow-sm"
                        placeholder="•••••••••••••" required>
                    <svg class="absolute top-3.5 right-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" id="Eye--Streamline-Guidance-Free" height="24" width="24">
                        <desc>Eye Streamline Icon: https://streamlinehq.com</desc>
                        <path stroke="#000000"
                            d="M6.106 7.053a8.079 8.079 0 0 1 11.788 0L20.5 10c1 1 2.224 2 3.5 2 -1.276 0 -2.5 1 -3.5 2l-2.606 2.947a8.079 8.079 0 0 1 -11.788 0L3.5 14c-1 -1 -2.224 -2 -3.5 -2 1.276 0 2.5 -1 3.5 -2l2.606 -2.947Z"
                            stroke-width="1"></path>
                        <path stroke="#000000" d="M9.5 12a2.5 2.5 0 1 1 5 0 2.5 2.5 0 0 1 -5 0Z" stroke-width="1">
                        </path>
                    </svg>
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between text-sm text-gray-600">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span>Remember me</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-red-500 hover:underline">Forgot password?</a>
                @endif
            </div>

            <!-- Sign In Button -->
            <button type="submit"
                class="w-full bg-black text-white py-3 rounded-lg hover:bg-gray-800 transition duration-200 shadow-md">
                Sign in
            </button>

            <!-- Sign Up Link -->
            <p class="text-center text-sm text-gray-600 mt-4">
                Don't have an account yet? <a href="#" class="text-red-500 font-semibold hover:underline">Sign
                    Up</a>
            </p>
        </form>
    </div>



</body>

</html>
