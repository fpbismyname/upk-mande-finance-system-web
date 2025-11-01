<?php

namespace App\Http\Controllers\Admin\User;

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
    public $relations = ['roles'];
    public $paginate = 10;
    public function __invoke(User $user_model, Roles $roles_model)
    {
        // Get search query
        $search = request()->get('search');

        // Data breadcrumbs untuk menu 
        $breadcrumbs = [
            route('admin.index') => 'Dashboard',
            route('admin.users.index') => 'Akun pengguna'
        ];

        // Query model
        $query = $user_model->with($this->relations);

        // Search data if any search input
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('nik', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('nomor_telepon', 'like', "%{$search}%")
                    ->orWhereHas('roles', function ($query_relation) use ($search) {
                        $searched_role = Str::of($search)->replace(" ", "_")->lower();
                        $query_relation->where('name', 'like', "%{$searched_role}%");
                    });
            });
        }

        // Datas
        $datas = $query->latest()->paginate($this->paginate)->withQueryString();
        $list_role = $roles_model->withoutRelations()->get();

        // Debug dump
        Debug::dump($datas, $search);
        // Payload untuk dipassing ke view
        $payload = compact('breadcrumbs', 'datas', 'list_role');

        // kembalikan view list user
        return view('admin.pages.users.list', $payload);
    }
}
