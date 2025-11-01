<?php

namespace App\Http\Controllers\Admin\Pendanaan\Action;

use App\Http\Controllers\Controller;
use App\Services\Admin\Pendanaan\PendanaanService;
use App\Services\UI\Toast;
use Illuminate\Http\Request;

class TarikSaldoPendanaan extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, PendanaanService $pendanaan)
    {
        // Data tarik dana
        $new_entries = $request->validate([
            'amount_tarik_saldo' => 'required|numeric',
            'notes_tarik_saldo' => 'string',
        ]);

        // Data jumlah saldo dan catatan
        $amount_tarik_saldo = $new_entries['amount_tarik_saldo'];
        $notes = $new_entries['notes_tarik_saldo'];

        // Kurangi saldo pendanaan
        $tarik_saldo = $pendanaan->kurangi_saldo($amount_tarik_saldo, $notes);

        // Response ketika ada error di pendanaan
        if ($tarik_saldo === false || !is_bool($tarik_saldo)) {
            Toast::show($tarik_saldo['message'], 'error');
            return redirect()->back();
        }

        // Response ketika tarik saldo gagal
        if (!$tarik_saldo) {
            Toast::show('Tarik saldo saldo gagal', 'error');
            return redirect()->back();
        }

        // Response ketika tarik saldo berhasil
        Toast::show('Tarik saldo berhasil', 'success');
        return redirect()->back();
    }
}
