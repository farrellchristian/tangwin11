<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Service;
use App\Models\Employee;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WebsiteSettingController extends Controller
{
    /**
     * Menampilkan halaman Setting Website Reservasi.
     * Menampilkan semua entitas beserta status show_on_reservation-nya.
     */
    public function index(): View
    {
        $stores = Store::orderBy('store_name')->get();
        $services = Service::with('store')->orderBy('id_store')->orderBy('service_name')->get();
        $employees = Employee::with('store')->orderBy('id_store')->orderBy('employee_name')->get();
        $paymentMethods = PaymentMethod::orderBy('method_name')->get();

        return view('admin.settings.website', compact(
            'stores',
            'services',
            'employees',
            'paymentMethods'
        ));
    }

    /**
     * Menyimpan perubahan visibilitas semua entitas sekaligus.
     */
    public function update(Request $request): RedirectResponse
    {
        // --- STORES ---
        $visibleStoreIds = $request->input('stores', []);
        Store::query()->update(['show_on_reservation' => false]);
        if (!empty($visibleStoreIds)) {
            Store::whereIn('id_store', $visibleStoreIds)->update(['show_on_reservation' => true]);
        }

        // --- SERVICES ---
        $visibleServiceIds = $request->input('services', []);
        Service::query()->update(['show_on_reservation' => false]);
        if (!empty($visibleServiceIds)) {
            Service::whereIn('id_service', $visibleServiceIds)->update(['show_on_reservation' => true]);
        }

        // --- EMPLOYEES & PHOTOS ---
        $visibleEmployeeIds = $request->input('employees', []);
        $photos = $request->file('employee_photos', []);

        // Reset visibilitas dulu
        Employee::query()->update(['show_on_reservation' => false]);

        // Proses setiap employee aktif untuk update visibilitas dan foto jika ada
        $activeEmployees = Employee::all();
        $base64Photos = $request->input('employee_photos_base64', []);
        $instagramUsernames = $request->input('instagram_usernames', []);

        foreach ($activeEmployees as $employee) {
            $updateData = [
                'show_on_reservation' => in_array($employee->id_employee, $visibleEmployeeIds),
                'instagram_username' => $instagramUsernames[$employee->id_employee] ?? null,
            ];

            if (isset($base64Photos[$employee->id_employee]) && !empty($base64Photos[$employee->id_employee])) {
                $data = $base64Photos[$employee->id_employee];

                if ($data === 'DELETE') {
                    $updateData['photo'] = null;
                } else {
                    if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                        $data = substr($data, strpos($data, ',') + 1);
                    }
                    $updateData['photo'] = $data;
                }
            } elseif (isset($photos[$employee->id_employee])) {
                $base64 = $this->resizeAndToBase64($photos[$employee->id_employee]);
                if ($base64) {
                    $updateData['photo'] = $base64;
                }
            }

            $employee->update($updateData);
        }
        $visiblePaymentIds = $request->input('payment_methods', []);
        PaymentMethod::query()->update(['show_on_reservation' => false]);
        if (!empty($visiblePaymentIds)) {
            PaymentMethod::whereIn('id_payment_method', $visiblePaymentIds)->update(['show_on_reservation' => true]);
        }

        return redirect()->route('admin.website-setting')
            ->with('success', 'Pengaturan web reservasi berhasil diperbarui.');
    }

    private function resizeAndToBase64($file, $maxWidth = 300)
    {
        try {
            $imagePath = $file->getRealPath();
            list($width, $height, $type) = getimagesize($imagePath);

            $newWidth = $width;
            $newHeight = $height;

            if ($width > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = floor($height * ($maxWidth / $width));
            }

            $src = null;
            switch ($type) {
                case IMAGETYPE_JPEG:
                    $src = imagecreatefromjpeg($imagePath);
                    break;
                case IMAGETYPE_PNG:
                    $src = imagecreatefrompng($imagePath);
                    break;
                case IMAGETYPE_GIF:
                    $src = imagecreatefromgif($imagePath);
                    break;
                case IMAGETYPE_WEBP:
                    $src = imagecreatefromwebp($imagePath);
                    break;
                default:
                    return null;
            }

            $dst = imagecreatetruecolor($newWidth, $newHeight);

            // Handle transparansi untuk PNG dan WEBP
            if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_WEBP) {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            }

            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            ob_start();
            switch ($type) {
                case IMAGETYPE_JPEG:
                    imagejpeg($dst, null, 80);
                    break;
                case IMAGETYPE_PNG:
                    imagepng($dst);
                    break;
                case IMAGETYPE_GIF:
                    imagegif($dst);
                    break;
                case IMAGETYPE_WEBP:
                    imagewebp($dst);
                    break;
            }
            $data = ob_get_clean();

            imagedestroy($src);
            imagedestroy($dst);

            return base64_encode($data);
        } catch (\Exception $e) {
            return null;
        }
    }
}
