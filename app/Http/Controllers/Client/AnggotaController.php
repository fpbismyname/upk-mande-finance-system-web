<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\AnggotaRequest;
use App\Services\UI\Toast;

class AnggotaController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelompok = auth()->user()->kelompok;
        $payload = compact('kelompok');
        return view('client.kelompok.anggota-kelompok.create', $payload);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AnggotaRequest $request)
    {
        $datas = $request->validated();

        $kelompok = auth()->user()->kelompok;

        $added_kelompok = $kelompok->anggota_kelompok()->create($datas);

        if ($added_kelompok->wasRecentlyCreated) {
            Toast::success("Anggota kelompok {$kelompok->name} berhasil ditambahkan.");
        } else {
            Toast::error("Anggota kelompok {$kelompok->name} gagal ditambahkan.");
        }
        return redirect()->route('client.kelompok.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_kelompok, string $id_anggota)
    {
        $anggota_kelompok = auth()->user()->kelompok->anggota_kelompok()->findOrFail($id_anggota);
        $payload = compact('anggota_kelompok');
        return view('client.kelompok.anggota-kelompok.show', $payload);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_kelompok, string $id_anggota)
    {
        $kelompok = auth()->user()->kelompok;
        $anggota_kelompok = $kelompok->anggota_kelompok->findOrFail($id_anggota);
        $payload = compact('kelompok', 'anggota_kelompok');
        return view('client.kelompok.anggota-kelompok.edit', $payload);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AnggotaRequest $request, string $id_kelompok, string $id_anggota)
    {
        $datas = $request->validated();

        $kelompok = auth()->user()->kelompok;

        $anggota = $kelompok->anggota_kelompok()->findOrFail($id_anggota);

        $anggota->update($datas);

        if ($anggota->wasChanged()) {
            Toast::success("{$anggota->name} berhasil diperbarui.");
        } elseif (empty($anggota->getChanges())) {
            Toast::info("{$anggota->name} tidak ada perubahan.");
        } else {
            Toast::error("{$anggota->name} gagal diperbarui.");
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_kelompok, string $id_anggota)
    {
        $kelompok = auth()->user()->kelompok;
        $anggota = $kelompok->anggota_kelompok()->findOrFail($id_anggota);

        $delete_anggota = $anggota->delete();

        if ($delete_anggota) {
            Toast::success("{$anggota->name} berhasil dihapus.");
        } else {
            Toast::error("{$anggota->name} gagal dihapus.");
        }
        return redirect()->route('client.kelompok.index');
    }
}
