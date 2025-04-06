@php
    $isLoggedIn = auth()->check();
@endphp

<div class="flex items-center gap-x-4">


    @if ($isLoggedIn)
    @else
        <div class="flex gap-4 justify-center items-center">
            <a href="/login" class="fi-btn">
                <button>Login</button>
            </a>
            <a href="/login" class="fi-btn">
                <button class="bg-red-600 font-semibold rounded-md shadow-sm text-slate-50 px-5 py-1.5">Register</button>
            </a>
        </div>
    @endif
</div>
