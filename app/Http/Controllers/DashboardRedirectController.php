<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardRedirectController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        // 1. Jika role ADMIN -> Lempar ke Dashboard Admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } 
        
        // 2. Jika role KASIR -> Lempar ke Dashboard Kasir
        if ($user->role === 'kasir') {
            return redirect()->route('kasir.dashboard');
        }

        // 3. Jika role tidak jelas -> Tendang keluar (Logout)
        Auth::logout();
        return redirect()->route('login')->with('error', 'Role akun tidak valid.');
    }
}