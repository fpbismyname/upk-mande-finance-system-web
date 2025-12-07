<x-layouts.admin-app title="Detail pengajuan pinjaman">
    <div class="flex flex-col gap-4">
        {{-- Data pengajuan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Preview dokumen proposal pengajuan pinjaman --}}
            @if ($pengajuan_pinjaman->file_proposal)
                <div class="grid md:col-span-2 place-items-center gap-4 my-6">
                    <iframe src="{{ route('storage.private.get', ['path' => $pengajuan_pinjaman->file_proposal]) }}"
                        frameborder="0"
                        class="border-0 shadow-xl rounded-box aspect-square md:aspect-video max-w-6xl"></iframe>
                    <h6>Proposal pengajuan pinjaman</h6>
                </div>
            @endif

            {{-- field data pengajuan pinjaman --}}
            {{-- Nama kelompok --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama kelompok</legend>
                <p class="w-full">{{ $pengajuan_pinjaman->kelompok_name }}</p>
            </fieldset>

            {{-- Ketua kelompok --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Ketua kelompok</legend>
                <p class="w-full">{{ $pengajuan_pinjaman->ketua_name }}</p>
            </fieldset>

            {{-- Status --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Status</legend>
                <p class="w-full">{{ $pengajuan_pinjaman->formatted_status }}</p>
            </fieldset>

            {{-- Nominal pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nominal Pinjaman</legend>
                <p class="w-full">{{ $pengajuan_pinjaman->formatted_nominal_pinjaman }}</p>
            </fieldset>

            {{-- Tenor --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Tenor</legend>
                <p class="w-full">{{ $pengajuan_pinjaman->formatted_tenor }}</p>
            </fieldset>

            {{-- Tanggal pengajuan --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Pengajuan Pada</legend>
                <p class="w-full">{{ $pengajuan_pinjaman->formatted_tanggal_pengajuan }}</p>
            </fieldset>

            {{-- Tanggal disetujui --}}
            @if ($pengajuan_pinjaman->status_disetujui)
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Disetujui Pada</legend>
                    <p class="w-full">{{ $pengajuan_pinjaman->formatted_tanggal_disetujui }}</p>
                </fieldset>
            @endif

            {{-- Tanggal di tolak --}}
            @if ($pengajuan_pinjaman->status_ditolak)
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Tanggal ditolak</legend>
                    <p class="w-full">{{ $pengajuan_pinjaman->formatted_tanggal_ditolak }}</p>
                </fieldset>
            @endif

            {{-- Catatan proposal --}}
            @if ($pengajuan_pinjaman->catatan)
                <div class="grid md:col-span-2">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Catatan</legend>
                        <p class="w-full">{{ $pengajuan_pinjaman->catatan }}</p>
                    </fieldset>
                </div>
            @endif
        </div>

        {{-- Approval action --}}
        <div
            class="grid {{ $pengajuan_pinjaman->status_dalam_proses_pengajuan ? 'md:grid-cols-2' : 'md:grid-cols-1 place-items-center' }} gap-4 my-8">
            <a href="{{ route('admin.pengajuan-pinjaman.index') }}" class="btn btn-neutral">
                Kembali
            </a>
        </div>
    </div>
</x-layouts.admin-app>
