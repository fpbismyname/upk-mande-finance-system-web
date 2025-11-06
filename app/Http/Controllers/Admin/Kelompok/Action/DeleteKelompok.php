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
        $result = $kelompok_service->delete_kelompok($id_kelompok);

        // Validasi delete kelompok
        Toast::show($result->message, $result->type_message);

        // Kembali ke halaman awal
        return redirect()->route('admin.kelompok.index');
    }
}
