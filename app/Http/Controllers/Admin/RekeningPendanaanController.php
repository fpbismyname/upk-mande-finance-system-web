<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Admin\Rekening\EnumTipeTransaksi;
use App\Enums\Table\PaginateSize;
use App\Exports\Admin\CatatanPendanaanExport;
use App\Http\Controllers\Controller;
use App\Models\Rekening;
use App\Models\TransaksiRekening;
use App\Services\UI\Toast;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class RekeningPendanaanController extends Controller
{
    protected $relations = ['transaksi_rekening'];

    // Check saldo
    public function check_saldo($current_saldo, $target_saldo, $message)
    {
        if ($current_saldo < $target_saldo) {
            Toast::info($message);
            return redirect()->route('admin.rekening.pendanaan.index');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, TransaksiRekening $transaksi_rekening_model, Rekening $rekening_model)
    {
        // Get search query
        $filters = $request->only('search', 'tipe_transaksi', 'created_at');

        // Data model
        $rekening_pendanaan = $rekening_model->get_rekening_pendanaan()->first();

        // Query model
        $query_transaksi = $rekening_pendanaan->transaksi_rekening();

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
        $list_tipe_transaksi = EnumTipeTransaksi::tipe_masuk_keluar();

        // Payload untuk dipassing ke view
        $payload = compact('data_rekening', 'data_transaksi_rekening', 'list_tipe_transaksi');

        // kembalikan view list user
        return view('admin.rekening.pendanaan.index', $payload);
    }
}
