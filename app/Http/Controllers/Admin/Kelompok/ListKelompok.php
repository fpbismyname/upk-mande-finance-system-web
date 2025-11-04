<?php

namespace App\Http\Controllers\Admin\Kelompok;

use App\Enum\Admin\Status\EnumStatusKelompok;
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
    public $relations = ['users', 'anggota_kelompok'];
    public $paginate = 10;
    public function __invoke(Kelompok $kelompok_model, StatusKelompok $status_kelompok_model)
    {
        // Get search query
        $search = request()->get('search');
        $status = request()->get('status');

        // Data breadcrumbs untuk menu 
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.kelompok.index') => 'Kelompok Upk'
        ];

        // Query model
        $query = $kelompok_model->with($this->relations);

        // Search data if any search input
        if (!empty($search)) {
            $query->filter($search);
        }

        // Search data by column
        if (!empty($status)) {
            $query->filterStatus($status);
        }

        // Datas
        $datas = $query->latest()->paginate($this->paginate)->withQueryString();
        $list_status = EnumStatusKelompok::options();

        // Debug dump
        Debug::dump($datas, $search);
        // Payload untuk dipassing ke view
        $payload = compact('breadcrumbs', 'datas', 'list_status');

        // kembalikan view list user
        return view('admin.pages.kelompok.list', $payload);
    }
}
