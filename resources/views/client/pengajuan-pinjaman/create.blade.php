<x-layouts.client-app title="Buat pengajuan pinjaman">
    <div class="flex flex-row">
        <div class="flex flex-col">
            <h3>Buat pengajuan pinjaman</h3>
        </div>
    </div>
    <div class="flex flex-col">
        <form action="{{ route('client.pengajuan-pinjaman.store') }}" class="grid grid-cols-2 gap-4" method="post"
            enctype="multipart/form-data" enctype="multipart/form-data">
            @csrf
            @method('post')

            <!-- Name Input -->
            <fieldset class="fieldset">
                <legend class="fieldset-legend">
                    Nominal pengajuan
                    <small id="preview-nominal">Rp 0</small>
                </legend>
                <input type="number" min="1000000" max="{{ $kelompok->decimal_limit_pinjaman_kelompok }}"
                    name="nominal_pinjaman" class="input w-full validator"
                    oninput="window.preview_currency_element(this, 'preview-nominal')"
                    value="{{ old('nominal_pinjaman') }}" required />
                <p class="validator-hint hidden">
                    Nominal pinjaman minimal Rp 1.000.000 - {{ $kelompok->formatted_limit_pinjaman_kelompok }}
                </p>
                @error('nominal_pinjaman')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>

            <!-- File Proposal -->
            <fieldset class="fieldset">
                <legend class="fieldset-legend">File Proposal</legend>
                <input type="file" name="file_proposal" class="file-input w-full validator" accept=".pdf"
                    required />
                <p class="validator-hint hidden">
                    File proposal wajib diupload.
                    <br> File proposal harus berformat PDF
                </p>
                @error('file_proposal')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>

            <!-- Tenor pinjaman -->
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Tenor pinjaman</legend>
                <select name="tenor" class="select w-full validator" required>
                    <option value="" selected disabled>Pilih tenor</option>
                    @foreach ($list_tenor as $value => $key)
                        <option value="{{ $value }}" @selected(old('tenor') == $value)>{{ $key }}</option>
                    @endforeach
                </select>
                <p class="validator-hint hidden">
                    Tenor pinjaman wajib diisi.
                </p>
                @error('tenor')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>

            <div class="grid place-items-center md:col-span-2">
                <div class="flex flex-row gap-4">
                    <button type class="btn btn-primary">Kirim</button>
                    <a href="{{ route('client.pengajuan-pinjaman.index') }}" class="btn btn-neutral">Kembali</a>
                </div>
            </div>

        </form>
    </div>
</x-layouts.client-app>
