<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Tambahkan pengecekan role di sini:
                $user = Auth::guard($guard)->user();
                if ($user->role == 'admin') {
                    return redirect('/admin');
                } elseif (in_array($user->role, ['pengurus', 'pemilik'])) {
                    return redirect('/teknisi');
                } elseif ($user->role == 'penyewa') {
                    return redirect('/customer');
                } else {
                    return redirect('/'); // fallback jika role tidak dikenali
                }
            }
        }


        return $next($request);
    }
}
