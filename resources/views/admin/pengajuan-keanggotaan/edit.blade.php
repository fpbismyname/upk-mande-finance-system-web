<x-layouts.admin-app title="Review pengajuan keanggotaan">
    <div class="flex flex-col gap-4">
        {{-- Data pengajuan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- NIK --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">NIK</legend>
                <p>{{ $pengajuan_keanggotaan->nik }}</p>
            </fieldset>

            {{-- KTP --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">KTP</legend>
                <a class="link link-primary link-hover" target="_blank"
                    href="{{ route('storage.private.get', ['path' => $pengajuan_keanggotaan->ktp]) }}">Lihat
                    selengkapnya</a>
            </fieldset>

            {{-- Nama pengguna --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama pengguna</legend>
                <p>{{ $pengajuan_keanggotaan->nama_lengkap }}</p>
            </fieldset>

            {{-- Alamat --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Alamat</legend>
                <p class="whitespace-pre-line">{{ $pengajuan_keanggotaan->alamat }}</p>
            </fieldset>

            {{-- Nomor rekening --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor rekening</legend>
                <p>{{ $pengajuan_keanggotaan->nomor_rekening }}</p>
            </fieldset>

            {{-- Nomor Telepon --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor telepon</legend>
                <p>{{ $pengajuan_keanggotaan->nomor_telepon }}</p>
            </fieldset>

            {{-- Status pengajuan --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Status pengajuan</legend>
                <p>{{ $pengajuan_keanggotaan->status->label() }}</p>
            </fieldset>

        </div>

        {{-- Bagian aksi persetujuan --}}
        @can('manage-users')
            <div
                class="grid {{ $pengajuan_keanggotaan->status_proses_pengajuan ? 'md:grid-cols-2' : 'md:grid-cols-1 place-items-center' }} gap-4 my-8">
                @if ($pengajuan_keanggotaan->status_proses_pengajuan)
                    {{-- Tombol review hanya muncul jika status pengajuan masih dalam proses --}}
                    <button href="{{ route('admin.users.index') }}" class="btn btn-primary"
                        onclick="window.open_modal('modal-review')">
                        Review
                    </button>
                @endif
                <a href="{{ route('admin.pengajuan-keanggotaan.index') }}" class="btn btn-neutral">
                    Kembali
                </a>
            </div>
        @else
            <div class="grid place-items-center">
                <a href="{{ route('admin.pengajuan-keanggotaan.index') }}" class="btn btn-neutral">
                    Kembali
                </a>
            </div>
        @endcan
    </div>

    {{-- Memuat modal review dari file terpisah --}}
    @include('admin.pengajuan-keanggotaan.review')
</x-layouts.admin-app>
