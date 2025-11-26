<x-layouts.admin-app title="Daftar pengajuan pinjaman">
    {{-- Data table --}}
    <div class="flex flex-col gap-6">
        <div class="flex flex-row justify-between flex-wrap gap-4">
            {{-- Header --}}
            <div class="flex flex-row gap-4">
                <form method="get" class="flex flex-row gap-4 flex-wrap">
                    <label class="select w-fit">
                        <span class="label">Status</span>
                        <select name="status">
                            <option value="" selected>Pilih status</option>
                            @foreach ($list_status as $value => $key)
                                <option value="{{ $value }}" @if ($value === request()->get('status')) selected @endif>
                                    {{ $key }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <label class="select w-fit">
                        <span class="label">Tenor</span>
                        <select name="tenor">
                            <option value="" selected>Pilih tenor</option>
                            @foreach ($list_tenor as $value => $key)
                                <option value="{{ $value }}" @if ($value == request()->get('tenor')) selected @endif>
                                    {{ $key }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <div class="flex flex-row gap-2">
                        <input type="text" name="search" value="{{ request()->get('search') }}" class="input"
                            placeholder="Cari pengajuan pinjaman..." />
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
                        href="{{ route('admin.pengajuan-pinjaman.export', [
                            'search' => request()->get('search'),
                            'status' => request()->get('status'),
                            'tenor' => request()->get('tenor'),
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
                    <th>Ketua kelompok</th>
                    <th>Nominal pinjaman</th>
                    <th>Tenor pinjaman (bulan)</th>
                    <th>Status pengajuan</th>
                    @can('manage-pengajuan-pinjaman')
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
                                <td>{{ $item->ketua_name }}</td>
                                <td>{{ $item->formatted_nominal_pinjaman }}</td>
                                <td>{{ $item->formatted_tenor }}</td>
                                <td>{{ $item->formatted_status }}</td>
                                <td>
                                    <div class="flex flex-row gap-2">
                                        @if ($item->status_dalam_proses_pengajuan)
                                            <a class="btn btn-sm btn-link link-hover"
                                                href="{{ route('admin.pengajuan-pinjaman.edit', [$item->id]) }}">
                                                <x-lucide-file-search class="w-4" />
                                                Review
                                            </a>
                                        @else
                                            <a class="btn btn-sm btn-link link-hover"
                                                href="{{ route('admin.pengajuan-pinjaman.show', [$item->id]) }}">
                                                <x-lucide-eye class="w-4" />
                                                Detail
                                            </a>
                                        @endif
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">
                                <div class="p-4 text-center">
                                    Tidak ada data pengajuan pinjaman.
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
