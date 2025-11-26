<x-layouts.client-app title="Bayar cicilan pinjaman">
    <div class="flex flex-row">
        <div class="flex flex-col">
            <h3>Kirim bukti pembayaran cicilan kelompok</h3>
        </div>
    </div>
    <div class="flex flex-col">
        <form
            action="{{ route('client.pinjaman-kelompok.cicilan-kelompok.update', [$id_pinjaman, $cicilan_kelompok->id]) }}"
            class="grid md:grid-cols-2 gap-4" method="post" enctype="multipart/form-data" enctype="multipart/form-data">
            @csrf
            @method('put')
            <!-- Nominal cicilan -->
            <fieldset class="fieldset">
                <legend class="fieldset-legend">
                    Nominal cicilan
                </legend>
                <p>{{ $cicilan_kelompok->formatted_nominal_cicilan }}</p>
            </fieldset>
            <!-- Tanggal jatuh tempo -->
            <fieldset class="fieldset">
                <legend class="fieldset-legend">
                    Tanggal jatuh tempo
                </legend>
                <p>{{ $cicilan_kelompok->formatted_tanggal_jatuh_tempo }}</p>
            </fieldset>
            <!-- Bukti pembayaran -->
            <fieldset class="fieldset">
                <legend class="fieldset-legend">
                    Bukti pembayaran
                </legend>
                <input type="file" name="bukti_pembayaran" class="file-input w-full validator" required />
                <p class="validator-hint hidden">
                    Bukti pembayaran wajib diupload.
                </p>
                @error('bukti_pembayaran')
                    <small class="text-error">{{ $message }}</small>
                @enderror
            </fieldset>

            <div class="grid place-items-center md:col-span-2">
                <div class="flex flex-row gap-4">
                    <button type class="btn btn-primary">Kirim bukti pembayaran</button>
                    <a href="{{ route('client.pinjaman-kelompok.show', [$pinjaman_kelompok->id]) }}"
                        class="btn btn-neutral">Kembali</a>
                </div>
            </div>

        </form>
    </div>
</x-layouts.client-app>
