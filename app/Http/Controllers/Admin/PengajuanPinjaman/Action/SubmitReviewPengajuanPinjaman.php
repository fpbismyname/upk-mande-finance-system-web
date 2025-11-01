<?php

namespace App\Http\Controllers\Admin\PengajuanPinjaman\Action;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPinjaman;
use App\Services\Admin\PengajuanPinjaman\PengajuanPinjamanService;
use App\Services\UI\Toast;
use Illuminate\Http\Request;

class SubmitReviewPengajuanPinjaman extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id, PengajuanPinjaman $pengajuan_pinjaman_model, PengajuanPinjamanService $pengajuan_pinjaman_service)
    {
        // Data pengajuan pinjaman
        $data_pengajuan_pinjaman = $pengajuan_pinjaman_model->findOrFail($id);
        // Validate entries
        $request->validate([
            'status_id' => 'required',
            'catatan' => ''
        ]);
        // Data review pengajuan
        $data_review = $request->only($pengajuan_pinjaman_model->getFillable());
        // Submit review
        $submit_review = $pengajuan_pinjaman_service->submit_review($id, $data_review);
        // Validasi review
        if ($submit_review) {
            Toast::show(__('crud.update_success', ["item" => $data_pengajuan_pinjaman->kelompok_name]));
        } else {
            Toast::show(__('crud.update_failed', ["item" => $data_pengajuan_pinjaman->kelompok_name]));
        }
        return redirect()->route('admin.pengajuan-pinjaman.review', [$data_pengajuan_pinjaman->id]);
    }
}
