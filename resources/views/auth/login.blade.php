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
        class="w-full max-w-md mx-auto bg-white p-8 border border-gray-200 rounded-md shadow-lg relative top-1/2 -translate-y-1/2">

        <!-- Logo -->
        <div class="w-full flex flex-col items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 object-cover mb-2">
            <h1 class="text-xl font-semibold">Welcome back</h1>
            <p class="text-gray-500 text-sm">Please enter your details to sign in.</p>
        </div>

        <!-- Social Login Buttons -->
        <div class="flex justify-center space-x-4 mt-6">
            <button
                class="w-16 h-12 flex items-center justify-center border border-gray-300 rounded-lg shadow-sm hover:shadow-md transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#000000" class="bi bi-google" viewBox="0 0 16 16"
                    id="Google--Streamline-Bootstrap" height="18" width="18">
                    <desc>Google Streamline Icon: https://streamlinehq.com</desc>
                    <path
                        d="M15.545 6.558a9.4 9.4 0 0 1 0.139 1.626c0 2.434 -0.87 4.492 -2.384 5.885h0.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.7 7.7 0 0 1 5.352 2.082l-2.284 2.284A4.35 4.35 0 0 0 8 3.166c-2.087 0 -3.86 1.408 -4.492 3.304a4.8 4.8 0 0 0 0 3.063h0.003c0.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004 -0.276 2.722 -0.764h-0.003a3.7 3.7 0 0 0 1.599 -2.431H8v-3.08z"
                        stroke-width="1"></path>
                </svg>
            </button>

            <button
                class="w-16 h-12 flex items-center justify-center border border-gray-300 rounded-lg shadow-sm hover:shadow-md transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"
                    id="Instagram--Streamline-Core" height="20" width="20">
                    <desc>Instagram Streamline Icon: https://streamlinehq.com</desc>
                    <g id="instagram">
                        <path id="Subtract" fill="#000000" fill-rule="evenodd"
                            d="M3.39027 0.786621C1.95225 0.786621 0.786499 1.95237 0.786499 3.3904v6.9434c0 1.438 1.165751 2.6038 2.603771 2.6038h6.94343c1.438 0 2.6037 -1.1658 2.6037 -2.6038V3.3904c0 -1.43803 -1.1657 -2.603779 -2.6037 -2.603779H3.39027ZM11.0832 3.39417c0 0.41421 -0.3358 0.75 -0.75 0.75 -0.41422 0 -0.75001 -0.33579 -0.75001 -0.75 0 -0.41422 0.33579 -0.75 0.75001 -0.75 0.4142 0 0.75 0.33578 0.75 0.75ZM6.8621 4.78198c-1.14878 0 -2.08006 0.93128 -2.08006 2.08006S5.71332 8.9421 6.8621 8.9421s2.08006 -0.93128 2.08006 -2.08006 -0.93128 -2.08006 -2.08006 -2.08006ZM3.78204 6.86204c0 -1.70107 1.37899 -3.08006 3.08006 -3.08006s3.08006 1.37899 3.08006 3.08006S8.56317 9.9421 6.8621 9.9421 3.78204 8.56311 3.78204 6.86204Z"
                            clip-rule="evenodd" stroke-width="1"></path>
                    </g>
                </svg>
            </button>

            <button
                class="w-16 h-12 flex items-center justify-center border border-gray-300 rounded-lg shadow-sm hover:shadow-md transition">
                <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"
                    id="Github--Streamline-Unicons" height="20" width="20">
                    <desc>Github Streamline Icon: https://streamlinehq.com</desc>
                    <path
                        d="M7.999933333333333 0.35346666666666665c-1.8616000000000001 0.00006666666666666667 -3.662466666666666 0.6626 -5.080266666666667 1.8689333333333331C1.5017999999999998 3.4287333333333327 0.5594666666666666 5.100266666666666 0.26126666666666665 6.937866666666666c-0.29819999999999997 1.8375333333333332 0.0672 3.7212666666666667 1.0308 5.3141333333333325 0.9635333333333334 1.5927333333333333 2.462466666666667 2.790733333333333 4.228533333333333 3.3795333333333333 0.39199999999999996 0.0686 0.5389999999999999 -0.1666 0.5389999999999999 -0.37239999999999995 0 -0.18619999999999998 -0.0098 -0.8036 -0.0098 -1.4602 -1.9697999999999998 0.36260000000000003 -2.4794 -0.4801333333333333 -2.6361999999999997 -0.9211333333333332 -0.174 -0.4288666666666666 -0.4498 -0.8089333333333333 -0.8036 -1.1074 -0.2744 -0.147 -0.6664 -0.5095999999999999 -0.0098 -0.5194 0.2507333333333333 0.027200000000000002 0.4911333333333333 0.11446666666666666 0.7010000000000001 0.2543333333333333 0.2098 0.13986666666666664 0.3828666666666667 0.3283333333333333 0.5044 0.5492666666666666 0.10719999999999999 0.1926 0.25139999999999996 0.36219999999999997 0.4242666666666666 0.499 0.17286666666666664 0.13679999999999998 0.37106666666666666 0.2382 0.5831999999999999 0.29819999999999997 0.2121333333333333 0.060066666666666664 0.434 0.07773333333333332 0.6529333333333334 0.05193333333333333s0.43066666666666664 -0.09453333333333333 0.6229333333333333 -0.2023333333333333c0.03393333333333333 -0.39859999999999995 0.2116 -0.7712666666666667 0.4998666666666667 -1.0486 -1.7444 -0.19599999999999998 -3.5671999999999997 -0.8722 -3.5671999999999997 -3.8709999999999996 -0.011 -0.7791333333333333 0.27646666666666664 -1.533 0.8036 -2.106933333333333 -0.23966666666666664 -0.6772 -0.21166666666666667 -1.4203999999999999 0.0784 -2.0776 0 0 0.6565333333333333 -0.20579999999999998 2.1559999999999997 0.8036 1.2828 -0.3528 2.6370666666666667 -0.3528 3.9199333333333333 0 1.4993999999999998 -1.0191999999999999 2.1559999999999997 -0.8036 2.1559999999999997 -0.8036 0.29006666666666664 0.6572 0.3181333333333333 1.4003999999999999 0.0784 2.0776 0.5286 0.5729333333333333 0.8164666666666666 1.3275333333333332 0.8036 2.106933333333333 0 3.0086 -1.8325999999999998 3.675 -3.577 3.8709999999999996 0.18706666666666666 0.1896 0.3311333333333333 0.41733333333333333 0.42246666666666666 0.6675333333333333 0.09133333333333334 0.2503333333333333 0.1277333333333333 0.5173333333333333 0.10673333333333332 0.7828666666666666 0 1.0486666666666666 -0.0098 1.8913333333333333 -0.0098 2.1559333333333335 0 0.20579999999999998 0.147 0.4508 0.5389999999999999 0.37239999999999995 1.7629333333333332 -0.5935333333333332 3.2575333333333334 -1.7943333333333333 4.217 -3.3879333333333332 0.9595333333333333 -1.5936666666666666 1.3214 -3.476466666666666 1.0211333333333332 -5.312266666666666 -0.3002666666666667 -1.8357333333333332 -1.2431333333333332 -3.5050666666666666 -2.6604 -4.709933333333333C11.659466666666667 1.0164666666666666 9.860133333333334 0.3544666666666666 7.999933333333333 0.35346666666666665Z"
                        fill="#000000" stroke-width="0.6667"></path>
                </svg>
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
                    <a href="{{ route('password.request') }}" class="text-red-500 hover:underline">Forgot
                        password?</a>
                @endif
            </div>

            <!-- Sign In Button -->
            <button type="submit"
                class="w-full bg-black text-white py-3 rounded-lg hover:bg-gray-800 transition duration-200 shadow-md">
                Sign in
            </button>

            <!-- Sign Up Link -->
            <p class="text-center text-sm text-gray-600 mt-4">
                Don't have an account yet? <a href="/register" class="text-red-500 font-semibold hover:underline">Sign
                    Up</a>
            </p>
        </form>
    </div>



</body>

</html>
