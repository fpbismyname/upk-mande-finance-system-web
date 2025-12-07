<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Admin\Status\EnumStatusPengajuanKeanggotaan;
use App\Enums\Admin\User\EnumRole;
use App\Enums\Table\PaginateSize;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\UserRequest;
use App\Models\PengajuanKeanggotaan;
use App\Models\User;
use App\Services\UI\Toast;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        // Payload untuk dipassing ke view
        $payload = compact('datas');

        // kembalikan view list user
        return view('admin.users.index', $payload);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_admin()
    {
        return view('admin.users.create-admin');
    }
    public function create_client()
    {
        return view('admin.users.create-client');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request, User $user_model, PengajuanKeanggotaan $pengajuan_keanggotaan_model)
    {
        // Validasi data form input
        $request->validated();

        $data_users = $request->only($user_model->getFillable());
        $data_pengajuan_keanggotaan = $request->only($pengajuan_keanggotaan_model->getFillable());

        $store_user = $user_model->create($data_users);

        if (!empty($data_pengajuan_keanggotaan)) {
            // Upload files
            $files = $request->only('ktp');
            $uploaded_file_path = [];
            $storage_private = Storage::disk('local');
            foreach ($files as $key => $value) {
                $file = $value;
                $ext_file = $file->getClientOriginalExtension();
                $safe_username = Str::snake($data_pengajuan_keanggotaan['nama_lengkap']);
                $today = now()->clone()->format('d-m-y');
                $file_name = "{$key}_{$safe_username}_{$today}.{$ext_file}";
                if ($storage_private->exists($file_name)) {
                    $storage_private->delete($file_name);
                }
                $uploaded_file_path[$key] = $storage_private->putFileAs('users', $file, $file_name);
            }

            foreach ($uploaded_file_path as $key => $path) {
                $data_pengajuan_keanggotaan[$key] = $path;
            }
            // Buat akun pengguna
            $data_pengajuan_keanggotaan['status'] = EnumStatusPengajuanKeanggotaan::DISETUJUI;
            $store_user->pengajuan_keanggotaan()->create($data_pengajuan_keanggotaan);
        }

        // Validasi akun pengguna yang ditambahkan
        if ($store_user->wasRecentlyCreated) {
            Toast::success('Akun pengguna berhasil ditambahkan.');
        } else {
            Toast::error('Akun pengguna gagal ditambahkan.');
        }
        return redirect()->route('admin.users.index');
    }

    public function show($id, User $user_model)
    {
        // Mencari data user berdasarkan id
        $user = $user_model::findOrFail($id);
        $pengajuan_keanggotaan = $user->pengajuan_keanggotaan_disetujui->first();
        $payload = compact('user', 'pengajuan_keanggotaan');
        return view('admin.users.show', $payload);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $pengajuan_keanggotaan = $user->pengajuan_keanggotaan_disetujui()->first();
        $payload = compact('user', 'pengajuan_keanggotaan');
        return view('admin.users.edit', $payload);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id, User $user_model, PengajuanKeanggotaan $pengajuan_keanggotaan_model)
    {
        // Data form input
        $datas = $request->validated();

        // is request reset password
        $is_reset_pass = isset($datas['reset_password']) ? $datas['reset_password'] == 'on' : false;

        // New password
        $new_user_pass = $datas['new_password'] ?? '';

        // Data user
        $data_user = $request->only($user_model->getFillable());

        // Data pengajuan keanggotaan
        $entries_data_pengajuan_keanggotaan = $request->only($pengajuan_keanggotaan_model->getFillable());

        // Update data user
        $user = $update_user = $user_model->findOrFail($id);
        $data_pengajuan_keanggotaan = $user->pengajuan_keanggotaan_disetujui()->first();


        // reset jika input mengizinkan reset password
        if ($is_reset_pass) {
            $update_user->password = Hash::make($new_user_pass);
        }

        // Update data pengajuan
        if (!empty($entries_data_pengajuan_keanggotaan)) {
            // Upload files
            $files = $request->only('ktp');
            $uploaded_file_path = [];
            $storage_private = Storage::disk('local');
            foreach ($files as $key => $value) {
                $file = $value;
                $ext_file = $file->getClientOriginalExtension();
                $safe_username = Str::snake($entries_data_pengajuan_keanggotaan['nama_lengkap']);
                $today = now()->clone()->format('d-m-y');
                $file_name = "{$key}_{$safe_username}_{$today}.{$ext_file}";
                if ($storage_private->exists($file_name)) {
                    $storage_private->delete($file_name);
                }
                $uploaded_file_path[$key] = $storage_private->putFileAs('users', $file, $file_name);
            }

            foreach ($uploaded_file_path as $key => $path) {
                $entries_data_pengajuan_keanggotaan[$key] = $path;
            }
            $data_pengajuan_keanggotaan->update(attributes: $entries_data_pengajuan_keanggotaan);
        }

        // Update data user
        $update_user = $user->update($data_user);

        // Validasi akun pengguna yang ditambahkan
        if ($update_user) {
            Toast::success('Akun pengguna berhasil diperbarui.');
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
