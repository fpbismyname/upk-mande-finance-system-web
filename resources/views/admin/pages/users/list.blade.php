<x-layouts.admin-app title="Kelola akun pengguna" :breadcrumbs="$breadcrumbs">
    {{-- Data table --}}
    <div class="flex flex-col gap-6">
        <div class="flex flex-row justify-between flex-wrap gap-4">
            <div class="flex flex-row gap-4">
                <div class="dropdown dropdown-bottom dropdown-start">
                    <div tabindex="0" role="button" class="btn btn-accent">
                        <x-lucide-filter class="w-3" />
                    </div>
                    <div class="dropdown-content menu bg-base-100 w-xs shadow-xl rounded-box">
                        <ul>
                            <li class="menu-title">Filter berdasarkan role</li>
                            @foreach ($list_role as $status)
                                <li><a
                                        href="{{ route('admin.users.index', ['search' => $status->name]) }}">{{ $status->formatted_name }}</a>
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
                            <input type="text" name="search" value="{{ request()->get('search') }}"
                                placeholder="Cari data akun" />
                        </div>
                        <button type="submit" class="btn btn-primary join-item">Cari</button>
                    </div>
                </form>
            </div>
            {{-- Add data button --}}
            <div class="flex flex-row">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <x-lucide-circle-plus class="w-4" />
                    Tambah akun pengguna
                </a>
            </div>
        </div>
        <div class="flex flex-col">
            <x-ui.table>
                <thead>
                    <th>#</th>
                    <th>NIK</th>
                    <th>Nomor telepon</th>
                    <th>Nama lengkap</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Alamat rumah</th>
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
                                <td>{{ $item->nik }}</td>
                                <td>{{ $item->nomor_telepon }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->role_name }}</td>
                                <td>{{ $item->alamat }}</td>
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
                                                href="{{ route('admin.users.view', ['id' => $item->id]) }}">
                                                <x-lucide-eye class="w-4" />
                                                {{ __('crud.action.detail', ['']) }}
                                            </a>
                                            <a class="btn btn-sm btn-link link-hover"
                                                href="{{ route('admin.users.edit', ['id' => $item->id]) }}">
                                                <x-lucide-pencil class="w-4" />
                                                {{ __('crud.action.edit') }}
                                            </a>
                                            <form method="post"
                                                action="{{ route('admin.users.delete', ['id' => $item->id]) }}">
                                                @method('delete')
                                                @csrf
                                                <button class="btn btn-sm btn-link link-hover" type="submit"
                                                    onclick="return confirm('Apakah anda yakin menghapus data {{ $item->name }}')">
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
                                    {{ __('crud.no_data', ['item' => 'Akun pengguna']) }}
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
