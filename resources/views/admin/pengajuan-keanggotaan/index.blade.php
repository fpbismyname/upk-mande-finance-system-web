<x-layouts.admin-app title="Daftar pengajuan keanggotaan">
    {{-- Data table --}}
    <div class="flex flex-col gap-6">
        <div class="flex flex-row justify-between flex-wrap gap-4">
            {{-- Header --}}
            <div class="flex flex-row gap-4">
                <form method="get" class="flex flex-row gap-4 flex-wrap">
                    <select name="status" class="select w-fit">
                        <option value="" selected>Pilih status</option>
                        @foreach (App\Enums\Admin\Status\EnumStatusPengajuanKeanggotaan::cases() as $status)
                            <option value="{{ $status->value }}" @if ($status->value === request()->get('status')) selected @endif>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                    <div class="flex flex-row gap-2">
                        <input type="text" name="search" value="{{ request()->get('search') }}" class="input"
                            placeholder="Cari pengajuan keanggotaan..." />
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
                    <th>NIK</th>
                    <th>Nama lengkap</th>
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
                                <td>{{ $item->nik }}</td>
                                <td>{{ $item->nama_lengkap }}</td>
                                <td>{{ $item->status->label() }}</td>
                                <td>
                                    <div class="flex flex-row gap-2">
                                        @if ($item->status_proses_pengajuan)
                                            <a class="btn btn-sm btn-link link-hover"
                                                href="{{ route('admin.pengajuan-keanggotaan.edit', [$item->id]) }}">
                                                <x-lucide-file-search class="w-4" />
                                                Review
                                            </a>
                                        @else
                                            <a class="btn btn-sm btn-link link-hover"
                                                href="{{ route('admin.pengajuan-keanggotaan.show', [$item->id]) }}">
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
