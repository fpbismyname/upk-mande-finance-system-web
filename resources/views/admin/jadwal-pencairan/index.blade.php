<x-layouts.admin-app title="Daftar jadwal pencairan pinjaman">
    {{-- Data table --}}
    <div class="flex flex-col gap-6">
        <div class="flex flex-row justify-between flex-wrap gap-4">
            {{-- Header --}}
            <div class="flex flex-row gap-4">
                {{-- Seach bar --}}
                <form method="get" class="flex flex-row gap-4 flex-wrap">
                    <label class="select w-fit">
                        <span class="label">Status jadwal</span>
                        <select name="status_jadwal">
                            <option value="" selected>Pilih status jadwal</option>
                            @foreach ($list_status_jadwal as $value => $key)
                                <option value="{{ $value }}" @if ($value === request()->get('status_jadwal')) selected @endif>
                                    {{ $key }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <label class="select w-fit">
                        <span class="label">Tenor pengajuan</span>
                        <select name="tenor_pengajuan">
                            <option value="" selected>Pilih tenor pengajuan</option>
                            @foreach ($list_tenor_pengajuan as $value => $key)
                                <option value="{{ $value }}" @if ($value == request()->get('tenor_pengajuan')) selected @endif>
                                    {{ $key }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <div class="flex flex-row gap-2">
                        <input type="text" name="search" value="{{ request()->get('search') }}" class="input"
                            placeholder="Cari jadwal pengajuan..." />
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
                        href="{{ route('admin.jadwal-pencairan.export', [
                            'search' => request()->get('search'),
                            'status_jadwal' => request()->get('status_jadwal'),
                            'tenor_pengajuan' => request()->get('tenor_pengajuan'),
                            'status_pengajuan' => request()->get('status_pengajuan'),
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
                    <th>Nama kelompok</th>
                    <th>Nominal pinjaman</th>
                    <th>Tenor pinjaman (bulan)</th>
                    <th>Tanggal pencairan</th>
                    <th>Status pencairan</th>
                    @can('manage-jadwal-pencairan')
                        <th class="text-end">Aksi</th>
                    @endcan
                </thead>
                <tbody>
                    @if (count($datas) > 0)
                        @foreach ($datas as $item)
                            <tr>
                                <td>{{ $loop->iteration + ($datas->currentPage() - 1) * $datas->perPage() }}
                                </td>
                                <td>{{ $item->kelompok_name }}</td>
                                <td>{{ $item->formatted_pengajuan_nominal }}</td>
                                <td>{{ $item->formatted_pengajuan_tenor }}</td>
                                <td>{{ $item->formatted_tanggal_pencairan }}</td>
                                <td>{{ $item->formatted_status }}</td>
                                <td>
                                    <div class="flex flex-row gap-2">
                                        @if ($item->status_telah_dicairkan)
                                            <a class="btn btn-sm btn-link link-hover"
                                                href="{{ route('admin.jadwal-pencairan.show', [$item->id]) }}">
                                                <x-lucide-eye class="w-4" />
                                                Detail
                                            </a>
                                        @else
                                            @can('manage-jadwal-pencairan')
                                                <a class="btn btn-sm btn-link link-hover"
                                                    href="{{ route('admin.jadwal-pencairan.edit', [$item->id]) }}">
                                                    <x-lucide-file-clock class="w-4" />
                                                    Jadwalkan
                                                </a>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">
                                <div class="p-4 text-center">
                                    {{ __('crud.no_data', ['item' => 'jadwal pencairan']) }}
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </x-ui.table>
            <div class="p-4">
                {{ $datas->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin-app>
