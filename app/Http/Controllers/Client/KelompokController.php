<?php

namespace App\Http\Controllers\Client;

use App\Enums\Admin\Settings\EnumSettingKeys;
use App\Enums\Admin\Status\EnumStatusKelompok;
use App\Enums\Table\PaginateSize;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\KelompokRequest;
use App\Models\Kelompok;
use App\Models\Settings;
use App\Services\UI\Toast;

class KelompokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelompok = auth()->user()
            ->kelompok()
            ->with(['anggota_kelompok'])
            ->first();
        $list_anggota_kelompok = $kelompok?->anggota_kelompok ? $kelompok->anggota_kelompok()->paginate(PaginateSize::SMALL->value)->withQueryString() : [];
        $kelompok_settings = [
            EnumSettingKeys::MINIMAL_ANGGOTA_KELOMPOK->value => Settings::getKeySetting(EnumSettingKeys::MINIMAL_ANGGOTA_KELOMPOK)->value('value')
        ];
        $payload = compact('kelompok', 'list_anggota_kelompok', 'kelompok_settings');
        return view('client.kelompok.index', $payload);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(KelompokRequest $request, Kelompok $kelompok_model)
    {
        $request->validated();
        $datas = [
            'name' => $request->input('name'),
            'users_id' => auth()->user()->id,
            'status' => EnumStatusKelompok::AKTIF,
            'limit_pinjaman' => 10000000.00,
        ];
        $added_kelompok = $kelompok_model->create($datas);
        if ($added_kelompok->wasRecentlyCreated) {
            Toast::success('Kelompok berhasil dibuat.');
        } else {
            Toast::error('Kelompok gagal dibuat.');
        }
        return redirect()->back();
    }
    public function update(KelompokRequest $request, string $id)
    {
        $datas = $request->validated();
        $kelompok = auth()->user()->kelompok;
        $kelompok->update($datas);
        if ($kelompok->wasChanged()) {
            Toast::success('Nama kelompok berhasil diperbarui.');
        } elseif (empty($kelompok->getChanges())) {
            Toast::info('Tidak ada perubahan yang dilakukan.');
        } else {
            Toast::error('Nama kelompok gagal diperbarui.');
        }
        return redirect()->back();
    }
}
