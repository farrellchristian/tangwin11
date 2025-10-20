<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View; // <-- Import class View

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     * Kita pakai __invoke lagi agar controller ini
     * otomatis berjalan saat dipanggil oleh rute.
     */
    public function __invoke(Request $request): View // Tentukan return type-nya adalah View
    {
        // Perintahkan controller untuk me-return sebuah view.
        // 'admin.dashboard' berarti file di:
        // resources/views/admin/dashboard.blade.php
        // (File ini akan kita buat di langkah berikutnya)
        return view('admin.dashboard');
    }
}