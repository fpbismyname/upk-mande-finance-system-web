<x-layouts.admin-app title="Daftar pinjaman kelompok" :breadcrumbs="$breadcrumbs">
    {{-- Data table --}}
    <div class="flex flex-col gap-6">
        <div class="flex flex-row justify-between flex-wrap gap-4">
            {{-- Seach bar & filter --}}
            <form method="get" class="flex flex-row gap-4 flex-wrap">
                {{-- filter status --}}
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
                {{-- filter tenor --}}
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
                        placeholder="Cari kelompok..." />
                    <button type="submit" class="btn btn-primary join-item">
                        <x-lucide-search class="w-4" />
                    </button>
                </div>
            </form>
        </div>
        <div class="flex flex-col">
            <x-ui.table>
                <thead>
                    <th>#</th>
                    <th>Nama kelompok</th>
                    <th>Tenor pinjaman (bulan)</th>
                    <th>Nominal pinjaman final</th>
                    <th>Status pinjaman</th>
                    <th>Tanggal mulai</th>
                    <th>Tanggal jatuh tempo</th>
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
                                <td>{{ $item->kelompok_name }}</td>
                                <td class="whitespace-nowrap">{{ $item->formatted_tenor }}</td>
                                <td>{{ $item->formatted_nominal_pinjaman_final }}</td>
                                <td>{{ $item->formatted_status }}</td>
                                <td class="whitespace-nowrap">{{ $item->formatted_tanggal_mulai }}</td>
                                <td class="whitespace-nowrap">{{ $item->formatted_tanggal_jatuh_tempo }}</td>
                                <td>
                                    <div class="flex flex-row gap-2">
                                        <a class="btn btn-sm btn-link link-hover"
                                            href="{{ route('admin.pinjaman-kelompok.view', [$item->id]) }}">
                                            <x-lucide-eye class="w-4" />
                                            {{ __('crud.action.detail', ['']) }}
                                        </a>
                                    </div>
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
