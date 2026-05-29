<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    /**
     * Menampilkan halaman pengaturan pengumuman.
     */
    public function index(): View
    {
        $announcement = Announcement::first() ?? new Announcement([
            'title' => '',
            'content' => '',
            'image' => null,
            'is_active' => false,
        ]);

        return view('admin.settings.announcement', compact('announcement'));
    }

    /**
     * Menyimpan/update pengumuman.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_active' => 'nullable',
        ]);

        $announcement = Announcement::first();

        $data = [
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'is_active' => $request->input('is_active') == '1',
        ];

        // Handle gambar base64
        $imageBase64 = $request->input('image_base64');
        if ($imageBase64 === 'DELETE') {
            $data['image'] = null;
        } elseif (!empty($imageBase64)) {
            // Strip data URI prefix if present
            if (preg_match('/^data:image\/(\w+);base64,/', $imageBase64)) {
                $imageBase64 = substr($imageBase64, strpos($imageBase64, ',') + 1);
            }
            $data['image'] = $imageBase64;
        }

        if ($announcement) {
            $announcement->update($data);
        } else {
            Announcement::create($data);
        }

        return redirect()->route('admin.announcement')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }
}
