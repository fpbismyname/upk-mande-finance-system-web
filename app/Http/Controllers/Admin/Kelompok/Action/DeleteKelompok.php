<?php

namespace App\Http\Controllers\Admin\Kelompok\Action;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Services\Admin\Kelompok\KelompokService;
use App\Services\UI\Toast;

class DeleteKelompok extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($id_kelompok, KelompokService $kelompok_service)
    {
        // Data kelompok
        $kelompok = Kelompok::findOrFail($id_kelompok);

        // Delete kelompok
        $delete_kelompok = $kelompok_service->delete_kelompok($id_kelompok);

        // Validasi delete kelompok
        if ($delete_kelompok) {
            Toast::show(__('crud.delete_success', ['item' => $kelompok->name]));
        } else {
            Toast::show(__('crud.delete_failed', ['item' => $kelompok->name]));
        }

        // Kembali ke halaman awal
        return redirect()->route('admin.kelompok.index');
    }
}
