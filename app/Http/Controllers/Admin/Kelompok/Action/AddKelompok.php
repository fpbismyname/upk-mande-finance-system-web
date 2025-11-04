<?php

namespace App\Http\Controllers\Admin\Kelompok\Action;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Services\Admin\Kelompok\KelompokService;
use App\Services\UI\Toast;
use Illuminate\Http\Request;

class AddKelompok extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Kelompok $kelompok_model, KelompokService $kelompok_service)
    {
        // Validate entries data add kelompok
        $request->validate([
            'name' => 'required',
            'limit_pinjaman' => 'required|numeric|min:1000000',
            'users_id' => 'required',
            'status' => 'required'
        ]);
        // Data kelompok
        $data_kelompok = $request->only($kelompok_model->getFillable());

        // Add new kelompok
        $add_kelompok = $kelompok_service->add_kelompok($data_kelompok);

        // Validate add new kelompok
        if ($add_kelompok) {
            Toast::show(__('crud.create_success', ['item' => 'Kelompok']));
        } else {
            Toast::show(__('crud.create_failed', ['item' => 'kelompok']));
        }

        return redirect()->route('admin.kelompok.index');
    }
}
