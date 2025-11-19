<x-layouts.admin-app title="Deposit saldo">

    {{-- Saldo saat ini --}}
    <div class="stats">
        <div class="stat bg-base-200">
            <div class="stat-title">Saldo rekening</div>
            <div class="stat-value text-primary">{{ $data_rekening->formatted_saldo }}</div>
        </div>
    </div>

    {{-- Form input deposit --}}
    <form action="{{ route('admin.rekening-akuntan.submit-deposit') }}" method="post" class="grid grid-cols-1 gap-4">
        @csrf
        @method('post')
        {{-- Nominal Deposit --}}
        <fieldset class="fieldset">
            <legend class="fieldset-legend">
                Nominal Deposit
                <small id="preview-deposit">Rp 0</small>
            </legend>
            <input type="number" placeholder="Masukan nominal deposit" name="nominal_deposit"
                oninput="window.preview_currency_element(this, 'preview-deposit')" min="1000000" max="900000000000000"
                class="input validator w-full" required>
            @error('nominal_deposit')
                <label class="fieldset-label text-error">{{ $message }}</label>
            @enderror
            <p class="validator-hint hidden">
                Nominal deposit wajib diisi.
                <br> Nominal deposit minimal bernilai Rp 1.000.000
            </p>
        </fieldset>
        {{-- Keterangan Deposit --}}
        <fieldset class="fieldset">
            <legend class="fieldset-legend">
                Keterangan Deposit
            </legend>
            <textarea type="text" placeholder="Masukan keterangan deposit" name="keterangan" class="textarea validator w-full"
                required></textarea>
            @error('keterangan')
                <label class="fieldset-label text-error">{{ $message }}</label>
            @enderror
            <p class="validator-hint hidden">
                Keterangan deposit wajib diisi.
            </p>
        </fieldset>
        <div class="grid place-items-center pt-4">
            <div class="flex flex-row gap-4">
                <button type="submit" class="btn btn-primary">Deposit</button>
                <a href="{{ route('admin.rekening-akuntan.index') }}" class="btn btn-neutral">Kembali</a>
            </div>
        </div>
    </form>
</x-layouts.admin-app>
