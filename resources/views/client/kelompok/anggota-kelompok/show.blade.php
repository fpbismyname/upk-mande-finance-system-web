<x-layouts.client-app title="Detail anggota kelompok">
    <div class="flex flex-col gap-4">
        <div class="flex flex-row">
            <div class="flex flex-col gap-2">
                <h3>Detail anggota kelompok</h3>
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            {{-- Nomor nik --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor NIK</legend>
                <input class="input w-full validator" type="text" maxlength="16" value="{{ $anggota_kelompok->nik }}"
                    readonly />
            </fieldset>
            {{-- Nama anggota --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama anggota</legend>
                <input class="input w-full validator" type="text" value="{{ $anggota_kelompok->name }}" readonly />
            </fieldset>
            {{-- Nomor telepon --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor telepon</legend>
                <input class="input w-full validator" type="text" value="{{ $anggota_kelompok->nomor_telepon }}"
                    readonly />
            </fieldset>
            {{-- Alamat --}}
            <div class="grid md:col-span-2">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Alamat</legend>
                    <textarea class="textarea w-full validator" type="text" readonly>{{ $anggota_kelompok->alamat }}</textarea>
                </fieldset>
            </div>
            <div class="grid md:col-span-2 place-items-center">
                <div class="flex flex-row gap-4">
                    <a href="{{ route('client.kelompok.index') }}" class="btn btn-neutral">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.client-app>
