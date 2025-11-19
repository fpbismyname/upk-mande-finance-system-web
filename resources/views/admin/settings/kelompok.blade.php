<label class="tab gap-2" onclick="window.history.pushState(0,0,'?tab=kelompok')">
    <input type="radio" name="settings_tab" class="tab" @if (request('tab') === 'kelompok') checked @endif />
    <x-lucide-users class="w-4" />
    Kelompok
</label>
<div class="tab-content border-base-300 bg-base-100 p-6">
    <form method="post" action="{{ route('admin.settings.kelompok.save-changes') }}" class="flex flex-col justify-center">
        @csrf
        @method('put')
        <div class="grid md:grid-cols-2 gap-4">
            {{-- Limit pinjaman maksimal --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Minimal anggota kelompok</legend>
                @can('manage-financial-settings')
                    <input type="number" class="input w-full validator" name="minimal_anggota_kelompok"
                        value="{{ $data_kelompok['minimal_anggota_kelompok'] }}" min="1" max="250" required>
                @else
                    <p>{{ $data_kelompok['minimal_anggota_kelompok'] }} Anggota</p>
                @endcan
                <p class="validator-hint hidden">
                    Minimal anggota kelompok maksimal wajib diisi.
                    <br> Minimal anggota kelompok minimal bernilai 1-250.
                </p>
                @error('minimal_anggota_kelompok')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Maksimal anggota kelompok</legend>
                @can('manage-financial-settings')
                    <input type="number" class="input w-full validator" name="maksimal_anggota_kelompok"
                        value="{{ $data_kelompok['maksimal_anggota_kelompok'] }}" min="1" max="250" required>
                @else
                    <p>{{ $data_kelompok['maksimal_anggota_kelompok'] }} Anggota</p>
                @endcan
                <p class="validator-hint hidden">
                    Maksimal anggota kelompok maksimal wajib diisi.
                    <br> Maksimal anggota kelompok minimal bernilai 1-250.
                </p>
                @error('maksimal_anggota_kelompok')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>
            {{-- Submit --}}
            @can('manage-financial-settings')
                <div class="grid md:col-span-2 place-items-end">
                    <button class="btn btn-primary"
                        onclick="return confirm('Apakah anda yakin dengan perubahan ini ?')"><x-lucide-save
                            class="w-4" />
                        Simpan perubahan
                    </button>
                </div>
            @endcan
        </div>
    </form>
</div>
