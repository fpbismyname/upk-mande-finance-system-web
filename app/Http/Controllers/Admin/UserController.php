<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Admin\User\EnumRole;
use App\Enums\Table\PaginateSize;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Services\UI\Toast;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $relations = [];
    public function index(Request $request, User $user_model)
    {
        // Get search and column query
        $filters = request()->only(['search', 'role']);

        // Query model
        $query = $user_model->with($this->relations);

        // Cari data dari params filters
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                match ($key) {
                    'search' => $query->search($value),
                    default => $query->search_by_column($key, $value)
                };
            }
        }

        // Data user
        $datas = $query->latest()->paginate(PaginateSize::SMALL->value)->withQueryString();
        // Daftar role user
        $list_role = EnumRole::options();

        // Payload untuk dipassing ke view
        $payload = compact('datas', 'list_role');

        // kembalikan view list user
        return view('admin.users.index', $payload);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $list_role = EnumRole::options();
        $payload = compact('list_role');
        return view('admin.users.create', $payload);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request, User $user_model)
    {
        // Validasi data form input
        $datas = $request->validated();

        // Buat akun pengguna
        $store_user = $user_model->create($datas);

        // Validasi akun pengguna yang ditambahkan
        if ($store_user->wasRecentlyCreated) {
            Toast::success('Akun pengguna berhasil ditambahkan.');
        } else {
            Toast::error('Akun pengguna gagal ditambahkan.');
        }
        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id, User $user_model)
    {
        // Mencari data user berdasarkan id
        $user = $user_model::findOrFail($id);
        $payload = compact('user');
        return view('admin.users.show', $payload);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $list_role = EnumRole::options();
        $payload = compact('user', 'list_role');
        return view('admin.users.edit', $payload);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id, User $user_model)
    {
        // Data form input
        $datas = $request->validated();
        // is request reset password
        $is_reset_pass = isset($datas['reset_password']) ? $datas['reset_password'] == 'on' : false;

        // New password
        $new_user_pass = $datas['new_password'] ?? '';

        // Data user
        $data_user = $request->only($user_model->getFillable());

        // Update data user
        $update_user = $user_model->findOrFail($id);

        // reset jika input mengizinkan reset password
        if ($is_reset_pass) {
            $update_user->resetPassword($new_user_pass);
        }

        // Update data user
        $update_user->update($data_user);

        // Validasi akun pengguna yang ditambahkan
        if ($update_user->wasChanged()) {
            Toast::success('Akun pengguna berhasil diperbarui.');
        } elseif (empty($update_user->getChanges())) {
            Toast::info('Tidak ada perubahan pada akun pengguna.');
        } else {
            Toast::error('Akun pengguna gagal diperbarui.');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, User $user_model)
    {
        // Delete user
        $current_user = User::findOrFail($id);
        $delete_user = $current_user->delete();

        // Validasi akun pengguna yang ditambahkan
        if ($delete_user) {
            Toast::success('Akun pengguna berhasil dihapus.');
        } else {
            Toast::error('Akun pengguna gagal dihapus.');
        }
        return redirect()->back();
    }
}
