<x-layouts.admin-app title="Kelola kelompok upk">
    {{-- Data table --}}
    <div class="flex flex-col gap-6">
        <div class="flex flex-row justify-between flex-wrap gap-4">
            {{-- Seach bar & filter --}}
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
                <div class="flex flex-row gap-2">
                    <input type="text" name="search" value="{{ request()->get('search') }}" class="input"
                        placeholder="Cari kelompok..." />
                    <button type="submit" class="btn btn-primary join-item">
                        <x-lucide-search class="w-4" />
                    </button>
                </div>
            </form>
            {{-- Add data button --}}
            @can('manage-kelompok')
                <div class="flex flex-row">
                    <a href="{{ route('admin.kelompok.create') }}" class="btn btn-primary">
                        <x-lucide-circle-plus class="w-4" />
                        Tambah kelompok
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
                    <th>Limit pinjaman per anggota</th>
                    <th>Status kelompok</th>
                    @can('manage-kelompok')
                        <th class="text-end">Aksi</th>
                    @endcan
                </thead>
                <tbody>
                    @if (count($datas) > 0)
                        @foreach ($datas as $item)
                            @php
                                $current_account = optional(auth()->user())->is($item);
                            @endphp
                            <tr @if ($current_account) class="bg-primary/50" @endif>
                                <td>{{ $loop->iteration + ($datas->currentPage() - 1) * $datas->perPage() }}
                                </td>
                                <td>{{ $item->formatted_name }}</td>
                                <td>{{ $item->ketua_name }}</td>
                                <td>{{ $item->formatted_limit_per_anggota }}</td>
                                <td>{{ $item->formatted_status }}</td>
                                <td>
                                    @if ($current_account)
                                        <div class="flex w-full justify-end">
                                            <div class="badge badge-primary">
                                                <a href="" class="link-hover">Akun anda</a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex flex-row gap-2">
                                            <a class="btn btn-sm btn-link link-hover"
                                                href="{{ route('admin.kelompok.show', [$item->id]) }}">
                                                <x-lucide-eye class="w-4" />
                                                Detail
                                            </a>
                                            @can('manage-kelompok')
                                                <a class="btn btn-sm btn-link link-hover"
                                                    href="{{ route('admin.kelompok.edit', [$item->id]) }}">
                                                    <x-lucide-pencil class="w-4" />
                                                    Edit
                                                </a>
                                                <form method="post"
                                                    action="{{ route('admin.kelompok.destroy', [$item->id]) }}">
                                                    @method('delete')
                                                    @csrf
                                                    <button class="btn btn-sm btn-link link-hover" type="submit"
                                                        onclick="return confirm('Apakah yakin ingin menghapus data {{ $item->name }}')">
                                                        <x-lucide-trash class="w-4" />
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">
                                <div class="p-4 text-center">
                                    Tidak ada data kelompok yang tersedia.
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </x-ui.table>
            <div class="p-4">
                {!! $datas->links() !!}
            </div>
        </div>
    </div>
</x-layouts.admin-app>
