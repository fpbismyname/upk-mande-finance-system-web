<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Admin\CatatanPendanaan\EnumCatatanPendanaan;
use App\Enums\Table\PaginateSize;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PendanaanRequest;
use App\Models\CatatanPendanaan;
use App\Models\Pendanaan;
use App\Services\UI\Toast;
use Illuminate\Http\Request;

class PendanaanController extends Controller
{
    protected $relations = [];
    protected $max_pendanaan = 900000000000000;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, CatatanPendanaan $catatan_pendanaan_model, Pendanaan $pendanaan_model)
    {
        // Get search query
        $filters = $request->only('search', 'tipe_catatan');

        // Query model
        $query_catatan = $catatan_pendanaan_model->with($this->relations);

        // Search filters
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                match ($key) {
                    'search' => $query_catatan->search($value),
                    default => $query_catatan->search_by_column($key, $value),
                };

            }
        }

        // Datas
        $datas_pendanaan = $pendanaan_model->first();
        $datas_catatan_pendanaan = $query_catatan->latest()->paginate(PaginateSize::SMALL->value)->withQueryString();
        $list_tipe_catatan = EnumCatatanPendanaan::options();

        // Payload untuk dipassing ke view
        $payload = compact('datas_pendanaan', 'datas_catatan_pendanaan', 'list_tipe_catatan');

        // kembalikan view list user
        return view('admin.pendanaan.index', $payload);
    }

    public function tambah_saldo(PendanaanRequest $request, Pendanaan $pendanaan_model, CatatanPendanaan $catatan_pendanaan_model)
    {
        // Validasi batas tambah saldo
        $datas = $request->validated();

        // Data pendanaan
        $pendanaan = $pendanaan_model->first();
        $pendanaan->increment('saldo', $datas['jumlah_saldo']);

        // Validasi data pendanaan 
        if ($pendanaan->wasChanged()) {
            $data_inflow = [
                'jumlah_saldo' => $datas['jumlah_saldo'],
                'catatan' => $datas['catatan'] ?? "-",
                'tipe_catatan' => EnumCatatanPendanaan::INFLOW,
            ];
            $catat_pendanaan = $catatan_pendanaan_model->create($data_inflow);
            if ($catat_pendanaan->wasRecentlyCreated) {
                Toast::success('Saldo pendanaan berhasil ditambahkan');
            } else {
                Toast::error('Saldo pendanaan gagal ditambahkan');
            }
        } else {
            Toast::error('Saldo pendanaan gagal ditambahkan');
        }
        return redirect()->back();
    }

    public function tarik_saldo(PendanaanRequest $request, Pendanaan $pendanaan_model, CatatanPendanaan $catatan_pendanaan_model)
    {
        // Validasi batas tambah saldo
        $datas = $request->validated();

        // Data pendanaan
        $pendanaan = $pendanaan_model->first();

        // Validasi tarik saldo
        if ($pendanaan->saldo < $datas['jumlah_saldo']) {
            Toast::error('Saldo pendanaan tidak mencukupi untuk ditarik.');
            return redirect()->back();
        }

        $pendanaan->decrement('saldo', $datas['jumlah_saldo']);

        // Validasi data pendanaan 
        if ($pendanaan->wasChanged()) {
            $data_outflow = [
                'jumlah_saldo' => $datas['jumlah_saldo'],
                'catatan' => $datas['catatan'] ?? "-",
                'tipe_catatan' => EnumCatatanPendanaan::OUTFLOW,
            ];
            $catat_pendanaan = $catatan_pendanaan_model->create($data_outflow);
            if ($catat_pendanaan->wasRecentlyCreated) {
                Toast::success('Saldo pendanaan berhasil ditarik');
            } else {
                Toast::error('Saldo pendanaan gagal ditarik');
            }
        } else {
            Toast::error('Saldo pendanaan gagal ditambahkan');
        }
        return redirect()->back();
    }
}
