<x-layouts.admin-app :title="'Detail ' . $anggota->name . ' | ' . $kelompok->name" :breadcrumbs="$breadcrumbs">
    <div class="flex flex-col gap-4">
        {{-- Add form --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            {{-- NIK --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">NIK</legend>
                <input type="text" name="nik" value="{{ $anggota->nik }}" class="input w-full" readonly />
            </fieldset>

            {{-- Nama anggota --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama anggota</legend>
                <input type="text" name="name" value="{{ $anggota->name }}" class="input w-full" readonly />
            </fieldset>

            {{-- Alamat --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Alamat</legend>
                <textarea name="alamat" class="textarea w-full" readonly>{{ $anggota->alamat }}</textarea>
            </fieldset>

            {{-- Nomor Telepon --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor Telepon</legend>
                <input type="tel" name="nomor_telepon" value="{{ $anggota->nomor_telepon }}" class="input w-full"
                    readonly />
            </fieldset>

            {{-- Tanggal Bergabung --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Tanggal Bergabung</legend>
                <input type="text" name="tanggal_bergabung"
                    value="{{ $anggota->created_at->format('d M Y | h:m ') }}" class="input w-full" readonly />
            </fieldset>

            {{-- Action button form --}}
            <div class="grid place-items-center md:col-span-2 pt-6">
                <a href="{{ route('admin.kelompok.view', [$id_kelompok]) }}" class="btn btn-neutral">
                    <span>{{ __('Back') }}</span>
                </a>
            </div>
        </div>
    </div>
</x-layouts.admin-app>
