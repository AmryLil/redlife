<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class RedirectByRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // Ambil role pertama menggunakan Spatie (asumsi 1 role per user)
        $role = $user->getRoleNames()->first();

        // Atau jika ingin memprioritaskan role tertentu (misal: super_admin diutamakan)
        // $role = $user->hasRole('super_admin') ? 'super_admin' : $user->getRoleNames()->first();

        $currentPath = $request->path();
        $panelPath   = match ($role) {
            'super_admin', 'admin' => 'admin',  // Gabungkan role dengan panel yang sama
            'user',                => 'app',
            default                => null
        };

        if (!$panelPath) {
            auth()->logout();
            return redirect('/')->withErrors(['role' => 'Unauthorized access role']);
        }

        $isValidPath = str_starts_with($currentPath, $panelPath) ||
            $request->routeIs('login') ||
            $request->routeIs('logout');

        if (!$isValidPath) {
            return redirect("/$panelPath");
        }

        return $next($request);
    }
}
