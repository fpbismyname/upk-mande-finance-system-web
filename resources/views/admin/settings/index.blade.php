<x-layouts.admin-app title="Pengaturan">
    {{-- Tabs menu --}}
    <div class="tabs tabs-sm tabs-lift">
        {{-- Informasi pengguna --}}
        <label class="tab gap-2">
            <input type="radio" name="settings_tab" class="tab"
                onclick="window.history.pushState({}, '', '?tab_settings=informasi_akun')"
                @if (request()->get('tab_settings') == 'informasi_akun') checked @else checked @endif />
            <x-lucide-user class="w-4" />
            Informasi akun
        </label>
        <div class="tab-content border-base-300 bg-base-100 p-6">
            <form action="{{ route('admin.settings.save-changes', ['type_settings' => 'informasi_akun']) }}"
                method="post" class="flex flex-col justify-center">
                @csrf
                @method('put')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Username --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Nama akun</legend>
                        <input type="text" class="input w-full validator" name="name" placeholder="Nama pengguna"
                            required value="{{ $current_user->name }}">
                        <p class="validator-hint hidden">
                            {{ __('validation.required', ['Attribute' => 'Nama pengguna']) }}
                        </p>
                        @error('name')
                            <small class="text-error">{{ $message }}</small>
                        @enderror
                    </fieldset>
                    {{-- Email --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Email</legend>
                        <input type="text" class="input w-full validator" name="email"
                            value="{{ $current_user->email }}">
                        <p class="validator-hint hidden">
                            {{ __('validation.required', ['Attribute' => 'Email']) }}
                        </p>
                        @error('email')
                            <small class="text-error">{{ $message }}</small>
                        @enderror
                    </fieldset>
                    {{-- Nomor telepon --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Nomor Telepon</legend>
                        <input type="text" class="input w-full validator" name="nomor_telepon" required
                            value="{{ $current_user->nomor_telepon }}">
                        <p class="validator-hint hidden">
                            {{ __('validation.required', ['Attribute' => 'Nomor telepon']) }}
                        </p>
                        @error('nomor_telepon')
                            <small class="text-error">{{ $message }}</small>
                        @enderror
                    </fieldset>
                    {{-- Reset password --}}
                    <div class="grid md:col-span-2 grid-cols-2 w-fit">
                        <label class="label">
                            <input type="checkbox" class="checkbox" name="reset_password"
                                onchange="window.toggle_hidden_element(this, 'fieldset_new_password', false)" />
                            Reset password
                        </label>
                        @error('reset_password')
                            <small class="text-error">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- New password --}}
                    <div class="grid md:col-span-2" id="fieldset_new_password" hidden>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Password baru</legend>
                            <input type="text" class="input w-full validator" name="new_password" value="">
                            <p class="validator-hint hidden">
                                Password baru minimal terdiri dari 6 karakter.
                            </p>
                            @error('new_password')
                                <small class="text-error">{{ $message }}</small>
                            @enderror
                        </fieldset>
                    </div>
                    {{-- Submit --}}
                    <div class="grid md:col-span-2 place-items-end">
                        <button class="btn btn-primary"
                            onclick="return confirm('Apakah anda yakin dengan perubahan ini ?')"><x-lucide-save
                                class="w-4" />
                            Simpan perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Pinjaman --}}
        <label class="tab gap-2">
            <input type="radio" name="settings_tab" class="tab"
                onclick="window.history.pushState({}, '', '?tab_settings=pinjaman')"
                @if (request()->get('tab_settings') == 'pinjaman') checked @endif />
            <x-lucide-circle-dollar-sign class="w-4" />
            Pinjaman
        </label>
        <div class="tab-content border-base-300 bg-base-100 p-6">
            <form method="post" action="{{ route('admin.settings.save-changes', ['type_settings' => 'pinjaman']) }}"
                class="flex flex-col justify-center">
                @csrf
                @method('put')
                <div class="grid md:grid-cols-2 gap-4">
                    {{-- Bunga pinjaman --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Bunga pinjaman (%)</legend>
                        <input type="number" class="input w-full validator" name="bunga_pinjaman" min="0.1"
                            max="100" value="{{ $pinjaman_settings['bunga_pinjaman'] }}" required>
                        <p class="validator-hint hidden">
                            Bunga pinjaman wajib diisi.
                            <br> Bunga pinjaman minimal bernilai 0.1 - 100
                        </p>
                    </fieldset>
                    {{-- Limit pinjaman maksimal --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">
                            Limit pinjaman maksimal
                        </legend>
                        <input type="number" class="input w-full validator" name="limit_pinjaman_maksimal"
                            value="{{ $pinjaman_settings['limit_pinjaman_maksimal'] }}" min="10000000" max="100000000"
                            required>
                        <p class="validator-hint hidden">
                            Limit pinjaman wajib diisi.
                            <br> Limit pinjaman minimal bernilai Rp 10.000.000 - Rp 100.000.000.
                        </p>
                    </fieldset>
                    {{-- Kenaikan limit per jumlah pinjaman --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Kenaikan limit per jumlah pinjaman</legend>
                        <input type="number" class="input w-full validator" name="kenaikan_limit_per_jumlah_pinjaman"
                            value="{{ $pinjaman_settings['kenaikan_limit_per_jumlah_pinjaman'] }}" min="1"
                            max="100" required>
                        <p class="validator-hint hidden">
                            Kenaikan limit per jumlah pinjaman wajib diisi.
                            <br> Kenaikan limit per jumlah pinjaman minimal bernilai 1 - 100
                        </p>
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

        {{-- Cicilan --}}
        <label class="tab gap-2">
            <input type="radio" name="settings_tab" class="tab"
                onclick="window.history.pushState({}, '', '?tab_settings=cicilan')"
                @if (request()->get('tab_settings') == 'cicilan') checked @endif />
            <x-lucide-dollar-sign class="w-4" />
            Cicilan
        </label>
        <div class="tab-content border-base-300 bg-base-100 p-6">
            <form method="post" action="{{ route('admin.settings.save-changes', ['type_settings' => 'cicilan']) }}"
                class="flex flex-col justify-center">
                @csrf
                @method('put')
                <div class="grid md:grid-cols-2 gap-4">
                    {{-- Toleransi telat bayar --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Toleransi telat bayar (Hari)</legend>
                        <input type="number" class="input w-full validator" name="toleransi_telat_bayar"
                            min="1" max="90" value="{{ $cicilan_settings['toleransi_telat_bayar'] }}"
                            required>
                        <p class="validator-hint hidden">
                            Tolerasi telat bayar wajib diisi.
                            <br> Toleransi telat bayar tidak boleh lebih dari 90 hari
                        </p>
                    </fieldset>
                    {{-- Denda telat bayar --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Denda telat bayar (%)</legend>
                        <input type="number" class="input w-full validator" name="denda_telat_bayar" min="0.1"
                            max="100" value="{{ $cicilan_settings['denda_telat_bayar'] }}" required>
                        <p class="validator-hint hidden">
                            Denda telat bayar wajib diisi.
                            <br> Denda telat bayar minimal bernilai 0.1 - 100.
                        </p>
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

    </div>
</x-layouts.admin-app>
