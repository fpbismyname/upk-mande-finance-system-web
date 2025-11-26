    <label class="tab gap-2" onclick="window.history.pushState(0,0,'?tab=pinjaman')">
        <input type="radio" name="settings_tab" class="tab" @if (request('tab') === 'pinjaman') checked @endif />
        <x-lucide-user class="w-4" />
        Pinjaman
    </label>
    <div class="tab-content border-base-300 bg-base-100 p-6">
        <form method="post" action="{{ route('admin.settings.pinjaman.save-changes') }}"
            class="flex flex-col justify-center">
            @csrf
            @method('put')
            <div class="grid md:grid-cols-2 gap-4">
                {{-- Bunga pinjaman --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Bunga pinjaman (%)</legend>
                    @can('manage-financial-settings')
                        <input type="number" class="input w-full validator" name="bunga_pinjaman" min="1"
                            max="100" value="{{ $data_pinjaman['bunga_pinjaman'] }}" required>
                    @else
                        <p>{{ $data_pinjaman['bunga_pinjaman'] }}%</p>
                    @endcan
                    <p class="validator-hint hidden">
                        Bunga pinjaman wajib diisi.
                        <br> Bunga pinjaman minimal bernilai 0.1 - 100
                    </p>
                    @error('bunga_pinjaman')
                        <small class="text-error">{{ $message }}</small>
                    @enderror
                </fieldset>
                {{-- Kenaikan limit per jumlah pinjaman --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Kenaikan limit per jumlah pinjaman</legend>
                    @can('manage-financial-settings')
                        <input type="number" class="input w-full validator" name="kenaikan_limit_per_jumlah_pinjaman"
                            value="{{ $data_pinjaman['kenaikan_limit_per_jumlah_pinjaman'] }}" min="1" max="100"
                            required>
                    @else
                        <p>{{ $data_pinjaman['kenaikan_limit_per_jumlah_pinjaman'] }} pinjaman</p>
                    @endcan
                    <p class="validator-hint hidden">
                        Kenaikan limit per jumlah pinjaman wajib diisi.
                        <br> Kenaikan limit per jumlah pinjaman minimal bernilai 1 - 100
                    </p>
                    @error('kenaikan_limit_per_jumlah_pinjaman')
                        <small class="text-error">{{ $message }}</small>
                    @enderror
                </fieldset>
                {{-- Limit pinjaman minimal --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Limit pinjaman minimal (Rp)</legend>
                    @can('manage-financial-settings')
                        <input type="number" class="input w-full validator" name="minimal_limit_pinjaman"
                            value="{{ $data_pinjaman['minimal_limit_pinjaman'] }}" min="1000000" max="10000000" required>
                    @else
                        <p>Rp {{ number_format($data_pinjaman['minimal_limit_pinjaman'], 0, ',', '.') }}</p>
                    @endcan
                    <p class="validator-hint hidden">
                        Limit pinjaman minimal wajib diisi.
                        <br> Limit pinjaman minimal bernilai Rp 1.000.000 - Rp 10.000.000.
                    </p>
                    @error('minimal_limit_pinjaman')
                        <small class="text-error">{{ $message }}</small>
                    @enderror
                </fieldset>
                {{-- Limit pinjaman maksimal --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Limit pinjaman maksimal (Rp)</legend>
                    @can('manage-financial-settings')
                        <input type="number" class="input w-full validator" name="maksimal_limit_pinjaman"
                            value="{{ $data_pinjaman['maksimal_limit_pinjaman'] }}" min="1000000" max="100000000"
                            required>
                    @else
                        <p>Rp {{ number_format($data_pinjaman['maksimal_limit_pinjaman'], 0, ',', '.') }}</p>
                    @endcan
                    <p class="validator-hint hidden">
                        Limit pinjaman maksimal wajib diisi.
                        <br> Limit pinjaman minimal bernilai Rp 1.000.000 - Rp 10.000.000.
                    </p>
                    @error('maksimal_limit_pinjaman')
                        <small class="text-error">{{ $message }}</small>
                    @enderror
                </fieldset>
                {{-- Toleransi telat bayar --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Toleransi telat bayar cicilan pinjaman (hari)</legend>
                    @can('manage-financial-settings')
                        <input type="number" class="input w-full validator" name="toleransi_telat_bayar" min="1"
                            max="240" value="{{ $data_pinjaman['toleransi_telat_bayar'] }}" required>
                    @else
                        <p>{{ $data_pinjaman['toleransi_telat_bayar'] }} hari</p>
                    @endcan
                    <p class="validator-hint hidden">
                        Toleransi telat bayar wajib diisi.
                        <br> Toleransi telat bayar minimal bernilai 1 - 240 hari
                    </p>
                    @error('toleransi_telat_bayar')
                        <small class="text-error">{{ $message }}</small>
                    @enderror
                </fieldset>
                {{-- Submit --}}
                @can('manage-financial-settings')
                    <div class="grid md:col-span-2 place-items-end">
                        <button class="btn btn-primary"
                            onclick="return confirm('Apakah anda yakin dengan perubahan ini ?')"><x-lucide-save
                                class="w-4" />
                            Simpan perubahan
                        </button>
                    </div>
                @endcan
            </div>
        </form>
    </div>
