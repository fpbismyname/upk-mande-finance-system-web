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
                <p class="w-full">
                    {{ $user->nik ?? '-' }}
                </p>
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama pengguna</legend>
                <p class="w-full">{{ $user->name ?? '-' }}</p>
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Email</legend>
                <p class="w-full">{{ $user->email ?? '-' }}</p>
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Role</legend>
                <p class="w-full">{{ $user->formatted_role ?? '-' }}</p>
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor Whatsapp</legend>
                <p class="w-full">
                    {{ $user->nomor_telepon ?? '-' }}
                </p>
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor Rekening</legend>
                <p class="w-full">
                    {{ $user->nomor_rekening ?? '-' }}
                </p>
            </fieldset>
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Akun dibuat pada</legend>
                <p class="w-full">
                    {{ $user->created_at->format('d M Y | H:i') ?? null }}
                </p>
            </fieldset>
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
