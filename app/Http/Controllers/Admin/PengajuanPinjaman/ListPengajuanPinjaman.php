<?php

namespace App\Http\Controllers\Admin\PengajuanPinjaman;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPinjaman;
use App\Models\Status\StatusPengajuanPinjaman;
use App\Services\Utils\Debug;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ListPengajuanPinjaman extends Controller
{
    /**
     * Handle the incoming request.
     */
    public $relations = ['kelompok', 'status_pengajuan_pinjaman'];
    public $paginate = 10;
    public function __invoke(Request $request, PengajuanPinjaman $pengajuan_pinjaman_model, StatusPengajuanPinjaman $status_pengajuan_pinjaman_model)
    {
        // Get search query
        $search = request()->get('search');

        // Data breadcrumbs untuk menu 
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.pengajuan-pinjaman.index') => 'Pengajuan pinjaman'
        ];

        // Query model
        $query = $pengajuan_pinjaman_model->with($this->relations);

        // Search data if any search input
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('nominal_pinjaman', 'like', "%{$search}%")
                    ->orWhere('tenor', 'like', "%{$search}%")
                    ->orWhere('pengajuan_pada', 'like', "%{$search}%")
                    ->orWhere('disetujui_pada', 'like', "%{$search}%")
                    ->orWhere('ditolak_pada', 'like', "%{$search}%")
                    ->orWhereHas('kelompok', function ($query_relation) use ($search) {
                        $query_relation->whereHas('users', function ($nqr) use ($search) {
                            $nqr->where('name', 'like', "%{$search}%");
                        });
                    })
                    ->orWhereHas('status_pengajuan_pinjaman', function ($query_relation) use ($search) {
                        $searched_status = Str::of($search)->lower()->replace(" ", "_");
                        $query_relation->where('name', 'like', "{$searched_status}");
                    });
            });
        }

        // Datas
        $datas = $query->orderBy('pengajuan_pada')->paginate($this->paginate)->withQueryString();
        $list_status_pengajuan = $status_pengajuan_pinjaman_model->withoutRelations()->get();

        // Debug dump
        Debug::dump($datas, $search);

        // Payload untuk dipassing ke view
        $payload = compact('breadcrumbs', 'datas', 'list_status_pengajuan');

        // kembalikan view list user
        return view('admin.pages.pengajuan-pinjaman.list', $payload);
    }
}
