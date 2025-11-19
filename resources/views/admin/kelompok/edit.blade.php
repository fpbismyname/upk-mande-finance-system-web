<x-layouts.admin-app title="Edit Kelompok">
    <div class="flex flex-col gap-4">
        {{-- Edit form --}}
        <form action="{{ route('admin.kelompok.update', [$kelompok->id]) }}" class="grid grid-cols-1 gap-2 md:grid-cols-2"
            method="POST">
            @csrf
            @method('put')
            {{-- Nama kelompok --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama kelompok</legend>
                <input type="text" name="name"
                    placeholder="Nama kelompok (contoh: kelompok mawar, kelompok anggrek, dsb.)"
                    class="input w-full validator" minlength="4" value="{{ old('nama_kelompok') ?? $kelompok->name }}"
                    required />
                <p class="validator-hint hidden">
                    Nama kelompok minimal berisi 6 karakter.
                </p>
                @error('name')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>

            {{-- limit pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">
                    Limit pinjaman per anggota
                    <small id="preview-limit-pinjaman">
                        {{ $kelompok->formatted_limit_per_anggota }}
                    </small>
                </legend>
                <input type="number" name="limit_per_anggota" placeholder="Limit pinjaman per anggota"
                    pattern="\s[0-9]" value="{{ old('limit_pinjaman') ?? $kelompok->limit_per_anggota }}" min="1000000"
                    max="9999999999999" class="input w-full validator"
                    oninput="window.preview_currency_element(this, 'preview-limit-pinjaman')" required />
                <p class="validator-hint hidden">
                    Limit pinjaman per anggota minimal berisi Rp 1.000.000.
                </p>
                @error('limit_per_anggota')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>

            {{-- Ketua kelompok --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Ketua kelompok</legend>
                <select name="users_id" class="select w-full validator" required>
                    <option value="" selected disabled>Pilih ketua kelompok</option>
                    @foreach ($list_ketua_kelompok as $user)
                        <option value="{{ $user->id }}" @if ($user->name == $kelompok->ketua_name) selected @endif>
                            {{ Str::of($user->name)->replace('_', ' ')->ucfirst() }}
                        </option>
                    @endforeach
                </select>
                @error('ketua_id')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>

            {{-- Status --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Status kelompok</legend>
                <select name="status" class="select validator w-full" required>
                    <option value="" selected disabled>Pilih status kelompok</option>
                    @foreach ($list_status as $value => $key)
                        <option value="{{ $value }}" @if ($value == $kelompok->status->value) selected @endif>
                            {{ $key }}
                        </option>
                    @endforeach
                </select>
                @error('status_id')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>

            {{-- Action button form --}}
            <div class="grid place-items-center md:col-span-2 pt-6">
                <div class="flex flex-row gap-4">
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                    <a href="{{ route('admin.kelompok.index') }}" class="btn btn-neutral">
                        Kembali
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-layouts.admin-app>
