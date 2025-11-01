<?php

namespace App\Http\Controllers\Admin\Kelompok;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\Status\StatusKelompok;
use App\Services\Utils\Debug;
use Illuminate\Support\Str;

class ListKelompok extends Controller
{
    /**
     * Handle the incoming request.
     */
    public $relations = ['users', 'anggota_kelompok', 'status_kelompok'];
    public $paginate = 10;
    public function __invoke(Kelompok $kelompok_model, StatusKelompok $status_kelompok_model)
    {
        // Get search query
        $search = request()->get('search');

        // Data breadcrumbs untuk menu 
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.kelompok.index') => 'Kelompok Upk'
        ];

        // Query model
        $query = $kelompok_model->with($this->relations);

        // Search data if any search input
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('limit_pinjaman', 'like', "%{$search}%")
                    ->orWhereHas('users', function ($query_relation) use ($search) {
                        $query_relation->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('status_kelompok', function ($query_relation) use ($search) {
                        $searched_status = Str::of($search)->lower()->replace(" ", "_");
                        $query_relation->where('name', 'like', "{$searched_status}");
                    });
            });
        }

        // Datas
        $datas = $query->paginate($this->paginate)->withQueryString();
        $list_status_kelompok = $status_kelompok_model->withoutRelations()->get();

        // Debug dump
        Debug::dump($datas, $search);
        // Payload untuk dipassing ke view
        $payload = compact('breadcrumbs', 'datas', 'list_status_kelompok');

        // kembalikan view list user
        return view('admin.pages.kelompok.list', $payload);
    }
}
