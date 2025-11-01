<?php

namespace App\Http\Controllers\Admin\JadwalPencairan;

use App\Http\Controllers\Controller;
use App\Models\JadwalPencairan;
use App\Models\Status\StatusJadwalPencairan;
use App\Services\Utils\Debug;
use Illuminate\Support\Str;

class ListJadwalPencairan extends Controller
{
    /**
     * Handle the incoming request.
     * 
     */
    protected $relations = ['kelompok', 'pengajuan_pinjaman', 'status_jadwal_pencarian'];
    protected $paginate = 10;
    public function __invoke(JadwalPencairan $jadwal_pencairan_model, StatusJadwalPencairan $status_jadwal_pencairan_model)
    {
        // Get search query
        $search = request()->get('search');

        // Data breadcrumbs untuk menu 
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.jadwal-pencairan.index') => 'Jadwal Pencairan'
        ];

        // Query model
        $query = $jadwal_pencairan_model->with($this->relations);

        // Search data if any search input
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->WhereHas('pengajuan_pinjaman', function ($query_relation) use ($search) {
                    $query_relation->where('nominal_pinjaman', 'like', "%{$search}%");
                })->orWhereHas('status_jadwal_pencairan', function ($query_relation) use ($search) {
                    $searced_status = Str::of($search)->lower()->replace(" ", "_");
                    $query_relation->where('name', 'like', "%{$searced_status}%");
                });
            });
        }

        // Datas
        $datas = $query->orderBy('pengajuan_pada')->paginate($this->paginate)->withQueryString();
        $list_status_jadwal_pencairan = $status_jadwal_pencairan_model->withoutRelations()->get();

        // Debug dump
        Debug::dump($datas, $search);

        // Payload untuk dipassing ke view
        $payload = compact('breadcrumbs', 'datas', 'list_status_jadwal_pencairan');

        // kembalikan view list user
        return view('admin.pages.jadwal-pencairan.list', $payload);
    }
}
