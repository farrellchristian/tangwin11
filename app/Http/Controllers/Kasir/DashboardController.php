<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View; // <-- Import class View

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View // Tentukan return type-nya adalah View
    {
        // Perintahkan controller untuk me-return sebuah view.
        // 'kasir.dashboard' berarti file di:
        // resources/views/kasir/dashboard.blade.php
        // (File ini akan kita buat setelah ini)
        return view('kasir.dashboard');
    }
}