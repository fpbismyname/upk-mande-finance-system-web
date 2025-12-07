<x-layouts.admin-app title="Detail pinjaman">
    <x-slot:right_item>
        <a href="{{ route('admin.pinjaman-kelompok.export-one', [$pinjaman_kelompok->id]) }}"
            class="btn btn-primary btn-outline">
            <x-lucide-printer class="w-4" />
            Export laporan pinjaman
        </a>
    </x-slot:right_item>
    {{-- Kelompok --}}
    <div class="flex flex-col gap-4">
        {{-- View form --}}
        <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
            {{-- Nama kelompok --}}
            <fieldset class="fieldset flex flex-col">
                <legend class="fieldset-legend">Nama kelompok</legend>
                <p>{{ $pinjaman_kelompok->kelompok_name }}</p>
            </fieldset>

            {{-- Ketua kelompok --}}
            <fieldset class="fieldset flex flex-col">
                <legend class="fieldset-legend">Ketua kelompok</legend>
                <p>{{ $pinjaman_kelompok->ketua_name }}</p>
            </fieldset>

            {{-- Nominal Pinjaman --}}
            <fieldset class="fieldset flex flex-col">
                <legend class="fieldset-legend">Nominal pinjaman</legend>
                <p>{{ $pinjaman_kelompok->formatted_nominal_pinjaman }}</p>
            </fieldset>


            {{-- Bunga Pinjaman --}}
            <fieldset class="fieldset flex flex-col">
                <legend class="fieldset-legend">Bunga pinjaman</legend>
                <p>{{ $pinjaman_kelompok->formatted_bunga }}</p>
            </fieldset>

            {{-- Total nominal pinjaman --}}
            <fieldset class="fieldset flex flex-col">
                <legend class="fieldset-legend">Total nominal pinjaman</legend>
                <p>{{ $pinjaman_kelompok->formatted_total_nominal_pinjaman }}</p>
            </fieldset>

            {{-- Tenor Pinjaman --}}
            <fieldset class="fieldset flex flex-col">
                <legend class="fieldset-legend">Tenor pinjaman</legend>
                <p>{{ $pinjaman_kelompok->formatted_tenor }}</p>
            </fieldset>

            {{-- Status pinjaman --}}
            <fieldset class="fieldset flex flex-col">
                <legend class="fieldset-legend">Status pinjaman</legend>
                <p>{{ $pinjaman_kelompok->formatted_status }}</p>
            </fieldset>

            {{-- Tanggal mulai --}}
            <fieldset class="fieldset flex flex-col">
                <legend class="fieldset-legend">Tanggal mulai</legend>
                <p>{{ $pinjaman_kelompok->formatted_tanggal_mulai }}</p>
            </fieldset>
            {{-- Tanggal jatuh tempo --}}
            <fieldset class="fieldset flex flex-col">
                <legend class="fieldset-legend">Tanggal jatuh tempo</legend>
                <p>{{ $pinjaman_kelompok->formatted_tanggal_jatuh_tempo }}</p>
            </fieldset>
        </div>
    </div>

    <div class="divider"></div>

    {{-- Daftar cicilan --}}
    <div class="flex flex-col gap-6">
        <h2>Cicilan kelompok</h2>
        <div class="flex flex-row justify-between flex-wrap gap-4">
            {{-- Seach bar & filter --}}
            <form method="get" class="flex flex-row gap-4 flex-wrap">
                <label class="select w-fit">
                    <span class="label">Status cicilan</span>
                    <select name="status">
                        <option value="" selected>Pilih status cicilan</option>
                        @foreach ($list_status_cicilan as $value => $key)
                            <option value="{{ $value }}" @if ($value === request()->get('status')) selected @endif>
                                {{ $key }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <button type="submit" class="btn btn-primary join-item">
                    <x-lucide-search class="w-4" />
                </button>
            </form>
        </div>
        <div class="flex flex-col">
            <x-ui.table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nominal cicilan</th>
                        <th>Status kelompok</th>
                        <th>Tanggal dibayar</th>
                        <th>Tanggal jatuh tempo</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($data_cicilan->count() > 0)
                        @foreach ($data_cicilan as $item)
                            <tr class="@if ($item->cicilan_telat_sudah_bayar) bg-warning/50 @endif">
                                <td>{{ $loop->iteration + ($data_cicilan->currentPage() - 1) * $data_cicilan->perPage() }}
                                </td>
                                <td>{{ $item->formatted_nominal_cicilan }}</td>
                                <td>{{ $item->formatted_status }}
                                    @if ($item->cicilan_telat_sudah_bayar)
                                        | Telat {{ $item->formatted_hari_telat_bayar }}
                                    @endif
                                </td>
                                <td>{{ $item->formatted_tanggal_dibayar }}</td>
                                <td>{{ $item->formatted_tanggal_jatuh_tempo }}</td>
                                <td>
                                    @if ($item->status_sudah_bayar)
                                        <a target="_blank"
                                            href="{{ route('storage.private.get', ['path' => $item->bukti_pembayaran]) }}"
                                            class="btn btn-link btn-sm link-hover">
                                            <x-lucide-receipt class="w-4" />
                                            Lihat bukti pembayaran
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">
                                <div class="p-4 text-center">
                                    {{ __('crud.no_data', ['item' => 'cicilan']) }}
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </x-ui.table>
            <div class="p-4">
                {{ $data_cicilan->links() }}
            </div>
        </div>
    </div>

    {{-- Action button form --}}
    <div class="grid place-items-center md:col-span-2 pt-6">
        <a href="{{ route('admin.pinjaman-kelompok.index') }}" class="btn btn-neutral">
            <span>
                {{ __('Back') }}
            </span>
        </a>
    </div>
</x-layouts.admin-app>
