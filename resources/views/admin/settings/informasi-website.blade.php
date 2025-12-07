    <label class="tab gap-2" onclick="window.history.pushState(0,0,'?tab=informasi_website')">
        <input type="radio" name="settings_tab" class="tab" @if (request('tab') === 'informasi_website') checked @endif />
        <x-lucide-globe class="w-4" />
        Informasi website
    </label>
    <div class="tab-content border-base-300 bg-base-100 p-6">
        <form method="post" action="{{ route('admin.settings.informasi_website.save-changes') }}"
            class="flex flex-col justify-center">
            @csrf
            @method('put')
            <div class="grid md:grid-cols-2 gap-4">
                {{-- Syarat dan ketentuan --}}
                <div class="grid md:col-span-3">
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Syarat dan ketentuan</legend>
                        @can('manage-users')
                            <textarea type="number" class="input w-full h-64 validator" name="syarat_dan_ketentuan" min="1" max="100"
                                required>{{ $data_informasi_website['syarat_dan_ketentuan'] }}</textarea>
                        @else
                            <p class="whitespace-pre-line">{{ $data_informasi_website['syarat_dan_ketentuan'] }}</p>
                        @endcan
                        <p class="validator-hint hidden">
                            Bunga pinjaman wajib diisi.
                            <br> Bunga pinjaman minimal bernilai 0.1 - 100
                        </p>
                        @error('syarat_dan_ketentuan')
                            <small class="text-error">{{ $message }}</small>
                        @enderror
                    </fieldset>
                </div>
                {{-- Submit --}}
                @can('manage-users')
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
