<x-layouts.admin-app title="Detail Akun">
    <x-slot:right_item>
        @can('manage-users')
            @if (auth()->user()->id !== $user->id)
                <a href="{{ route('admin.users.edit', [$user->id]) }}" class="btn btn-primary">
                    <x-lucide-edit class="w-4" />
                    Edit
                </a>
            @endif
        @endcan
    </x-slot:right_item>
    <div class="flex flex-col gap-4">
        <div class="grid gap-2 md:grid-cols-2">
            <div class="grid md:col-span-2">
                <h6>Data akun</h6>
            </div>

            {{-- username --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama pengguna</legend>
                <p>{{ $user->name }}</p>
            </fieldset>

            {{-- email --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Email</legend>
                <p>{{ $user->email }}</p>
            </fieldset>

            {{-- role --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Role</legend>
                <p>{{ $user->role->label() }}</p>
            </fieldset>

            {{-- created at --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Dibuat pada</legend>
                <p>{{ $user->formatted_created_at }}</p>
            </fieldset>

        </div>


        @if ($pengajuan_keanggotaan)
            <div class="divider"></div>

            <div class="grid md:grid-cols-2 gap-2">

                <div class="grid md:col-span-2">
                    <h6>Data profil</h6>
                </div>

                {{-- nik --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nomor NIK</legend>
                    <p>{{ $pengajuan_keanggotaan->nik }}</p>
                </fieldset>

                {{-- ktp --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Ktp</legend>
                    <a href="{{ route('storage.private.get', ['path' => $pengajuan_keanggotaan->ktp]) }}"
                        target="_blank" class="link link-primary link-hover">Lihat selengkapnya</a>
                </fieldset>

                {{-- nama lengkap --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nama lengkap</legend>
                    <p>{{ $pengajuan_keanggotaan->nama_lengkap }}</p>
                </fieldset>

                {{-- Phone --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nomor Whatsapp</legend>
                    <p>{{ $pengajuan_keanggotaan->nomor_telepon }}</p>
                </fieldset>

                {{-- Nomor Rekening --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nomor Rekening</legend>
                    <p>{{ $pengajuan_keanggotaan->nomor_rekening }}</p>
                </fieldset>

                {{-- Alamat --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Alamat pengguna</legend>
                    <p>{{ $pengajuan_keanggotaan->alamat }}</p>
                </fieldset>

            </div>
        @endif

        <div class="flex flex-row gap-4 justify-center items-center">
            <a href="{{ route('admin.users.index') }}" class="btn btn-neutral">
                <span>
                    {{ __('Back') }}
                </span>
            </a>
        </div>
    </div>
</x-layouts.admin-app>
