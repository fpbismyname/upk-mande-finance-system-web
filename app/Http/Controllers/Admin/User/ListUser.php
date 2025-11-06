<?php

namespace App\Http\Controllers\Admin\User;

use App\Enum\Admin\User\EnumRole;
use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Models\User;
use App\Services\Utils\Debug;
use Illuminate\Support\Str;

class ListUser extends Controller
{
    /**
     * Handle the incoming request.
     */
    public $relations = [];
    public $paginate = 10;
    public function __invoke(User $user_model)
    {
        // Get search and column query
        $search = request()->get('search');
        $role = request()->get('role');

        // Data breadcrumbs untuk menu 
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.users.index') => 'Akun pengguna'
        ];

        // Query model
        $query = $user_model->with($this->relations);

        // Search data if any search input
        if (!empty($search)) {
            $query->filter($search);
        }
        if (!empty($role)) {
            $query->filterRole($role);
        }

        // Datas
        $datas = $query->latest()->paginate($this->paginate)->withQueryString();
        $list_role = EnumRole::options();

        // Debug dump
        Debug::dump($datas, $search);
        // Payload untuk dipassing ke view
        $payload = compact('breadcrumbs', 'datas', 'list_role');

        // kembalikan view list user
        return view('admin.pages.users.list', $payload);
    }
}
