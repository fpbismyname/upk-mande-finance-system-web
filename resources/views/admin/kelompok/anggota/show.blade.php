<x-layouts.admin-app title="Detail anggota kelompok">
    <div class="flex flex-col gap-4">
        {{-- Add form --}}
        <div class="card bg-base-200">
            <div class="card-body grid grid-cols-1 gap-4 md:grid-cols-2">
                {{-- NIK --}}
                <fieldset class="fieldset flex flex-col gap-2">
                    <legend class="fieldset-legend">NIK</legend>
                    <p>{{ $anggota_kelompok->nik }}</p>
                </fieldset>

                {{-- Nama anggota --}}
                <fieldset class="fieldset flex flex-col gap-2">
                    <legend class="fieldset-legend">Nama anggota</legend>
                    <p>{{ $anggota_kelompok->name }}</p>
                </fieldset>

                {{-- Alamat --}}
                <fieldset class="fieldset flex flex-col gap-2">
                    <legend class="fieldset-legend">Alamat</legend>
                    <p>{{ $anggota_kelompok->alamat }}</p>
                </fieldset>

                {{-- Nomor Telepon --}}
                <fieldset class="fieldset flex flex-col gap-2">
                    <legend class="fieldset-legend">Nomor Telepon</legend>
                    <p>{{ $anggota_kelompok->nomor_telepon }}</p>
                </fieldset>

                {{-- Tanggal Bergabung --}}
                <fieldset class="fieldset flex flex-col gap-2">
                    <legend class="fieldset-legend">Tanggal Bergabung</legend>
                    <p>{{ $anggota_kelompok->tanggal_bergabung }}</p>
                </fieldset>

                {{-- Action button form --}}
                <div class="grid place-items-center md:col-span-2 pt-6">
                    <a href="{{ route('admin.kelompok.show', [$id_kelompok]) }}" class="btn btn-neutral">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin-app>
