<x-layouts.admin-app title="Rekening akuntan">
    <!-- Akuntan preview -->
    <div class="flex flex-col gap-4">
        {{-- Header rekening saldo --}}
        <div class="stats stats-vertical xl:stats-horizontal w-full">
            <!-- Saldo rekening -->
            <div class="stat bg-base-200">
                <div class="stat-title">Saldo rekening</div>
                <div class="stat-value text-primary">{{ $data_rekening->formatted_saldo }}</div>
            </div>
            <!-- Pemasukan -->
            <div class="stat bg-base-200">
                <div class="stat-title">Pemasukan</div>
                <div class="stat-value text-success">{{ $data_rekening->formatted_inflow_data }}</div>
            </div>
            <!-- Pengeluaran -->
            <div class="stat bg-base-200">
                <div class="stat-title">Pengeluaran</div>
                <div class="stat-value text-error">{{ $data_rekening->formatted_outflow_data }}</div>
            </div>
        </div>
        {{-- Action rekening --}}
        @can('manage-rekening')
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.rekening-akuntan.deposit') }}" class="btn btn-primary">
                    <x-lucide-circle-arrow-down class="w-4 hidden md:inline" />
                    Deposit
                </a>
                <a href="{{ route('admin.rekening-akuntan.transfer') }}" class="btn btn-accent">
                    <x-lucide-circle-arrow-up class="w-4 hidden md:inline" />
                    Transfer ke pendanaan
                </a>
            </div>
        @endcan
    </div>

    <div class="divider"></div>

    <!-- Data inflow & outflow -->
    <div class="flex flex-col gap-4">
        <div class="flex flex-row">
            <h2>Transaksi Rekening</h2>
        </div>
        {{-- Data table --}}
        <div class="flex flex-col gap-6">
            {{-- Header table --}}
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
                                @foreach ($list_tipe_transaksi as $value => $key)
                                    <option value="{{ $value }}"
                                        @if ($value === request()->get('tipe_transaksi')) selected @endif>
                                        {{ $key }}
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
                            href="{{ route('admin.transaksi-rekening.export', [
                                'search' => request()->get('search'),
                                'tipe_catatan' => request()->get('tipe_catatan'),
                                'created_at' => request()->get('created_at'),
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
                        <th>Nama rekening</th>
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
                                    <td>{{ $item->rekening->formatted_name }}</td>
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
