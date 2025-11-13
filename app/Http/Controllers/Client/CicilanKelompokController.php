<?php

namespace App\Http\Controllers\Client;

use App\Enums\Admin\CatatanPendanaan\EnumCatatanPendanaan;
use App\Enums\Admin\Status\EnumStatusCicilanKelompok;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\BayarCicilanKelompokRequest;
use App\Models\CatatanPendanaan;
use App\Models\Pendanaan;
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
        Pendanaan $pendanaan_model,
        CatatanPendanaan $catatan_pendanaan_model
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
        $today = now()->format("d_M_Y-H_i_s");
        $bukti_pembayaran = $request->file('bukti_pembayaran');
        $extension_file = $bukti_pembayaran->getClientOriginalExtension();
        $name_bukti_pembayaran = urlencode("{$kelompok->formatted_name_snake_case}_cicilan_pinjaman_{$today}.{$extension_file}");
        $path = Storage::disk()->putFileAs("bukti_pembayaran", $bukti_pembayaran, $name_bukti_pembayaran);


        $datas['bukti_pembayaran'] = $path;

        $denda_cicilan = $cicilan_kelompok->denda_telat_bayar;

        $cicilan_kelompok->update([
            ...$datas,
            'status' => EnumStatusCicilanKelompok::SUDAH_BAYAR,
            'tanggal_dibayar' => now(),
            'denda_dibayar' => floatval($denda_cicilan)
        ]);

        // bayar pendanaan
        $total_nominal_bayar_cicilan = $cicilan_kelompok->nominal_cicilan + $denda_cicilan;
        $pendanaan = $pendanaan_model->first();
        $pendanaan->increment('saldo', $total_nominal_bayar_cicilan);

        if ($pendanaan->wasChanged()) {
            $catatan_telat_bayar = $denda_cicilan != 0 ? "Dengan denda telat bayar {$cicilan_kelompok->formatted_denda_telat_bayar}." : '';
            $data_catatan = [
                'jumlah_saldo' => $total_nominal_bayar_cicilan,
                'catatan' => "Bayar cicilan kelompok {$kelompok->formatted_name}. {$catatan_telat_bayar}",
                'tipe_catatan' => EnumCatatanPendanaan::INFLOW,
            ];
            $catatan_pendanaan_model->create($data_catatan);
        }

        if ($cicilan_kelompok->wasChanged()) {
            Toast::success('Cicilan berhasil dibayar.');
        } else {
            Toast::success('Cicilan gagal dibayar. Silahkan cobalagi di lain waktu.');
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
