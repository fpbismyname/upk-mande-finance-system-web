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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor NIK</legend>
                <input type="text" name="name" placeholder="Nomor NIK" class="input w-full"
                    value="{{ $user->nik ?? '-' }}" readonly />
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama pengguna</legend>
                <input type="text" name="name" placeholder="Nama pengguna" class="input w-full"
                    value="{{ $user->name ?? '-' }}" readonly />
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Email</legend>
                <input type="email" name="email" placeholder="Email" class="input w-full"
                    value="{{ $user->email ?? '-' }}" readonly />
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Role</legend>
                <input type="email" name="role" placeholder="Role" class="input w-full"
                    value="{{ $user->formatted_role ?? '-' }}" readonly />
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor Whatsapp</legend>
                <input type="text" name="nomor_telepon" placeholder="Nomor Whatsapp" class="input w-full"
                    value="{{ $user->nomor_telepon ?? '-' }}" readonly />
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Alamat</legend>
                <textarea name="alamat" placeholder="Alamat" class="textarea w-full" readonly>{{ $user->alamat ?? '-' }}</textarea>
            </fieldset>
            <div class="grid col-span-2 grid-cols-2 gap-4">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Akun dibuat pada</legend>
                    <input type="text" name="created_at" placeholder="Tanggal dibuat"
                        value="{{ $user->created_at->format('d M Y | H:i') ?? null }}" class="input w-full" readonly />
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Terakhir diubah</legend>
                    <input type="text" name="updated_at" placeholder="Terakhir diubah"
                        value="{{ $user->updated_at->format('d M Y | H:i') ?? null }}" class="input w-full" readonly />
                </fieldset>
            </div>
        </div>
        <div class="flex flex-row gap-4 justify-center items-center">
            <a href="{{ route('admin.users.index') }}" class="btn btn-neutral">
                <span>
                    {{ __('Back') }}
                </span>
            </a>
        </div>
    </div>
</x-layouts.admin-app>
