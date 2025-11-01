<x-layouts.admin-app :title="'Edit ' . $anggota->name . ' | ' . $kelompok->name" :breadcrumbs="$breadcrumbs">
    <div class="flex flex-col gap-4">
        {{-- Add form --}}
        <form action="{{ route('admin.kelompok.anggota.update', [$id_kelompok, $id_anggota]) }}"
            class="grid grid-cols-1 gap-4 md:grid-cols-2" method="POST">
            @csrf
            @method('put')

            {{-- NIK --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">NIK</legend>
                <input type="text" name="nik" placeholder="Nomor Induk Kependudukan" class="input w-full validator"
                    minlength="16" maxlength="16" value="{{ $anggota->nik ?? old('nik') }}" required />
                <p class="validator-hint hidden">
                    {{ __('validation.required', ['attribute' => 'NIK']) }}
                    <br>{{ __('validation.min.string', ['Attribute' => 'NIK', 'min' => 16]) }}
                </p>
                @error('nik')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>

            {{-- Nama anggota --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama anggota</legend>
                <input type="text" name="name" placeholder="Nama anggota kelompok" class="input w-full validator"
                    minlength="4" value="{{ $anggota->name ?? old('name') }}" required />
                <p class="validator-hint hidden">
                    {{ __('validation.required', ['attribute' => 'Nama anggota']) }}
                    <br>{{ __('validation.min.string', ['Attribute' => 'Nama anggota', 'min' => 4]) }}
                </p>
                @error('name')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>

            {{-- Alamat --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Alamat</legend>
                <textarea name="alamat" placeholder="Alamat lengkap" class="textarea w-full validator" minlength="10" required>{{ $anggota->alamat ?? old('alamat') }}</textarea>
                <p class="validator-hint hidden">
                    {{ __('validation.required', ['attribute' => 'Alamat']) }}
                    <br>{{ __('validation.min.string', ['Attribute' => 'Alamat', 'min' => 10]) }}
                </p>
                @error('alamat')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>

            {{-- Nomor Telepon --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor Telepon</legend>
                <input type="tel" inputmode="tel" name="nomor_telepon" placeholder="Nomor telepon"
                    class="input w-full validator" minlength="10" maxlength="13"
                    value="{{ $anggota->nomor_telepon ?? old('nomor_telepon') }}" required />
                <p class="validator-hint hidden">
                    {{ __('validation.required', ['attribute' => 'Nomor telepon']) }}
                    <br>{{ __('validation.min.string', ['Attribute' => 'Nomor telepon', 'min' => 10]) }}
                </p>
                @error('nomor_telepon')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>

            {{-- Action button form --}}
            <div class="grid place-items-center md:col-span-2 pt-6">
                <div class="flex flex-row gap-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('crud.action.save') }}
                    </button>
                    <a href="{{ route('admin.kelompok.view', [$id_kelompok]) }}" class="btn btn-neutral">
                        <span>
                            {{ __('Back') }}
                        </span>
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-layouts.admin-app>
