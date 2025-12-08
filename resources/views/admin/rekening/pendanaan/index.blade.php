<x-layouts.admin-app title="Rekening pendanaan">

    <!-- Pendanaan preview -->
    <div class="flex flex-row gap-4">
        <div class="stats stats-vertical xl:stats-horizontal w-full">
            <!-- Saldo rekening -->
            <div class="stat bg-base-200">
                <div class="stat-title">Saldo rekening</div>
                <div class="stat-value text-primary">{{ $data_rekening->formatted_saldo }}</div>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Data inflow & outflow -->
    <div class="flex flex-col gap-4">
        <div class="flex flex-row">
            <h2>Transaksi Rekening</h2>
        </div>
        {{-- Data table --}}
        <div class="flex flex-col gap-6">
            <div class="flex flex-row justify-between">
                <div class="flex flex-row gap-4">
                    <form method="get" class="flex flex-row gap-4 flex-wrap">
                        {{-- Filter date --}}
                        <label class="input w-fit">
                            <span class="label">Tanggal transaksi</span>
                            <input name="created_at" type="date" value="{{ request()->get('created_at') }}" />
                        </label>
                        {{-- Filter select --}}
                        <label class="select w-fit">
                            <span class="label">Tipe transaksi</span>
                            <select name="tipe_transaksi">
                                <option value="" selected>Pilih tipe transaksi</option>
                                @foreach ($list_tipe_transaksi as $tipe)
                                    <option value="{{ $tipe->value }}"
                                        @if ($tipe->value === request()->get('tipe_transaksi')) selected @endif>
                                        {{ $tipe->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </label>
                        {{-- Seach bar --}}
                        <div class="flex flex-row gap-2">
                            <input type="text" name="search" value="{{ request()->get('search') }}" class="input"
                                placeholder="Cari transaksi..." />
                            <button type="submit" class="btn btn-primary join-item">
                                <x-lucide-search class="w-4" />
                            </button>
                        </div>
                    </form>
                </div>
                {{-- Print button --}}
                @can('print-data')
                    <div class="flex flex-row">
                        <a class="btn btn-outline btn-primary"
                            href="{{ route('admin.catatan-pendanaan.export', [
                                'search' => request()->get('search'),
                                'tipe_catatan' => request()->get('tipe_catatan'),
                            ]) }}">
                            <x-lucide-printer class="w-4" />
                            Export Data
                        </a>
                    </div>
                @endcan
            </div>
            <div class="flex flex-col">
                <x-ui.table>
                    <thead>
                        <th>#</th>
                        <th>Nominal</th>
                        <th>Tipe transaksi</th>
                        <th>Keterangan</th>
                        <th>Tanggal transaksi</th>
                    </thead>
                    <tbody>
                        @if ($data_transaksi_rekening->isNotEmpty())
                            @foreach ($data_transaksi_rekening as $item)
                                <tr>
                                    <td>
                                        {{ $loop->iteration + ($data_transaksi_rekening->currentPage() - 1) * $data_transaksi_rekening->perPage() }}
                                    </td>
                                    <td>{{ $item->formatted_nominal }}</td>
                                    <td>{{ $item->formatted_tipe_transaksi }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                    <td>{{ $item->formatted_tanggal_transaksi }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8">
                                    <div class="p-4 text-center">
                                        Tidak ada data transaksi.
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </x-ui.table>
                <div class="p-4">
                    {{ $data_transaksi_rekening->links() }}
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin-app>
