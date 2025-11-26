<x-layouts.admin-app title="Edit Akun">
    <div class="flex flex-col gap-4">
        {{-- Edit form --}}
        <form action="{{ route('admin.users.update', [$user->id]) }}" class="grid grid-cols-1 gap-2 md:grid-cols-2"
            method="POST">
            @csrf
            @method('put')

            {{-- nik --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor NIK</legend>
                <input type="text" pattern="[0-9]{16}" minlength="16" maxlength="16" name="nik"
                    value="{{ $user->nik }}" placeholder="Nomor NIK" class="input w-full validator"
                    value="{{ old('nik') }}" required />
                <p class="validator-hint hidden">
                    Nomor NIK wajib diisi
                    <br> Nomor NIK harus terdiri dari minimal 16 digit
                </p>
                @error('nik')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>

            {{-- username --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama pengguna</legend>
                <input type="text" name="name" placeholder="Nama pengguna" class="input w-full validator"
                    value="{{ $user->name ?? '-' }}" required />
                <p class="validator-hint hidden">
                    Nama pengguna wajib diisi
                </p>
            </fieldset>

            {{-- email --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Email</legend>
                <input type="email" name="email" placeholder="Email" class="input w-full validator"
                    value="{{ $user->email ?? '-' }}" required />
                <p class="validator-hint hidden">
                    Alamat email tidak valid.
                </p>
            </fieldset>

            {{-- Nomor telepon --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor Whatsapp</legend>
                <input type="text" name="nomor_telepon" placeholder="Nomor Whatsapp" class="input w-full validator"
                    value="{{ $user->nomor_telepon ?? '' }}" pattern="[0-9]{12,16}" minlength="12" maxlength="16"
                    required />
                <p class="validator-hint hidden">
                    Nomor whatasapp wajib diisi
                    <br>Nomor whatsapp harus terdiri dari minimal 12 digit
                </p>
                @error('nomor_telepon')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>

            {{-- Nomor rekening --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor Rekening</legend>
                <input type="text" name="nomor_rekening" placeholder="Nomor Rekening" class="input w-full validator"
                    value="{{ $user->nomor_rekening ?? '' }}" pattern="[0-9]{10,16}" minlength="10" maxlength="16"
                    required />
                <p class="validator-hint hidden">
                    Nomor rekening wajib diisi
                    <br>Nomor rekening minimal terdiri dari 10-12 digit
                </p>
                @error('nomor_rekening')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>

            {{-- role --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Role</legend>
                <select name="role" class="select w-full">
                    @foreach ($list_role as $value => $key)
                        <option value="{{ $value }}" @if ($value === $user->role->value) selected @endif>
                            {{ $key }}
                        </option>
                    @endforeach
                </select>
            </fieldset>

            {{-- Alamat --}}
            <div class="grid md:col-span-2">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Alamat pengguna</legend>
                    <textarea name="alamat" placeholder="Alamat pengguna" class="textarea w-full validator" required>{{ $user->alamat }}</textarea>
                    <p class="validator-hint hidden">
                        Alamat pengguna wajib diisi
                    </p>
                    @error('alamat')
                        <p class="fieldset-label">{{ $message }}</p>
                    @enderror
                </fieldset>
            </div>

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
                        minlength="6">
                    <p class="validator-hint hidden">
                        Password baru minimal terdiri dari 6 karakter.
                    </p>
                    @error('new_password')
                        <small class="text-error">{{ $message }}</small>
                    @enderror
                </fieldset>
            </div>

            <div class="grid place-items-center md:col-span-2 pt-6">
                <div class="flex flex-row gap-4">
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-neutral">
                        <span>
                            Kembali
                        </span>
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-layouts.admin-app>
