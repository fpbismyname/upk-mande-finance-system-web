<x-layouts.client-app title="Tambah anggota kelompok">
    <div class="flex flex-col gap-4">
        <div class="flex flex-row">
            <div class="flex flex-col gap-2">
                <h3>Tambah anggota kelompok</h3>
            </div>
        </div>
        <form action="{{ route('client.kelompok.anggota-kelompok.store', [$kelompok->id]) }}" method="post"
            class="grid md:grid-cols-2 gap-4">
            @csrf
            @method('post')
            {{-- Nomor nik --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor NIK</legend>
                <input class="input w-full validator" type="text" pattern="[0-9]{16}" name="nik" minlength="3"
                    maxlength="16" required />
                <p class="validator-hint hidden">
                    Nomor NIK wajib diisi.
                    <br> Nomor NIK tidak boleh lebih dari 16 digit.
                </p>
                @error('nik')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>
            {{-- Nama anggota --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama anggota</legend>
                <input class="input w-full validator" type="text" name="name" required />
                <p class="validator-hint hidden">
                    Nama anggota wajib diisi.
                </p>
                @error('name')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>
            {{-- Nomor telepon --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor telepon</legend>
                <input class="input w-full validator" type="text" name="nomor_telepon" pattern="[0-9]{10,16}"
                    minlength="10" maxlength="16" required />
                <p class="validator-hint hidden">
                    Nomor telepon wajib diisi.
                    <br> Nomor telepon harus berupa angka.
                    <br> Nomor telepon minimal terdiri dari 10 digit.
                </p>
                @error('nomor_telepon')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>
            {{-- Alamat --}}
            <div class="grid md:col-span-2">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Alamat</legend>
                    <textarea class="textarea w-full validator" type="text" name="alamat" required></textarea>
                    <p class="validator-hint hidden">
                        Alamat wajib diisi.
                    </p>
                    @error('alamat')
                        <small class="text-error">{{ $message }}</small>
                    @enderror
                </fieldset>
            </div>
            <div class="grid md:col-span-2 place-items-center">
                <div class="flex flex-row gap-4">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                    <a href="{{ route('client.kelompok.index') }}" class="btn btn-neutral">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</x-layouts.client-app>
