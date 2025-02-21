<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;  // Pastikan import model User
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;  // Import Hash facade
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Custom authentication logic untuk cek role
        Fortify::authenticateUsing(function (Request $request) {
            $username = Fortify::username();
            $user     = User::where($username, $request->$username)->first();

            // Cek apakah user ada dan password cocok
            if ($user && Hash::check($request->password, $user->password)) {
                // Contoh pengecekan role: sesuaikan dengan struktur role di aplikasi Anda
                // Jika menggunakan kolom 'role' di tabel users
                if (in_array($user->role, ['admin', 'user'])) {
                    return $user;
                }
                // Jika menggunakan package spatie/laravel-permission:
                // if ($user->hasRole(['admin', 'user'])) {
                //     return $user;
                // }
            }

            return null;  // Tidak mengizinkan login jika role tidak sesuai
        });

        // Custom redirect setelah login berdasarkan role
        

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
