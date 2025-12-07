<x-layouts.admin-app title="Buat kelompok upk">
    <div class="flex flex-col gap-4">
        {{-- Add form --}}
        @if ($list_ketua_kelompok->isNotEmpty())
            <form action="{{ route('admin.kelompok.store') }}" class="grid grid-cols-1 gap-2 md:grid-cols-2"
                method="POST">
                @csrf
                @method('post')
                {{-- Nama kelompok --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nama kelompok</legend>
                    <input type="text" name="name"
                        placeholder="Nama kelompok (contoh: kelompok mawar, kelompok anggrek, dsb.)"
                        class="input w-full validator" minlength="6" value="{{ old('name') }}" required />
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
                        Limit pinjaman
                        <small id="preview-limit-pinjaman">{{ __('Rp 0' ?? '') }}</small>
                    </legend>
                    <input type="number" name="limit_per_anggota" placeholder="Limit pinjaman" pattern="\s[0-9]"
                        value="{{ old('limit_per_anggota') ?? 0 }}" min="1000000" max="9999999999999"
                        class="input w-full validator"
                        oninput="window.preview_currency_element(this, 'preview-limit-pinjaman')" required />
                    <p class="validator-hint hidden">
                        Limit pinjaman minimal berisi Rp 1.000.000.
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
                            <option value="{{ $user->id }}">
                                {{ Str::of($user->name)->replace('_', ' ')->ucfirst() }}
                            </option>
                        @endforeach
                    </select>
                    @error('users_id')
                        <small class="text-error">{{ $message }}</small>
                    @enderror
                </fieldset>

                {{-- Status --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Status kelompok</legend>
                    <select name="status" class="select validator w-full" required>
                        <option value="" selected disabled>Pilih status kelompok</option>
                        @foreach ($list_status as $value => $key)
                            <option value="{{ $value }}">
                                {{ $key }}
                            </option>
                        @endforeach
                    </select>
                    <p class="validator-hint hidden">
                        {{ __('validation.required', ['attribute' => 'Status kelompok']) }}
                    </p>
                    @error('status_id')
                        <p class="fieldset-label">{{ $message }}</p>
                    @enderror
                </fieldset>

                {{-- Action button form --}}
                <div class="grid place-items-center md:col-span-2 pt-6">
                    <div class="flex flex-row gap-4">
                        <button type="submit" class="btn btn-primary"
                            @if (empty($list_ketua_kelompok)) disabled @endif>
                            {{ __('crud.action.create') }}
                        </button>
                        <a href="{{ route('admin.kelompok.index') }}" class="btn btn-neutral">
                            <span>
                                {{ __('Back') }}
                            </span>
                        </a>
                    </div>
                </div>
            </form>
        @else
            <div class="grid place-items-center md:col-span-2 pt-6 gap-4">
                <h6>Ketua anggota tidak tersedia, silahkan tambahkan terlebih dahulu.</h6>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <x-lucide-user-plus class="w-4" />
                    Tambah ketua anggota baru
                </a>
            </div>
        @endif
    </div>
</x-layouts.admin-app>
