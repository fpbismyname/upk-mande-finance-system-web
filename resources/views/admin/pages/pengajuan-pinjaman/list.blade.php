<x-layouts.admin-app title="Daftar pengajuan pinjaman" :breadcrumbs="$breadcrumbs">
    {{-- Data table --}}
    <div class="flex flex-col gap-6">
        <div class="flex flex-row justify-between flex-wrap gap-4">
            {{-- Header --}}
            <div class="flex flex-row gap-4">
                {{-- Filter --}}
                <div class="dropdown dropdown-bottom">
                    <div tabindex="0" role="button" class="btn btn-accent">
                        <x-lucide-filter class="w-3" />
                    </div>
                    <div class="dropdown-content menu bg-base-100 w-xs shadow-xl rounded-box">
                        <ul>
                            <li class="menu-title">Filter berdasarkan status</li>
                            @foreach ($list_status_pengajuan as $status)
                                <li><a
                                        href="{{ route('admin.pengajuan-pinjaman.index', ['search' => $status->name]) }}">{{ $status->formatted_name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                {{-- Search bar --}}
                <form method="get">
                    <div class="join">
                        <div class="input join-item">
                            <x-lucide-search class="w-4" />
                            <input type="text" name="search" value="{{ request()->get('search') }}"
                                placeholder="Cari data pengajuan" />
                        </div>
                        <button type="submit" class="btn btn-primary join-item">Cari</button>
                    </div>
                </form>
            </div>
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
                    <th>Pengajuan pada</th>
                    <th>Disetujui pada</th>
                    <th>Ditolak pada</th>
                    <th class="text-end">Aksi</th>
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
                                <td>{{ $item->status_name }}</td>
                                <td>{{ $item->formatted_pengajuan }}</td>
                                <td>{{ $item->formatted_disetujui }}</td>
                                <td>{{ $item->formatted_ditolak }}</td>
                                <td>
                                    <div class="flex flex-row gap-2">
                                        <a class="btn btn-sm btn-link link-hover"
                                            href="{{ route('admin.pengajuan-pinjaman.review', [$item->id]) }}">
                                            <x-lucide-file-search class="w-4" />
                                            {{ __('crud.action.review') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">
                                <div class="p-4 text-center">
                                    {{ __('crud.no_data', ['item' => 'pengajuan pinjaman']) }}
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
