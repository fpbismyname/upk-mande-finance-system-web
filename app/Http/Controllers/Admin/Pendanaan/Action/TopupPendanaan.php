<?php

namespace App\Http\Controllers\Admin\Pendanaan\Action;

use App\Http\Controllers\Controller;
use App\Services\Admin\Pendanaan\PendanaanService;
use App\Services\UI\Toast;
use Illuminate\Http\Request;

class TopupPendanaan extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, PendanaanService $pendanaan)
    {
        // Data tarik dana
        $new_entries = $request->validate([
            'amount_topup_saldo' => 'required|numeric',
            "notes_topup_saldo" => 'string'
        ]);

        // Data jumlah saldo dan catatan
        $amount_tarik_dana = $new_entries['amount_topup_saldo'];
        $notes = $new_entries['notes_topup_saldo'];

        // Kurangi saldo pendanaan
        $topup_saldo = $pendanaan->tambah_saldo($amount_tarik_dana, $notes);

        // Response ketika ada error di pendanaan
        if ($topup_saldo === false || !is_bool($topup_saldo)) {
            Toast::show($topup_saldo['message'], 'error');
            return redirect()->back();
        }

        // Response ketika topup saldo gagal
        if (!$topup_saldo) {
            Toast::show('Topup saldo gagal', 'error');
            return redirect()->back();
        }

        // Response ketika topup saldo berhasil
        Toast::show('Topup saldo berhasil', 'success');
        return redirect()->back();
    }
}
