<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class RedirectByRole
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user belum login, lanjutkan request
        if (!auth()->check()) {
            return $next($request);
        }

        $user        = auth()->user();
        $currentPath = $request->path();
        $panelPath   = match ($user->role) {
            'admin' => 'admin',
            'user'  => 'app',
            default => null
        };

        // Jika role tidak valid, logout dan redirect ke home
        if (!$panelPath) {
            auth()->logout();
            return redirect('/');
        }

        // Cek 3 kondisi untuk mencegah loop:
        // 1. Sudah berada di path panel yang benar
        // 2. Sedang mencoba login
        // 3. Sedang di halaman logout
        $isValidPath = str_starts_with($currentPath, $panelPath) ||
            $request->routeIs('login') ||
            $request->routeIs('logout');

        if (!$isValidPath) {
            return redirect()->to("/$panelPath");
        }

        return $next($request);
    }
}
