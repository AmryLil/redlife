<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class CheckProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->isProfileComplete()) {
            return redirect()
                ->route('filament.app.pages.profile')
                ->with('error', 'Lengkapi profil terlebih dahulu!');
        }

        return $next($request);
    }
}
