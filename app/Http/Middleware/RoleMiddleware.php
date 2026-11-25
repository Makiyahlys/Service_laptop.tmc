<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('info', 'Silakan masuk / login terlebih dahulu untuk mengakses modul ini.');
        }

        $user = Auth::user();

        // Owner dan super-admin memiliki akses penuh ke semua modul operasional
        if ($user->role === 'owner' || $user->role === 'admin') {
            return $next($request);
        }

        // Cek apakah role user saat ini tercakup dalam role yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Arahkan ke dashboard yang sesuai jika tidak memiliki wewenang
        $redirectRoute = match ($user->role) {
            'teknisi' => route('teknisi.index'),
            'kasir' => route('admin.index'),
            default => route('home'),
        };

        return redirect($redirectRoute)->with('error', 'Akses Ditolak: Anda masuk sebagai ' . $user->getRoleLabel() . ' dan tidak memiliki hak akses ke modul tersebut.');
    }
}
