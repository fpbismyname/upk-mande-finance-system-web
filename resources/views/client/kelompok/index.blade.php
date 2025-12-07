<x-layouts.client-app title="Kelompok saya">
    <div class="flex flex-col gap-4">
        @if (!empty($kelompok))
            <div class="flex flex-row justify-between items-center flex-wrap gap-4">
                <div class="flex flex-col gap-2">
                    <div class="flex flex-row gap-2">
                        <h3>{{ $kelompok->name }}</h3>
                        <button class="btn btn-circle btn-sm btn-primary" onclick="window.open_modal('edit-kelompok')">
                            <x-lucide-edit class="w-4" />
                        </button>
                    </div>
                    @if ($kelompok->decimal_jumlah_anggota_kelompok < $kelompok_settings['minimal_anggota_kelompok'])
                        <p class="text-warning">Jumlah anggota : {{ $kelompok->jumlah_anggota_kelompok }}</p>
                        <small class="text-primary">
                            Lengkapi anggota kelompok dengan minimal
                            {{ $kelompok_settings['minimal_anggota_kelompok'] }}
                            anggota untuk
                            dapat mengajukan
                            pinjaman.
                        </small>
                    @else
                        <small>Jumlah anggota : {{ $kelompok->jumlah_anggota_kelompok }}</small>
                    @endif
                    <small>Limit pinjaman per anggota : {{ $kelompok->formatted_limit_per_anggota }}</small>
                </div>
                @if ($kelompok->dapat_menambahkan_anggota_kelompok)
                    <div class="flex flex-row gap-2">
                        <a href="{{ route('client.kelompok.anggota-kelompok.create', [$kelompok->id]) }}"
                            class="btn btn-primary">
                            <x-lucide-circle-plus class="w-4" />
                            Tambah anggota
                        </a>
                    </div>
                @endif
            </div>
            <x-ui.table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NIK</th>
                        <th>Nama anggota</th>
                        <th>Nomor telepon</th>
                        <th>Alamat</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($list_anggota_kelompok?->count())
                        @foreach ($list_anggota_kelompok as $anggota)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $anggota->nik ?? '-' }}</td>
                                <td>{{ $anggota->name ?? '-' }}</td>
                                <td>{{ $anggota->nomor_telepon ?? '-' }}</td>
                                <td>{{ $anggota->alamat ?? '-' }}</td>
                                <td>
                                    <div class="flex flex-row gap-2">
                                        <a href="{{ route('client.kelompok.anggota-kelompok.show', [$kelompok->id, $anggota->id]) }}"
                                            class="btn btn-sm btn-link link-hover">
                                            <x-lucide-eye class="w-4" />
                                            Detail
                                        </a>
                                        <a href="{{ route('client.kelompok.anggota-kelompok.edit', [$kelompok->id, $anggota->id]) }}"
                                            class="btn btn-sm btn-link link-hover">
                                            <x-lucide-edit class="w-4" />
                                            Edit
                                        </a>
                                        <form
                                            action="{{ route('client.kelompok.anggota-kelompok.destroy', [$kelompok->id, $anggota->id]) }}"
                                            method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-sm btn-link link-hover"
                                                onclick="return confirm('Apakah anda yakin ingin menghapus {{ $anggota->name }} ?')">
                                                <x-lucide-trash class="w-4" />
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class=" text-center py-8">Tidak ada data anggota kelompok.</td>
                        </tr>
                    @endif
                </tbody>
            </x-ui.table>
            <div>
                {{ $list_anggota_kelompok->links() }}
            </div>
        @else
            @if (auth()->user()->pengajuan_keanggotaan_disetujui()->exists())
                <div class="flex flex-col items-center">
                    <x-ui.card>
                        <div class="card-body text-center">
                            <x-lucide-users class="w-6 self-center" />
                            <h3>Anda belum memiliki kelompok pinjaman</h3>
                            <div class="card-actions justify-center">
                                <button class="btn btn-primary" onclick="window.open_modal('create-kelompok')">
                                    <x-lucide-circle-plus class="w-4" />
                                    Buat kelompok
                                </button>
                            </div>
                        </div>
                    </x-ui.card>
                </div>
            @else
                <div class="flex flex-col items-center">
                    <x-ui.card>
                        <div class="card-body text-center">
                            <x-lucide-users class="w-6 self-center" />
                            <h3>Anda belum menjadi anggota {{ config('site.website.title') }}</h3>
                            <div class="card-actions justify-center">
                                <a class="btn btn-primary" href="{{ route('client.pengajuan-keanggotaan.index') }}">
                                    Ajukan keanggotaan
                                </a>
                            </div>
                        </div>
                    </x-ui.card>
                </div>
            @endif
        @endif
    </div>

    {{-- Modal edit nama --}}
    <dialog id="edit-kelompok" class="modal">
        <div class="modal-box">
            <h3>Edit nama kelompok</h3>
            @if (!empty($kelompok))
                <form action="{{ route('client.kelompok.update', [$kelompok->id]) }}" method="post"
                    class="grid space-y-4">
                    @csrf
                    @method('put')
                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Nama kelompok</legend>
                        <input class="input w-full validator" type="text" name="name"
                            placeholder="Kelompok (nama yang mewakili kelompok)" value="{{ $kelompok->name }}"
                            required />
                        <p class="validator-hint hidden">
                            Nama kelompok wajib diisi.
                        </p>
                        @error('name')
                            <small class="text-error">{{ $message }}</small>
                        @enderror
                    </fieldset>
                    <div class="grid md:grid-cols-2 gap-4">
                        <button type="submit" class="btn btn-primary">Buat</button>
                        <button type="reset" onclick="window.close_modal('edit-kelompok')"
                            class="btn btn-neutral">Kembali</button>
                    </div>
                </form>
            @endif
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    {{-- Modal create kelompok --}}
    <dialog id="create-kelompok" class="modal">
        <div class="modal-box">
            <h3>Buat kelompok baru</h3>
            <form action="{{ route('client.kelompok.store', [auth()->user()->id]) }}" method="post"
                class="grid space-y-4">
                @csrf
                @method('post')
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nama kelompok</legend>
                    <input type="text" name="name" class="input w-full validator"
                        placeholder="Kelompok (nama yang mewakili kelompok)" required />
                    <p class="validator-hint hidden">
                        Nama kelompok wajib diisi
                    </p>
                    @error('name')
                        <small class="text-error">{{ $message }}</small>
                    @enderror
                </fieldset>
                <div class="grid md:grid-cols-2 gap-4">
                    <button type="submit" class="btn btn-primary">Buat</button>
                    <button type="reset" onclick="window.close_modal('create-kelompok')"
                        class="btn btn-neutral">Kembali</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</x-layouts.client-app>
