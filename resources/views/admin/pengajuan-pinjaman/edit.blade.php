<x-layouts.admin-app title="Review pengajuan pinjaman">
    <div class="flex flex-col gap-4">
        {{-- Data pengajuan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Preview dokumen proposal pengajuan pinjaman --}}
            @if ($pengajuan_pinjaman->file_proposal)
                <div class="grid md:col-span-2 place-items-center gap-4 my-6">
                    <iframe src="{{ route('storage.get-file', ['path' => $pengajuan_pinjaman->file_proposal]) }}"
                        frameborder="0"
                        class="border-0 shadow-xl rounded-box aspect-square md:aspect-video max-w-6xl"></iframe>
                    <h6>Proposal pengajuan pinjaman</h6>
                </div>
            @endif

            {{-- field data pengajuan pinjaman --}}

            {{-- Nama kelompok --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama kelompok</legend>
                <input type="text" class="input w-full" value="{{ $pengajuan_pinjaman->kelompok_name }}" readonly />
            </fieldset>

            {{-- Ketua kelompok --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Ketua kelompok</legend>
                <input type="text" class="input w-full" value="{{ $pengajuan_pinjaman->ketua_name }}" readonly />
            </fieldset>

            {{-- Status --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Status</legend>
                <input type="text" class="input w-full" value="{{ $pengajuan_pinjaman->formatted_status }}"
                    readonly />
            </fieldset>

            {{-- Nominal pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nominal Pinjaman</legend>
                <input type="text" class="input w-full" value="{{ $pengajuan_pinjaman->formatted_nominal_pinjaman }}"
                    readonly />
            </fieldset>

            {{-- Tenor --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Tenor</legend>
                <input type="text" class="input w-full" value="{{ $pengajuan_pinjaman->formatted_tenor }}"
                    readonly />
            </fieldset>

            {{-- Tanggal pengajuan --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Pengajuan Pada</legend>
                <input type="text" class="input w-full"
                    value="{{ $pengajuan_pinjaman->formatted_tanggal_pengajuan }}" readonly />
            </fieldset>

            {{-- Tanggal disetujui --}}
            @if ($pengajuan_pinjaman->status_disetujui)
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Disetujui Pada</legend>
                    <input type="text" class="input w-full"
                        value="{{ $pengajuan_pinjaman->formatted_tanggal_disetujui }}" readonly />
                </fieldset>
            @endif

            {{-- Tanggal di tolak --}}
            @if ($pengajuan_pinjaman->status_ditolak)
                {{-- Menampilkan tanggal ditolak jika statusnya sudah ditolak --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Tanggal ditolak</legend>
                    <input type="text" class="input w-full"
                        value="{{ $pengajuan_pinjaman->formatted_tanggal_ditolak }}" readonly />
                </fieldset>
            @endif

            {{-- Catatan proposal --}}
            @if ($pengajuan_pinjaman->catatan)
                {{-- Menampilkan catatan jika ada --}}
                <div class="grid md::col-span-2">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Catatan</legend>
                        <textarea class="textarea w-full" readonly>{{ $pengajuan_pinjaman->catatan }}</textarea>
                    </fieldset>
                </div>
            @endif
        </div>

        {{-- Bagian aksi persetujuan --}}
        @can('manage-pengajuan-pinjaman')
            <div
                class="grid {{ $pengajuan_pinjaman->status_dalam_proses_pengajuan ? 'md:grid-cols-2' : 'md:grid-cols-1 place-items-center' }} gap-4 my-8">
                @if ($pengajuan_pinjaman->status_dalam_proses_pengajuan)
                    {{-- Tombol review hanya muncul jika status pengajuan masih dalam proses --}}
                    <button href="{{ route('admin.users.index') }}" class="btn btn-primary"
                        onclick="window.open_modal('modal-review')">
                        Review
                    </button>
                @endif
                <a href="{{ route('admin.pengajuan-pinjaman.index') }}" class="btn btn-neutral">
                    Kembali
                </a>
            </div>
        @endcan
    </div>

    {{-- Memuat modal review dari file terpisah --}}
    @include('admin.pengajuan-pinjaman.review')
</x-layouts.admin-app>
