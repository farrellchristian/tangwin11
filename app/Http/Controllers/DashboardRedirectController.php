<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Import facade Auth

class DashboardRedirectController extends Controller
{
    /**
     * Handle the incoming request.
     * Ini adalah "invokable" method, yang otomatis berjalan
     * saat controller ini dipanggil.
     */
    public function __invoke(Request $request)
    {
        // Cek role user yang sedang login
        if (Auth::user()->role === 'admin') {
            
            // Jika 'admin', alihkan ke rute '/admin/dashboard'
            // (Rute ini akan kita buat setelah ini)
            return redirect('/admin/dashboard');

        } elseif (Auth::user()->role === 'kasir') {
            
            // Jika 'kasir', alihkan ke rute '/kasir/dashboard'
            // (Rute ini juga akan kita buat setelah ini)
            return redirect('/kasir/dashboard');

        } else {
            // Jika ada role aneh (seharusnya tidak), paksa logout
            Auth::logout();
            return redirect('/login')->with('error', 'Role tidak valid.');
        }
    }
}