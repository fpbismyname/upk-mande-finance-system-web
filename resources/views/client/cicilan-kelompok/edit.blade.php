<x-layouts.client-app title="Bayar cicilan pinjaman">
    <div class="flex flex-row">
        <div class="flex flex-col">
            <h3>Bayar cicilan kelompok</h3>
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
                <input type="text" class="input w-full" value="{{ $cicilan_kelompok->formatted_nominal_cicilan }}"
                    readonly />
            </fieldset>
            <!-- Tanggal jatuh tempo -->
            <fieldset class="fieldset">
                <legend class="fieldset-legend">
                    Tanggal jatuh tempo
                </legend>
                <input type="text" class="input w-full"
                    value="{{ $cicilan_kelompok->formatted_tanggal_jatuh_tempo }}" readonly />
            </fieldset>
            <!-- Denda telat bayar -->
            @if ($cicilan_kelompok->status_telat_bayar)
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        Denda telat bayar (perhari telat bayar)
                    </legend>
                    <input type="text" class="input w-full"
                        value="{{ $cicilan_kelompok->formatted_denda_telat_bayar }}" readonly />
                </fieldset>
            @endif
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
                    <button type class="btn btn-primary">Bayar</button>
                    <a href="{{ route('client.pinjaman-kelompok.show', [$pinjaman_kelompok->id]) }}"
                        class="btn btn-neutral">Kembali</a>
                </div>
            </div>

        </form>
    </div>
</x-layouts.client-app>
