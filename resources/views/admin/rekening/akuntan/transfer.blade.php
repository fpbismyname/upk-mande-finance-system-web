<x-layouts.admin-app title="Transfer saldo ke pendanaan">
    {{-- Saldo saat ini --}}
    <div class="stats">
        <div class="stat bg-base-200">
            <div class="stat-title">Saldo rekening</div>
            <div class="stat-value text-primary">{{ $data_rekening->formatted_saldo }}</div>
        </div>
    </div>
    {{-- Form input transfer --}}
    <form action="{{ route('admin.rekening-akuntan.submit-transfer') }}" method="post" class="grid grid-cols-1 gap-4">
        @csrf
        @method('post')
        {{-- Nominal Transfer --}}
        <fieldset class="fieldset">
            <legend class="fieldset-legend">
                Nominal Transfer
                <small id="preview-transfer">Rp 0</small>
            </legend>
            <input type="number" placeholder="Masukan nominal transfer" name="nominal_transfer"
                oninput="window.preview_currency_element(this, 'preview-transfer')" min="100000"
                max="{{ $data_rekening->saldo }}" class="input validator w-full" required>
            <p class="validator-hint hidden">
                Nominal transfer wajib diisi.
                <br>Nominal transfer minimal bernilai Rp 100.000
            </p>
            @error('nominal_transfer')
                <label class="fieldset-label text-error">{{ $message }}</label>
            @enderror
        </fieldset>
        {{-- Keterangan transfer --}}
        <fieldset class="fieldset">
            <legend class="fieldset-legend">
                Keterangan transfer
            </legend>
            <textarea type="text" placeholder="Masukan keterangan transfer" name="keterangan" class="textarea validator w-full"
                required></textarea>
            @error('keterangan')
                <label class="fieldset-label text-error">{{ $message }}</label>
            @enderror
            <p class="validator-hint hidden">
                Keterangan transfer wajib diisi.
            </p>
        </fieldset>
        <div class="grid place-items-center pt-4">
            <div class="flex flex-row gap-4">
                <button type="submit" class="btn btn-primary">transfer</button>
                <a href="{{ route('admin.rekening-akuntan.index') }}" class="btn btn-neutral">Kembali</a>
            </div>
        </div>
    </form>
</x-layouts.admin-app>
