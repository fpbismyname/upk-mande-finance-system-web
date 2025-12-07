<x-layouts.client-app class="Pengaturan Akun">
    <form action="{{ route('client.settings.save-changes', ['type_settings' => 'informasi_akun']) }}" method="post"
        enctype="multipart/form-data" class="flex flex-col justify-center">
        @csrf
        @method('put')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="grid md:col-span-2">
                <h6>Data Akun</h6>
            </div>
            {{-- nik --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor NIK</legend>
                <input type="text" pattern="[0-9]{16}" minlength="16" maxlength="16" name="nik"
                    value="{{ $current_user->pengajuan_keanggotaan_disetujui->first()->nik }}" placeholder="Nomor NIK"
                    class="input w-full validator" value="{{ old('nik') }}" required />
                <p class="validator-hint hidden">
                    <br> Nomor NIK harus terdiri dari minimal 16 digit
                </p>
                @error('nik')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>
            {{-- Nomor Rekening --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor Rekening</legend>
                <input type="text" pattern="[0-9]{16}" minlength="10" maxlength="16" name="nomor_rekening"
                    value="{{ $current_user->pengajuan_keanggotaan_disetujui->first()->nomor_rekening }}"
                    placeholder="Nomor Rekening" class="input w-full validator" value="{{ old('nik') }}" required />
                <p class="validator-hint hidden">
                    <br> Nomor Rekening harus terdiri dari minimal 10-16 digit
                </p>
                @error('nomor_rekening')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>

            {{-- Nama lengkap --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama lengkap</legend>
                <input type="text" class="input w-full validator" name="nama_lengkap" placeholder="Nama lengkap"
                    required value="{{ $current_user->name }}">
                <p class="validator-hint hidden">
                    {{ __('validation.required', ['Attribute' => 'Nama lengkap']) }}
                </p>
                @error('nama_lengkap')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>

            {{-- Nomor telepon --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor Telepon</legend>
                <input type="text" class="input w-full validator" name="nomor_telepon" required
                    value="{{ $current_user->pengajuan_keanggotaan_disetujui()->first()->nomor_telepon }}">
                <p class="validator-hint hidden">
                    {{ __('validation.required', ['Attribute' => 'Nomor telepon']) }}
                </p>
                @error('nomor_telepon')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>

            {{-- Ktp --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Ktp (Lampirkan ktp untuk mengubah ktp)</legend>
                <a target="_blank" class="link link-hover text-sm w-fit link-primary"
                    href="{{ route('storage.private.get', ['path' => $current_user->pengajuan_keanggotaan_disetujui()->first()->ktp]) }}">Lihat
                    selengkapnya</a>
                <input type="file" class="file-input w-full" name="ktp" />
                <p class="validator-hint hidden">
                    {{ __('validation.required', ['Attribute' => 'Nomor telepon']) }}
                </p>
                @error('ktp')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>

            {{-- Alamat --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Alamat</legend>
                <textarea type="text" class="textarea w-full validator" name="alamat" required>{{ $current_user->pengajuan_keanggotaan_disetujui()->first()->alamat }}</textarea>
                <p class="validator-hint hidden">
                    Alamat wajib diisi.
                </p>
                @error('alamat')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>

        </div>

        <div class="divider"></div>

        <div class="grid md:grid-cols-2 gap-4">
            <div class="grid md:col-span-2">
                <h6>Data keanggotaan</h6>
            </div>


            {{-- Nama akun --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama akun</legend>
                <input type="text" class="input w-full validator" name="name" placeholder="Nama pengguna" required
                    value="{{ $current_user->name }}">
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
                <input type="text" class="input w-full validator" name="email" value="{{ $current_user->email }}">
                <p class="validator-hint hidden">
                    {{ __('validation.required', ['Attribute' => 'Email']) }}
                </p>
                @error('email')
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
                    <input type="text" class="input w-full validator" name="new_password" value=""
                        pattern="\w{6,255}" minlength="6">
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
</x-layouts.client-app>
