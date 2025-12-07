<?php

namespace App\Http\Controllers\Client;

use App\Enums\Admin\CatatanPendanaan\EnumCatatanPendanaan;
use App\Enums\Admin\Rekening\EnumTipeTransaksi;
use App\Enums\Admin\Status\EnumStatusCicilanKelompok;
use App\Http\Controllers\Controller;
use App\Enums\Admin\Rekening\EnumRekening;
use App\Http\Requests\Client\BayarCicilanKelompokRequest;
use App\Models\Rekening;
use App\Models\TransaksiRekening;
use App\Services\UI\Toast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CicilanKelompokController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_pinjaman, string $id_cicilan)
    {
        $pinjaman_kelompok = auth()->user()
            ->kelompok
            ->pinjaman_kelompok()
            ->with(['cicilan_kelompok'])
            ->findOrFail($id_pinjaman);
        $cicilan_kelompok = $pinjaman_kelompok->cicilan_kelompok()->findOrFail($id_cicilan);
        $payload = compact('id_pinjaman', 'pinjaman_kelompok', 'cicilan_kelompok');
        return view('client.cicilan-kelompok.edit', $payload);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        BayarCicilanKelompokRequest $request,
        string $id_pinjaman,
        string $id_cicilan,
        Rekening $rekening_model,
    ) {
        $datas = $request->validated();
        $kelompok = auth()->user()->kelompok;
        $pinjaman_kelompok = auth()->user()
            ->kelompok
            ->pinjaman_kelompok()
            ->with(['cicilan_kelompok'])
            ->findOrFail($id_pinjaman);
        $cicilan_kelompok = $pinjaman_kelompok->cicilan_kelompok()->findOrFail($id_cicilan);

        // simpan file bukti pembayaran
        $storage_private = Storage::disk('local');
        $today = now()->format("d_M_Y-H_i_s");
        $bukti_pembayaran = $request->file('bukti_pembayaran');
        $extension_file = $bukti_pembayaran->getClientOriginalExtension();
        $name_bukti_pembayaran = urlencode("{$kelompok->formatted_name_snake_case}_cicilan_pinjaman_{$today}.{$extension_file}");
        $path = $storage_private->putFileAs("bukti_pembayaran", $bukti_pembayaran, $name_bukti_pembayaran);


        $datas['bukti_pembayaran'] = $path;

        $cicilan_kelompok->update([
            ...$datas,
            'status' => EnumStatusCicilanKelompok::SUDAH_BAYAR,
            'tanggal_dibayar' => now()
        ]);

        // bayar pendanaan
        $rekening_akuntan = $rekening_model->get_rekening_akuntan()->first();
        $total_nominal_bayar_cicilan = $cicilan_kelompok->nominal_cicilan;
        $rekening_akuntan->increment('saldo', $total_nominal_bayar_cicilan);
        if ($rekening_akuntan->wasChanged()) {
            $data_transaksi = [
                'nominal' => $total_nominal_bayar_cicilan,
                'catatan' => "Bayar cicilan kelompok {$kelompok->formatted_name}.",
                'keterangan' => "Pembayaran cicilan pinjaman kelompok {$kelompok->formatted_name} ke rekening akuntan.",
                'tipe_transaksi' => EnumTipeTransaksi::MASUK,
            ];
            $rekening_akuntan->transaksi_rekening()->create($data_transaksi);
        }

        if ($cicilan_kelompok->wasChanged()) {
            Toast::success('Bukti pembayaran berhasil dikirim.');
        } else {
            Toast::success('Bukti pembayaran gagal dikirim. Silahkan cobalagi di lain waktu.');
        }
        return redirect()->route('client.pinjaman-kelompok.show', [$id_pinjaman]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
