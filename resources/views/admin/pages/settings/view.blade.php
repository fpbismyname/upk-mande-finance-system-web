<x-layouts.admin-app title="Pengaturan" :breadcrumbs="$breadcrumbs">
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
                    </fieldset>
                    {{-- Email --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Email</legend>
                        <input type="text" class="input w-full validator" name="email"
                            value="{{ $current_user->email }}">
                        <p class="validator-hint hidden">
                            {{ __('validation.required', ['Attribute' => 'Email']) }}
                        </p>
                    </fieldset>
                    {{-- Nomor telepon --}}
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Nomor Telepon</legend>
                        <input type="text" class="input w-full validator" name="nomor_telepon" required
                            value="{{ $current_user->nomor_telepon }}">
                        <p class="validator-hint hidden">
                            {{ __('validation.required', ['Attribute' => 'Nomor telepon']) }}
                        </p>
                    </fieldset>
                    {{-- Reset password --}}
                    <div class="grid md:col-span-2 grid-cols-2 w-fit">
                        <label class="label">
                            <input type="checkbox" class="checkbox" name="reset_password"
                                onchange="window.toggle_hidden_element(this, 'fieldset_new_password', false)" />
                            Reset password
                        </label>
                    </div>
                    {{-- New password --}}
                    <div class="grid md:col-span-2" id="fieldset_new_password" hidden>
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Password baru</legend>
                            <input type="text" class="input w-full validator" name="new_password" value="">
                            <p class="validator-hint hidden">
                                {{ __('validation.required', ['Attribute' => 'Password baru']) }}
                            </p>
                        </fieldset>
                    </div>
                    {{-- Submit --}}
                    <div class="grid md:col-span-2 place-items-center">
                        <button class="btn btn-primary"
                            onclick="return confirm('Apakah anda yakin dengan perubahan ini ?')">Simpan
                            perubahan</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Suku bunga --}}
        <label class="tab gap-2">
            <input type="radio" name="settings_tab" class="tab"
                onclick="window.history.pushState({}, '', '?tab_settings=suku_bunga')"
                @if (request()->get('tab_settings') == 'suku_bunga') checked @endif />
            <x-lucide-percent class="w-4" />
            Suku bunga
        </label>
        <div class="tab-content border-base-300 bg-base-100 p-6">
            <form method="post" action="{{ route('admin.settings.save-changes', ['type_settings' => 'suku_bunga']) }}"
                class="flex flex-col justify-center">
                @csrf
                @method('put')
                <div class="grid md:grid-cols-2 gap-4">
                    {{-- Suku bunga flat --}}
                    <div class="grid md:col-span-2">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Suku bunga flat
                                <small id="preview-sukubunga">{{ $sukubunga->formatted_jumlah }}</small>
                            </legend>
                            <input type="number" class="input w-full" name="jumlah" value="{{ $sukubunga->jumlah }}"
                                oninput="window.preview_percentage_element(this, 'preview-sukubunga')">
                        </fieldset>
                    </div>
                    {{-- Submit --}}
                    <div class="grid md:col-span-2 place-items-center">
                        <button class="btn btn-primary"
                            onclick="return confirm('Apakah anda yakin dengan perubahan ini ?')">Simpan
                            perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin-app>
