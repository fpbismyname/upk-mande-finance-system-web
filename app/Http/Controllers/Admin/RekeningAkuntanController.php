<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Admin\Rekening\EnumTipeTransaksi;
use App\Enums\Table\PaginateSize;
use App\Exports\Admin\TransaksiRekeningExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Rekening\DepositRequest;
use App\Http\Requests\Admin\Rekening\TransferRequest;
use App\Models\Rekening;
use App\Models\TransaksiRekening;
use App\Services\UI\Toast;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class RekeningAkuntanController extends Controller
{
    protected $max_nominal = 900000000000000;

    // Check saldo
    public function check_saldo($current_saldo, $target_saldo, $message)
    {
        if ($current_saldo < $target_saldo) {
            Toast::info($message);
            return redirect()->route('admin.rekening-akuntan.index');
        }
    }
    // Views rekening akuntan
    public function index(Request $request, TransaksiRekening $transaksi_rekening_model, Rekening $rekening_model)
    {
        // Get search query
        $filters = $request->only('search', 'tipe_transaksi', 'created_at');

        // Data model
        $rekening_pendanaan = $rekening_model->get_rekening_akuntan()->first();

        // Query model
        $query_transaksi = $transaksi_rekening_model->with(['rekening']);

        // Search filters
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                match ($key) {
                    'search' => $query_transaksi->search($value),
                    'created_at' => $query_transaksi->search_by_date($key, $value),
                    default => $query_transaksi->search_by_column($key, $value),
                };

            }
        }

        // Datas for passing.
        $data_rekening = $rekening_pendanaan;
        $data_transaksi_rekening = $query_transaksi->latest()->paginate(PaginateSize::SMALL->value)->withQueryString();
        $list_tipe_transaksi = EnumTipeTransaksi::options();

        // Payload untuk dipassing ke view
        $payload = compact('data_rekening', 'data_transaksi_rekening', 'list_tipe_transaksi');

        // kembalikan view list user
        return view('admin.rekening.akuntan.index', $payload);
    }
    // View deposit pages
    public function deposit(Rekening $rekening_model)
    {
        $data_rekening = $rekening_model->get_rekening_akuntan()->first();
        $payload = compact('data_rekening');
        return view('admin.rekening.akuntan.deposit', $payload);
    }
    // Action submit deposit
    public function submit_deposit(DepositRequest $request, Rekening $rekening_model)
    {
        $data_deposit = $request->validated();

        $dana_deposit = $data_deposit['nominal_deposit'] ?? 0;
        $keterangan_deposit = $data_deposit['keterangan'] ?? "-";

        // Check nominal deposit
        if ($dana_deposit > $this->max_nominal) {
            Toast::info('Nominal deposit anda terlalu besar. Nominal maksimal deposit Rp 900.000.000.000.000');
            return redirect()->back();
        }

        // Rekening akuntan
        $rekening_akuntan = $rekening_model->get_rekening_akuntan()->first();

        // Catat transaksi
        $catat_transaksi = $rekening_akuntan->transaksi_rekening()->create([
            'nominal' => $dana_deposit,
            'tipe_transaksi' => EnumTipeTransaksi::MASUK,
            'keterangan' => $keterangan_deposit
        ]);

        // Validasi transaksi   
        if ($catat_transaksi->wasRecentlyCreated) {
            Toast::success("Deposit sebesar {$catat_transaksi->formatted_nominal} berhasil");
            // Tambahkan dana deposit ke rekening
            $rekening_akuntan->increment('saldo', $dana_deposit);
            $rekening_akuntan->save();
        } else {
            Toast::error("Deposit sebesar {$catat_transaksi->formatted_nominal} gagal");
        }

        return redirect()->route('admin.rekening.akuntan.index');

    }
    // View transfer
    public function transfer(Rekening $rekening_model)
    {
        $data_rekening = $rekening_model->get_rekening_akuntan()->first();
        $payload = compact('data_rekening');
        return view('admin.rekening.akuntan.transfer', $payload);
    }
    // Action submit transfer
    public function submit_transfer(TransferRequest $request, Rekening $rekening_model)
    {
        $data_transfer = $request->validated();

        // rekening akuntan & pendanaan
        $rekening_akuntan = $rekening_model->get_rekening_akuntan()->first();
        $rekening_pendanaan = $rekening_model->get_rekening_pendanaan()->first();

        // check saldo
        $this->check_saldo($data_transfer['nominal_transfer'], $rekening_akuntan->saldo, 'Saldo anda tidak cukup untuk melakukan transfer, silahkah cek saldo anda.');

        // catat transaksi
        $catat_transaksi = $rekening_akuntan->transaksi_rekening()->create([
            'nominal' => $data_transfer['nominal_transfer'],
            'keterangan' => $data_transfer['keterangan'],
            'tipe_transaksi' => EnumTipeTransaksi::TRANSFER
        ]);

        // Validasi transaksi
        if ($catat_transaksi->wasRecentlyCreated) {
            Toast::success("Transfer sebesar {$catat_transaksi->formatted_nominal} berhasil");
            // kurangi dana deposit ke rekening
            $rekening_akuntan->decrement('saldo', $data_transfer['nominal_transfer']);
            $rekening_akuntan->save();
            // tambah saldo pendanaan
            $rekening_pendanaan->increment('saldo', $data_transfer['nominal_transfer']);
            $rekening_pendanaan->save();
        } else {
            Toast::error("Transfer sebesar {$catat_transaksi->formatted_nominal} gagal");
        }

        return redirect()->route('admin.rekening-akuntan.index');
    }
    // Action print datatable
    public function export(Request $request, TransaksiRekening $transaksi_rekening_model, Excel $excel)
    {
        /// Get search query
        $filters = $request->only('search', 'tipe_transaksi', 'created_at');

        // Query model
        $query_transaksi = $transaksi_rekening_model->with(['rekening']);

        // Search filters
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                match ($key) {
                    'search' => $query_transaksi->search($value),
                    'created_at' => $query_transaksi->search_by_date($key, $value),
                    default => $query_transaksi->search_by_column($key, $value),
                };

            }
        }

        $data_rekening_akuntan = $query_transaksi->get();

        $today = now()->format('d_M_Y-H_i_s');
        $file_name = "data_transaksi_rekening_{$today}.xlsx";

        return $excel->download(new TransaksiRekeningExport($data_rekening_akuntan), $file_name);
    }
}
