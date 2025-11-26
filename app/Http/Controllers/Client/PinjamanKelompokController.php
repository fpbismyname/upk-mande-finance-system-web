<?php

namespace App\Http\Controllers\Client;

use App\Enums\Admin\User\EnumRole;
use App\Enums\Table\PaginateSize;
use App\Http\Controllers\Controller;
use App\Models\User;

class PinjamanKelompokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelompok = auth()->user()
            ->kelompok()
            ->with(['pinjaman_kelompok'])
            ->first();
        $list_pinjaman_kelompok = $kelompok?->pinjaman_kelompok ? $kelompok->pinjaman_kelompok()->latest()->paginate(PaginateSize::SMALL->value)->withQueryString() : [];
        $payload = compact('kelompok', 'list_pinjaman_kelompok');
        return view('client.pinjaman-kelompok.index', $payload);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, User $user_model)
    {
        $pinjaman_kelompok = auth()->user()
            ->kelompok
            ->pinjaman_kelompok()
            ->with(['cicilan_kelompok'])
            ->findOrFail($id);
        $user_akuntan = $user_model->search_by_column('role', EnumRole::AKUNTAN)->first();
        $payload = compact('pinjaman_kelompok', 'user_akuntan');
        return view('client.pinjaman-kelompok.show', $payload);
    }
}
