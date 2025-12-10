<x-layouts.client-app title="Edit pengajuan pinjaman">
    <div class="flex flex-row justify-between items-center">
        <div class="flex flex-col">
            <h3>Edit pengajuan pinjaman</h3>
        </div>
    </div>
    <form action="{{ route('client.pengajuan-keanggotaan.update', [$pengajuan_keanggotaan->id]) }}" method="post"
        enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="flex flex-col gap-4">
            <div class="grid md:grid-cols-2 gap-2">

                {{-- nik --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nomor NIK</legend>
                    <input type="text" pattern="[0-9]{16}" minlength="16" maxlength="16" name="nik"
                        placeholder="Nomor NIK" class="input w-full validator"
                        value="{{ old('nik', $pengajuan_keanggotaan->nik) }}" required />
                    <p class="validator-hint hidden">
                        Nomor NIK wajib diisi
                        <br> Nomor NIK harus terdiri dari minimal 16 digit
                    </p>
                    @error('nik')
                        <p class="fieldset-label">{{ $message }}</p>
                    @enderror
                </fieldset>

                {{-- ktp --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Ktp (Upload untuk mengubah data ktp)</legend>
                    <input type="file" class="file-input validator w-full" name="ktp" />
                    <p class="validator-hint hidden">
                        Ktp wajib dilampirkan
                    </p>
                    @error('ktp')
                        <p class="fieldset-label">{{ $message }}</p>
                    @enderror
                </fieldset>

                {{-- nama lengkap --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nama lengkap</legend>
                    <input type="text" name="nama_lengkap" placeholder="Nama lengkap" class="input w-full validator"
                        value="{{ old('nama_lengkap', $pengajuan_keanggotaan->nama_lengkap) }}" required />
                    <p class="validator-hint hidden">
                        Nama lengkap wajib diisi
                    </p>
                    @error('nama_lengkap')
                        <p class="fieldset-label">{{ $message }}</p>
                    @enderror
                </fieldset>

                {{-- Phone --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nomor Whatsapp</legend>
                    <input type="text" name="nomor_telepon" placeholder="Nomor Whatsapp : 08xxxxxxxxxx"
                        class="input w-full validator" pattern="[0-9]{12,16}"
                        value="{{ old('nomor_telepon', $pengajuan_keanggotaan->nomor_telepon) }}" pattern="[0-9]{10,15}"
                        minlength="12" maxlength="16" required />
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
                    <input type="text" name="nomor_rekening" placeholder="Nomor Rekening"
                        class="input w-full validator"
                        value="{{ old('nomor_rekening', $pengajuan_keanggotaan->nomor_rekening) }}"
                        pattern="[0-9]{10,16}" minlength="10" maxlength="16" required />
                    <p class="validator-hint hidden">
                        Nomor rekening wajib diisi
                        <br>Nomor rekening minimal terdiri dari 10-12 digit
                    </p>
                    @error('nomor_rekening')
                        <p class="fieldset-label">{{ $message }}</p>
                    @enderror
                </fieldset>

                {{-- Alamat --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Alamat pengguna</legend>
                    <textarea name="alamat" class="textarea validator w-full" placeholder="Alamat pengguna" required>{{ old('alamat', $pengajuan_keanggotaan->alamat) }}</textarea>
                    <p class="validator-hint hidden">
                        Alamat pengguna wajib diisi
                    </p>
                    @error('alamat')
                        <p class="fieldset-label">{{ $message }}</p>
                    @enderror
                </fieldset>

            </div>

            <div class="grid place-items-center md:col-span-2">
                <div class="flex flex-row gap-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('client.pengajuan-keanggotaan.show', [$pengajuan_keanggotaan->id]) }}"
                        class="btn btn-neutral">Kembali</a>
                </div>
            </div>
        </div>
    </form>

</x-layouts.client-app>
