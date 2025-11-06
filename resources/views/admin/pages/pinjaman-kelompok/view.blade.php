<x-layouts.admin-app :title="'Detail pinjaman'" :breadcrumbs="$breadcrumbs">

    {{-- Kelompok --}}
    <div class="flex flex-col gap-4">
        {{-- View form --}}
        <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
            {{-- Nama kelompok --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama kelompok</legend>
                <input type="text" readonly class="input w-full" value="{{ $pinjaman_kelompok->kelompok_name }}" />
            </fieldset>

            {{-- Ketua kelompok --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Ketua kelompok</legend>
                <input type="text" value="{{ $pinjaman_kelompok->ketua_name }}" class="input w-full" readonly />
            </fieldset>

            {{-- Nominal Pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nominal pinjaman</legend>
                <input type="text" value="{{ $pinjaman_kelompok->formatted_nominal_pinjaman }}" class="input w-full"
                    readonly />
            </fieldset>

            {{-- Tenor Pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Tenor pinjaman</legend>
                <input type="text" value="{{ $pinjaman_kelompok->formatted_tenor }}" class="input w-full" readonly />
            </fieldset>

            {{-- Status pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Status pinjaman</legend>
                <input type="text" value="{{ $pinjaman_kelompok->formatted_status }}" class="input w-full"
                    readonly />
            </fieldset>

            {{-- Tangal mulai --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Tanggal mulai</legend>
                <input type="text" value="{{ $pinjaman_kelompok->formatted_tanggal_mulai }}" class="input w-full"
                    readonly />
            </fieldset>
            {{-- Tanggal jatuh tempo --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Tanggal jatuh tempo</legend>
                <input type="text" value="{{ $pinjaman_kelompok->formatted_tanggal_jatuh_tempo }}"
                    class="input w-full" readonly />
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
                    <th>#</th>
                    <th>Nominal cicilan</th>
                    <th>Status kelompok</th>
                    <th>Tanggal dibayar</th>
                    <th>Tanggal jatuh tempo</th>
                </thead>
                <tbody>
                    @if (count($data_cicilan) > 0)
                        @foreach ($data_cicilan as $item)
                            <tr>
                                <td>{{ $loop->iteration + ($data_cicilan->currentPage() - 1) * $data_cicilan->perPage() }}
                                </td>
                                <td>{{ $item->formatted_nominal_cicilan }}</td>
                                <td>{{ $item->formatted_status }}</td>
                                <td>{{ $item->formatted_tanggal_dibayar }}</td>
                                <td>{{ $item->formatted_tanggal_jatuh_tempo }}</td>
                                @if ($item->status === App\Enum\Admin\Status\EnumStatusCicilanKelompok::SUDAH_BAYAR)
                                    <td>
                                        <a href="" class="btn btn-link btn-sm link-hover">
                                            <x-lucide-receipt class="w-4" />
                                            Lihat bukti pembayaran
                                        </a>
                                    </td>
                                @endif
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
