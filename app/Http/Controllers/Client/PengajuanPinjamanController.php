<?php

namespace App\Http\Controllers\Client;

use App\Enums\Admin\PengajuanPinjaman\EnumTenor;
use App\Enums\Admin\Status\EnumStatusPengajuanPinjaman;
use App\Enums\Table\PaginateSize;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\PengajuanPinjamanRequest;
use App\Services\UI\Toast;
use Illuminate\Support\Facades\Storage;

class PengajuanPinjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelompok = auth()->user()
            ->kelompok()
            ->with(['pinjaman_kelompok_berlangsung', 'pengajuan_pinjaman'])
            ->first();
        $list_pengajuan_pinjaman = $kelompok?->pengajuan_pinjaman ? $kelompok?->pengajuan_pinjaman()->latest()->paginate(PaginateSize::SMALL->value)->withQueryString() : [];
        $payload = compact('kelompok', 'list_pengajuan_pinjaman');
        return view('client.pengajuan-pinjaman.index', $payload);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelompok = auth()->user()->kelompok;
        $list_tenor = EnumTenor::options();
        $payload = compact('kelompok', 'list_tenor');
        return view('client.pengajuan-pinjaman.create', $payload);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PengajuanPinjamanRequest $request)
    {
        $kelompok = auth()->user()->kelompok;
        $data_pengajuan = $request->validated();
        $pengajuan_pinjaman_model = auth()->user()->kelompok()->first()->pengajuan_pinjaman();

        // Simpan file proposal
        $today = now()->format("d_M_Y-H_i_s");
        $file_proposal = $request->file('file_proposal');
        $extension_file = $file_proposal->getClientOriginalExtension();
        $name_file_proposal = "proposal_pengajuan_pinjaman_{$kelompok->formatted_name_snake_case}_{$today}.{$extension_file}";
        $path_file = Storage::disk()->putFileAs("proposals", $file_proposal, $name_file_proposal);

        // simpan path file
        $data_pengajuan['file_proposal'] = $path_file;

        $added_pengajuan = $pengajuan_pinjaman_model->create($data_pengajuan);

        if ($added_pengajuan->wasRecentlyCreated) {
            Toast::success("Pengajuan berhasil dikirim. Silahkan tunggu persetujuan admin.");
        } else {
            Toast::error("Pengajuan gagal dikirim. Silahkan cobalagi di lain waktu.");
        }

        return redirect()->route('client.pengajuan-pinjaman.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pengajuan_pinjaman = auth()->user()->kelompok->pengajuan_pinjaman()->findOrFail($id);
        $list_tenor = EnumTenor::options();
        $payload = compact('pengajuan_pinjaman', 'list_tenor');
        return view('client.pengajuan-pinjaman.show', $payload);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kelompok = auth()->user()->kelompok;
        $pengajuan_pinjaman = auth()->user()->kelompok->pengajuan_pinjaman()->findOrFail($id);
        $list_tenor = EnumTenor::options();
        $payload = compact('kelompok', 'pengajuan_pinjaman', 'list_tenor');
        return view('client.pengajuan-pinjaman.edit', $payload);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PengajuanPinjamanRequest $request, string $id)
    {
        $data_pengajuan = $request->validated();
        $kelompok = auth()->user()->kelompok;
        $pengajuan_pinjaman_model = auth()->user()->kelompok->pengajuan_pinjaman()->findOrFail($id);

        $today = now()->format("d_M_Y-H_i");
        if ($request->hasFile('file_proposal')) {
            if (Storage::disk()->exists($pengajuan_pinjaman_model->file_proposal)) {
                Storage::disk()->delete($pengajuan_pinjaman_model->file_proposal);
            }
            $file_proposal = $request->file('file_proposal');
            $extension_file = $file_proposal->getClientOriginalExtension();
            $name_file_proposal = "proposal_pengajuan_pinjaman_{$kelompok->formatted_name_snake_case}.{$extension_file}";
            $path_file = Storage::disk()->putFileAs("proposals", $file_proposal, $name_file_proposal);

            // simpan path file
            $data_pengajuan['file_proposal'] = $path_file;
        } else {
            unset($data_pengajuan['file_proposal']);
        }

        $pengajuan_pinjaman_model->update($data_pengajuan);

        if ($pengajuan_pinjaman_model->wasChanged()) {
            Toast::success("Update data pengajuan berhasil.");
        } elseif (empty($pengajuan_pinjaman_model->getChanges())) {
            Toast::info("Tidak ada perubahan yang dilakukan.");
        } else {
            Toast::error("Update data pengajuan gagal . Silahkan cobalagi di lain waktu.");
        }

        return redirect()->route('client.pengajuan-pinjaman.show', [$pengajuan_pinjaman_model->id]);
    }

    public function cancel_pengajuan(string $id)
    {
        $pengajuan_pinjaman_model = auth()->user()->kelompok->pengajuan_pinjaman()->findOrFail($id);
        if (Storage::disk()->exists($pengajuan_pinjaman_model->file_proposal)) {
            Storage::disk()->delete($pengajuan_pinjaman_model->file_proposal);
        }
        $pengajuan_pinjaman_model->update([
            'status' => EnumStatusPengajuanPinjaman::DIBATALKAN,
            'file_proposal' => null
        ]);
        if ($pengajuan_pinjaman_model->wasChanged()) {
            Toast::success('Pengajuan berhasil dibatalkan.');
        } else {
            Toast::error('Pengajuan gagal dibatalkan. Silahkan cobalagi di lain waktu');
        }
        return redirect()->back();
    }
}
