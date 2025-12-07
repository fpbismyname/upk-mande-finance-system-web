{{-- Informasi pengguna --}}
<label class="tab gap-2" onclick="window.history.pushState(0,0,'?tab=account')">
    <input type="radio" name="settings_tab" class="tab"
        @if (request('tab') === 'account') checked @else checked @endif />
    <x-lucide-user class="w-4" />
    Informasi akun
</label>
<div class="tab-content border-base-300 bg-base-100 p-6">
    <form action="{{ route('admin.settings.account.save-changes') }}" method="post" class="flex flex-col justify-center">
        @csrf
        @method('put')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Username --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama akun</legend>
                <input type="text" class="input w-full validator" name="name" placeholder="Nama pengguna" required
                    value="{{ $data_user->name }}">
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
                <input type="text" class="input w-full validator" name="email" value="{{ $data_user->email }}">
                <p class="validator-hint hidden">
                    {{ __('validation.required', ['Attribute' => 'Email']) }}
                </p>
                @error('email')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>
            {{-- Nomor telepon --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor telepon</legend>
                <input type="text" class="input w-full validator" name="nomor_telepon"
                    value="{{ $data_user->pengajuan_keanggotaan_disetujui()->first()?->nomor_telepon }}">
                <p class="validator-hint hidden">
                    {{ __('validation.required', ['Attribute' => 'Nomor Telepon']) }}
                </p>
                @error('email')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>
            {{-- Nomor rekening --}}
            @can('has-bank-account-number')
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nomor rekening</legend>
                    <input type="text" class="input w-full validator" name="nomor_rekening" required minlength="10"
                        maxlength="16"
                        value="{{ $data_user->pengajuan_keanggotaan_disetujui()->first()->nomor_rekening }}">
                    <p class="validator-hint hidden">
                        Nomor rekening wajib diisi.
                        <br>Nomor rekening minimal terdiri dari 10-16 angka.
                    </p>
                    @error('nomor_rekening')
                        <small class="text-error">{{ $message }}</small>
                    @enderror
                </fieldset>
            @endcan
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
