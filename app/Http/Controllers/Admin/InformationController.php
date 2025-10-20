<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View; // Import class View

class InformationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        // Tampilkan view 'admin.information.index'
        // Lokasinya: resources/views/admin/information/index.blade.php
        return view('admin.information.index');
    }
}