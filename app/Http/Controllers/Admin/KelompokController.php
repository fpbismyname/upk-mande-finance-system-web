<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Admin\Status\EnumStatusKelompok;
use App\Enums\Admin\User\EnumRole;
use App\Enums\Table\PaginateSize;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KelompokRequest;
use App\Models\Kelompok;
use App\Models\User;
use App\Services\UI\Toast;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $relations = ['users', 'anggota_kelompok'];
    public function index(Request $request, Kelompok $kelompok_model)
    {
        // Get search query
        $filters = request()->only(['search', 'status']);

        // Query model
        $query = $kelompok_model->with($this->relations);

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
        $list_status = EnumStatusKelompok::options();

        // Payload untuk dipassing ke view
        $payload = compact('datas', 'list_status');

        // kembalikan view list user
        return view('admin.kelompok.index', $payload);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(User $user_model)
    {
        $role_ketua = EnumRole::ANGGOTA;
        $list_ketua_kelompok = $user_model->doesntHaveKelompok()->get();
        $list_status = EnumStatusKelompok::options();

        $payload = compact('list_ketua_kelompok', 'list_status');
        return view('admin.kelompok.create', $payload);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KelompokRequest $request, Kelompok $kelompok_model)
    {
        $datas = $request->validated();
        $added_kelompok = $kelompok_model->create($datas);
        if ($added_kelompok->wasRecentlyCreated) {
            Toast::success("Kelompok {$added_kelompok->name} berhasil ditambahkan");
        } else {
            Toast::error("Kelompok {$added_kelompok->name} gagal ditambahkan");
        }
        return redirect()->route('admin.kelompok.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id_kelompok)
    {
        // Data kelompok
        $kelompok = Kelompok::findOrFail($id_kelompok);
        $role_ketua = EnumRole::ANGGOTA;
        $list_ketua_kelompok = User::doesntHaveKelompok()->get();
        $list_status = EnumStatusKelompok::options();

        // Data anggota kelompok
        $query_anggota = $kelompok->anggota_kelompok();
        $search = $request->get('search');
        $query_anggota->search($search);
        $data_anggota = $query_anggota->paginate(PaginateSize::SMALL->value)->withQueryString();

        $payload = compact('kelompok', 'list_ketua_kelompok', 'list_status', 'data_anggota');
        return view('admin.kelompok.show', $payload);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_kelompok, Kelompok $kelompok_model, User $user_model)
    {
        $kelompok = $kelompok_model->findOrFail($id_kelompok);
        $role_ketua = EnumRole::ANGGOTA;
        $list_ketua_kelompok = $user_model->doesntHaveKelompok($kelompok->users_id)->get();
        $list_status = EnumStatusKelompok::options();
        $payload = compact('kelompok', 'list_ketua_kelompok', 'list_status');
        return view('admin.kelompok.edit', $payload);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KelompokRequest $request, string $id_kelompok, Kelompok $kelompok_model)
    {
        // Data kelompok
        $kelompok = $kelompok_model->findOrFail($id_kelompok);

        $datas = $request->validated();

        $kelompok->update($datas);

        // Validasi update kelompok
        if ($kelompok->wasChanged()) {
            Toast::success("Kelompok {$kelompok->name} berhasil diperbarui.");
        } elseif ($kelompok->getChanges()) {
            Toast::error("Kelompok {$kelompok->name} gagal diperbarui.");
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Kelompok $kelompok)
    {
        $kelompok = $kelompok->findOrFail($id);
        $deleted_kelompok = $kelompok->delete();

        // Validasi update kelompok
        if ($deleted_kelompok) {
            Toast::success("Kelompok {$kelompok->name} berhasil dihapus.");
        } else {
            Toast::error("Kelompok {$kelompok->name} gagal dihapus.");
        }

        return redirect()->back();

    }
}
