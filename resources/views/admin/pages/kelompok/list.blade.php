<x-layouts.admin-app title="Kelola kelompok upk" :breadcrumbs="$breadcrumbs">
    {{-- Data table --}}
    <div class="flex flex-col gap-6">
        <div class="flex flex-row justify-between flex-wrap gap-4">
            <div class="flex flex-row gap-4">
                {{-- Filter --}}
                <div class="dropdown dropdown-bottom dropdown-start">
                    <div tabindex="0" role="button" class="btn btn-accent">
                        <x-lucide-filter class="w-3" />
                    </div>
                    <div class="dropdown-content menu bg-base-100 w-xs shadow-xl rounded-box">
                        <ul>
                            <li class="menu-title">Filter berdasarkan status</li>
                            @foreach ($list_status_kelompok as $status)
                                <li><a
                                        href="{{ route('admin.kelompok.index', ['search' => $status->name]) }}">{{ $status->formatted_name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                {{-- Seach bar --}}
                <form method="get">
                    <div class="join">
                        <div class="input join-item">
                            <x-lucide-search class="w-4" />
                            <input type="text" name="search" placeholder="Cari data kelompok"
                                value="{{ request()->get('search') }}" />
                        </div>
                        <button type="submit" class="btn btn-primary join-item">Cari</button>
                    </div>
                </form>
            </div>
            {{-- Add data button --}}
            <div class="flex flex-row">
                <a href="{{ route('admin.kelompok.create') }}" class="btn btn-primary">
                    <x-lucide-circle-plus class="w-4" />
                    Tambah kelompok
                </a>
            </div>
        </div>
        <div class="flex flex-col">
            <x-ui.table>
                <thead>
                    <th>#</th>
                    <th>Ketua kelompok</th>
                    <th>Nama kelompok</th>
                    <th>Limit pinjaman</th>
                    <th>Status kelompok</th>
                    <th class="text-end">Aksi</th>
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
                                <td>{{ $item->ketua_name }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->formatted_limit_pinjaman }}</td>
                                <td>{{ $item->status_name->replace('_', ' ')->ucfirst() }}</td>
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
                                                href="{{ route('admin.kelompok.view', [$item->id]) }}">
                                                <x-lucide-eye class="w-4" />
                                                {{ __('crud.action.detail', ['']) }}
                                            </a>
                                            <a class="btn btn-sm btn-link link-hover"
                                                href="{{ route('admin.kelompok.edit', [$item->id]) }}">
                                                <x-lucide-pencil class="w-4" />
                                                {{ __('crud.action.edit') }}
                                            </a>
                                            <form method="post"
                                                action="{{ route('admin.kelompok.destroy', [$item->id]) }}">
                                                @method('delete')
                                                @csrf
                                                <button class="btn btn-sm btn-link link-hover" type="submit"
                                                    onclick="return confirm('Apakah yakin ingin menghapus data {{ $item->name }}')">
                                                    <x-lucide-trash class="w-4" />
                                                    {{ __('crud.action.delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">
                                <div class="p-4 text-center">
                                    {{ __('crud.no_data', ['item' => 'kelompok']) }}
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
