<x-layouts.admin-app title="Detail Kelompok">
    @can('manage-kelompok')
        <x-slot:right_item>
            <a href="{{ route('admin.kelompok.edit', [$kelompok->id]) }}" class="btn btn-primary">
                <x-lucide-edit class="w-4" />
                Edit
            </a>
        </x-slot:right_item>
    @endcan

    {{-- Kelompok --}}
    <div class="card bg-base-200">
        <div class="card-body">
            {{-- View form --}}
            <div class="grid grid-cols-1 gap-2 md:grid-cols-2" method="POST">
                {{-- Nama kelompok --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nama kelompok</legend>
                    <p>{{ $kelompok->name }}</p>
                </fieldset>

                {{-- limit pinjaman --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        Limit pinjaman per anggota
                    </legend>
                    <p>{{ $kelompok->formatted_limit_per_anggota }}</p>
                </fieldset>

                {{-- Ketua kelompok --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        Ketua kelompok
                    </legend>
                    <p>{{ $kelompok->ketua_name }}</p>
                </fieldset>

                {{-- Status kelompok --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        Status kelompok
                    </legend>
                    <p>{{ $kelompok->formatted_status }}</p>
                </fieldset>

                {{-- Created at --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">
                        Dibuat pada
                    </legend>
                    <p>{{ $kelompok->created_at->format('d M Y | H:i') }}</p>
                </fieldset>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    {{-- 
        Anggota kelompok
    --}}

    {{-- Data table --}}
    <div class="flex flex-col gap-6">
        <div class="flex flex-row">
            <h2>Anggota kelompok</h2>
        </div>
        <div class="flex flex-row justify-between">
            {{-- Seach bar --}}
            <div class="flex flex-row">
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
        </div>
        <div class="flex flex-col">
            <x-ui.table>
                <thead>
                    <th>#</th>
                    <th>NIK</th>
                    <th>Nama lengkap</th>
                    <th>Nomor telepon</th>
                    <th>Alamat</th>
                    <th class="text-end">Aksi</th>
                </thead>
                <tbody>
                    @if ($data_anggota->count() > 0)
                        @foreach ($data_anggota as $item)
                            @php
                                $current_account = optional(auth()->user())->is($item);
                            @endphp
                            <tr @if ($current_account) class="bg-primary/50" @endif>
                                <td>{{ $loop->iteration + ($data_anggota->currentPage() - 1) * $data_anggota->perPage() }}
                                </td>
                                <td>{{ $item->nik }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->nomor_telepon }}</td>
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
                                                href="{{ route('admin.kelompok.anggota-kelompok.show', [$kelompok->id, $item->id]) }}">
                                                <x-lucide-eye class="w-4" />
                                                Detail
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">
                                <div class="p-4 text-center">
                                    Tidak ada anggota kelompok yang tersedia.
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </x-ui.table>
            <div class="p-4">
                {{ $data_anggota->links() }}
            </div>
        </div>
    </div>

    {{-- Action button form --}}
    <div class="grid place-items-center md:col-span-2 pt-6">
        <a href="{{ route('admin.kelompok.index') }}" class="btn btn-neutral">
            <span>
                {{ __('Back') }}
            </span>
        </a>
    </div>
</x-layouts.admin-app>
