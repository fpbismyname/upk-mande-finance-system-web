<?php

namespace App\Http\Controllers\Admin\Kelompok\Action;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Services\Admin\Kelompok\KelompokService;
use App\Services\UI\Toast;
use Illuminate\Http\Request;

class UpdateKelompok extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id_kelompok, Kelompok $kelompok_model, KelompokService $kelompok_service)
    {
        // Data kelompok
        $kelompok = $kelompok_model->findOrFail($id_kelompok);

        // Validasi pembaruan data kelompok
        $request->validate([
            'limit_pinjaman' => 'required|numeric|min:1000000',
            'ketua_id' => 'required',
            'status_id' => 'required'
        ]);

        // Data kelompok yang baru untuk diupdate
        $data_kelompok = $request->only($kelompok_model->getFillable());

        // Update kelompok
        $update_kelompok = $kelompok_service->update_kelompok($data_kelompok, $id_kelompok);

        // Validasi update kelompok
        if ($update_kelompok) {
            Toast::show(__('crud.update_success', ['item' => $kelompok->name]));
        } else {
            Toast::show(__('crud.update_failed', ['item' => $kelompok->name]));
        }

        // Kembali kehalaman awal
        return redirect()->route('admin.kelompok.index');
    }
}
