<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminSession
{
    public function handle(Request $request, Closure $next): Response
    {
        // Belum login atau sesi sudah berakhir: kembali ke halaman login
        if (! $request->session()->get('admin_login')) {
            return redirect()->guest(route('login'));
        }

        $response = $next($request);

        // Cegah tombol Back menampilkan halaman lama dari cache setelah logout
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');

        return $response;
    }
}