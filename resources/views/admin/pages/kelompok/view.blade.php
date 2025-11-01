@php
    $is_empty_ketua_kelompok = $list_ketua_kelompok->isEmpty();
    $is_empty_status = $list_status->isEmpty();
    $is_empty_anggota = count($data_anggota) < 1;
@endphp
<x-layouts.admin-app :title="'Detail ' . $kelompok->name" :breadcrumbs="$breadcrumbs">
    <x-slot:right_item>
        <a href="{{ route('admin.kelompok.edit', [$kelompok->id]) }}" class="btn btn-primary">Edit</a>
        <x-partials.delete-item item="kelompok" :route="route('admin.kelompok.destroy', [$kelompok->id])" />
    </x-slot:right_item>
    {{-- 
        Kelompok 
     --}}

    <div class="flex flex-col gap-4">
        {{-- View form --}}
        <div class="grid grid-cols-1 gap-2 md:grid-cols-2" method="POST">
            {{-- Nama kelompok --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama kelompok</legend>
                <input type="text" name="name" readonly
                    placeholder="Nama kelompok (contoh: kelompok mawar, kelompok anggrek, dsb.)" class="input w-full"
                    minlength="4" value="{{ $kelompok->name }}" />
            </fieldset>

            {{-- limit pinjaman --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">
                    Limit pinjaman
                </legend>
                <input type="text" name="limit_pinjaman" placeholder="Limit pinjaman"
                    value="{{ $kelompok->formatted_limit_pinjaman }}" class="input w-full" readonly />
            </fieldset>

            {{-- Ketua kelompok --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">
                    Ketua kelompok
                </legend>
                <input type="text" name="ketua_id" placeholder="Ketua kelompok" value="{{ $kelompok->ketua_name }}"
                    class="input w-full" readonly />
            </fieldset>

            {{-- Status kelompok --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">
                    Status kelompok
                </legend>
                <input type="text" name="ketua_id" placeholder="Ketua kelompok"
                    value="{{ $kelompok->status_name->ucfirst()->replace('_', ' ') }}" class="input w-full" readonly />
            </fieldset>

            {{-- Created at --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">
                    Dibuat pada
                </legend>
                <input type="text" name="created_at" placeholder="Dibuat pada"
                    value="{{ $kelompok->created_at->format('d M Y | H:i') }}" class="input w-full" readonly />
            </fieldset>

            {{-- Updated at --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">
                    Terakhir diperbarui
                </legend>
                <input type="text" name="updated_at" placeholder="Terakhir diperbarui"
                    value="{{ $kelompok->updated_at->format('d M Y | H:i') }}" class="input w-full" readonly />
            </fieldset>
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
                    @if (count($data_anggota) > 0)
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
                                                href="{{ route('admin.kelompok.anggota.view', [$kelompok->id, $item->id]) }}">
                                                <x-lucide-eye class="w-4" />
                                                {{ __('crud.action.detail', ['']) }}
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
                                    {{ __('crud.not_found', ['item' => 'Akun pengguna']) }}
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
