<x-layouts.admin-app title="Tambah akun admin">
    <div class="flex flex-col gap-4">
        {{-- Add form --}}
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('post')

            <div class="grid gap-2 md:grid-cols-2">
                <div class="grid md:col-span-2">
                    <h6>Data akun</h6>
                </div>

                {{-- username --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Nama pengguna</legend>
                    <input type="text" name="name" placeholder="Nama pengguna" class="input w-full validator"
                        value="{{ old('name') }}" required />
                    <p class="validator-hint hidden">
                        Nama pengguna wajib diisi
                    </p>
                    @error('name')
                        <p class="fieldset-label">{{ $message }}</p>
                    @enderror
                </fieldset>

                {{-- email --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Email</legend>
                    <input type="email" name="email" placeholder="Email" class="input w-full validator"
                        value="{{ old('email') }}" required />
                    <p class="validator-hint hidden">
                        Alamat email tidak valid.
                    </p>
                    @error('email')
                        <p class="fieldset-label">{{ $message }}</p>
                    @enderror
                </fieldset>

                {{-- new password --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Password baru</legend>
                    <x-ui.input-password name="password" placeholder="Password baru" class="validator" required
                        value="{{ old('password') }}" pattern="[\w\s]{6,250}" />
                    @error('password')
                        <p class="fieldset-label">{{ $message }}</p>
                    @enderror
                </fieldset>

                {{-- role --}}
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Role</legend>
                    <select name="role" class="select w-full validator" required>
                        <option value="" selected disabled>Pilih role</option>
                        @foreach (App\Enums\Admin\User\EnumRole::list_admin_role() as $role)
                            <option value="{{ $role->value }}">
                                {{ $role->label() }}
                            </option>
                        @endforeach
                    </select>
                    <p class="validator-hint hidden">
                        Role wajib dipilih
                    </p>
                    @error('role_id')
                        <p class="fieldset-label">{{ $message }}</p>
                    @enderror
                </fieldset>

            </div>

            {{-- Action button form --}}
            <div class="grid place-items-center md:col-span-2 pt-6">
                <div class="flex flex-row gap-4">
                    <button type="submit" class="btn btn-primary">
                        Buat
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-neutral">
                        <span>
                            Kembali
                        </span>
                    </a>
                </div>
            </div>

        </form>
    </div>
</x-layouts.admin-app>
