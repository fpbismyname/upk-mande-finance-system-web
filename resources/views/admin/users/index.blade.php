<x-layouts.admin-app title="Kelola akun pengguna">
    {{-- Data table --}}
    <div class="flex flex-col gap-6">
        <div class="flex flex-row justify-between flex-wrap gap-4">
            {{-- Seach bar & filter --}}
            <form method="get" class="flex flex-row gap-4 flex-wrap">
                <label class="select w-fit">
                    <span class="label">Role</span>
                    <select name="role">
                        <option value="" selected>Pilih role</option>
                        @foreach ($list_role as $value => $key)
                            <option value="{{ $value }}" @if ($value === request()->get('role')) selected @endif>
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
            @can('manage-users')
                <div class="flex flex-row">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <x-lucide-circle-plus class="w-4" />
                        Tambah akun pengguna
                    </a>
                </div>
            @endcan
        </div>
        <div class="flex flex-col">
            <x-ui.table>
                <thead>
                    <th>#</th>
                    <th>Nama lengkap</th>
                    <th>Email</th>
                    <th>Role</th>
                    @can('manage-users')
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
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->formatted_role }}</td>
                                <td>
                                    @if ($current_account)
                                        <div class="flex w-full justify-end">
                                            <div class="badge badge-primary">
                                                <a href="{{ route('admin.settings.index') }}"
                                                    class="link-hover whitespace-nowrap">Akun anda</a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex flex-row gap-2">
                                            <a class="btn btn-sm btn-link link-hover"
                                                href="{{ route('admin.users.show', [$item->id]) }}">
                                                <x-lucide-eye class="w-4" />
                                                {{ __('crud.action.detail', ['']) }}
                                            </a>
                                            @can('manage-users')
                                                <a class="btn btn-sm btn-link link-hover"
                                                    href="{{ route('admin.users.edit', [$item->id]) }}">
                                                    <x-lucide-edit class="w-4" />
                                                    {{ __('crud.action.edit') }}
                                                </a>
                                                <form method="post"
                                                    action="{{ route('admin.users.destroy', [$item->id]) }}">
                                                    @method('delete')
                                                    @csrf
                                                    <button class="btn btn-sm btn-link link-hover" type="submit"
                                                        onclick="return confirm('Apakah anda yakin menghapus data {{ $item->name }}')">
                                                        <x-lucide-trash class="w-4" />
                                                        {{ __('crud.action.delete') }}
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
