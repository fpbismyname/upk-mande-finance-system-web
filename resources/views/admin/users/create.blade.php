<x-layouts.admin-app title="Tambah akun pengguna">
    <div class="flex flex-col gap-4">
        {{-- Add form --}}
        <form action="{{ route('admin.users.store') }}" class="grid grid-cols-1 gap-2 md:grid-cols-2" method="POST">
            @csrf
            @method('post')
            {{-- nik --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor NIK</legend>
                <input type="text" pattern="[0-9]{16}" minlength="16" maxlength="16" name="nik"
                    placeholder="Nomor NIK" class="input w-full validator" value="{{ old('nik') }}" required />
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
                    value="{{ old('name') }}" required />
                <p class="validator-hint hidden">
                    Nama pengguna wajib diisi
                </p>
                @error('name')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>

            {{-- email --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Email</legend>
                <input type="email" name="email" placeholder="Email" class="input w-full validator"
                    value="{{ old('email') }}" required />
                <p class="validator-hint hidden">
                    Alamat email tidak valid.
                </p>
                @error('email')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>

            {{-- Phone --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor Whatsapp</legend>
                <input type="text" name="nomor_telepon" placeholder="Nomor Whatsapp : 08xxxxxxxxxx"
                    class="input w-full validator" pattern="[0-9]{12,16}" value="{{ old('nomor_telepon') }}"
                    pattern="[0-9]{10,15}" minlength="12" maxlength="16" required />
                <p class="validator-hint hidden">
                    Nomor whatasapp wajib diisi
                    <br>Nomor whatsapp harus terdiri dari minimal 12 digit
                </p>
                @error('phone')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>

            {{-- Nomor Rekening --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor Rekening</legend>
                <input type="text" name="nomor_rekening" placeholder="Nomor Rekening" class="input w-full validator"
                    value="{{ old('nomor_rekening') }}" pattern="[0-9]{10,16}" minlength="10" maxlength="16" required />
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
                <select name="role" class="select w-full validator" required>
                    @if (!empty($list_role))
                        @foreach ($list_role as $value => $key)
                            <option value="{{ $value }}">
                                {{ $key }}
                            </option>
                        @endforeach
                    @else
                        <option value="" selected>{{ __('crud.no_data') }}</option>
                    @endif
                </select>
                <p class="validator-hint hidden">
                    Role wajib dipilih
                </p>
                @error('role_id')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>

            {{-- Alamat --}}
            <div class="grid md:col-span-2">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Alamat pengguna</legend>
                    <textarea name="alamat" class="textarea validator w-full" placeholder="Alamat pengguna" required>{{ old('alamat') }}</textarea>
                    <p class="validator-hint hidden">
                        Alamat pengguna wajib diisi
                    </p>
                    @error('alamat')
                        <p class="fieldset-label">{{ $message }}</p>
                    @enderror
                </fieldset>
            </div>

            {{-- new password --}}
            <div class="grid md:col-span-2">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Password baru</legend>
                    <x-ui.input-password name="password" placeholder="Password baru" class="validator" required
                        value="{{ old('password') }}" pattern="[\w\s]{6,250}" />
                    @error('password')
                        <p class="fieldset-label">{{ $message }}</p>
                    @enderror
                </fieldset>
            </div>

            {{-- Action button form --}}
            <div class="grid place-items-center md:col-span-2 pt-6">
                <div class="flex flex-row gap-4">
                    <button type="submit" class="btn btn-primary">
                        Buat
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
