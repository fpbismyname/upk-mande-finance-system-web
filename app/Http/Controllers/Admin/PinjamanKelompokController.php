<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Admin\PengajuanPinjaman\EnumTenor;
use App\Enums\Admin\Status\EnumStatusCicilanKelompok;
use App\Enums\Admin\Status\EnumStatusPinjaman;
use App\Enums\Table\PaginateSize;
use App\Exports\Admin\PinjamanKelompokExport;
use App\Http\Controllers\Controller;
use App\Models\PinjamanKelompok;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class PinjamanKelompokController extends Controller
{
    protected $relations = ['kelompok'];
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PinjamanKelompok $pinjaman_kelompok_model)
    {
        // Get search query
        $filters = $request->only(['search', 'status', 'tenor']);

        // Query model
        $query = $pinjaman_kelompok_model->with($this->relations);

        // Search data if any search input
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                match ($key) {
                    'search' => $query->search($value),
                    default => $query->search_by_column($key, $value),
                };

            }
        }

        // Datas
        $datas = $query->latest()->paginate(PaginateSize::SMALL->value)->withQueryString();
        $list_status = EnumStatusPinjaman::options();
        $list_tenor = EnumTenor::options();

        // Payload untuk dipassing ke view
        $payload = compact('datas', 'list_status', 'list_tenor');

        // kembalikan view list user
        return view('admin.pinjaman-kelompok.index', $payload);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id, PinjamanKelompok $pinjaman_kelompok_model)
    {
        // Data Pinjaman
        $pinjaman_kelompok = $pinjaman_kelompok_model->findOrFail($id);

        // Data anggota kelompok
        $query_cicilan = $pinjaman_kelompok->cicilan_kelompok();
        $filters = $request->only(['status']);
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                match ($key) {
                    'search' => $query_cicilan->search($value),
                    default => $query_cicilan->search_by_column($key, $value),
                };
            }
        }
        $data_cicilan = $query_cicilan->paginate(PaginateSize::SMALL->value)->withQueryString();
        $list_status_cicilan = EnumStatusCicilanKelompok::options();

        $payload = compact('pinjaman_kelompok', 'data_cicilan', 'list_status_cicilan');
        return view('admin.pinjaman-kelompok.show', $payload);
    }
    public function export(Request $request, PinjamanKelompok $pinjaman_kelompok_model, Excel $excel)
    {
        // Get search query
        $filters = $request->only(['search', 'status', 'tenor']);

        // Query model
        $query = $pinjaman_kelompok_model->with($this->relations);

        // Search data if any search input
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                match ($key) {
                    'search' => $query->search($value),
                    default => $query->search_by_column($key, $value),
                };

            }
        }
        $data_pengajuan = $query->get();

        $today = now()->format('d_M_Y-H_i_s');
        $file_name = "data_pinjaman_kelompok_{$today}.xlsx";

        return $excel->download(new PinjamanKelompokExport($data_pengajuan), $file_name);
    }
}
