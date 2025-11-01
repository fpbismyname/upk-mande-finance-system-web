@push('scripts')
    <script>
        let is_reset_password = false;

        function changed_reset_pass() {
            // Get reset password checkbox element
            const reset_password_element = document.getElementById('reset_password')
            // Get fieldset new password
            const fieldset_new_password = document.getElementById('new_password_fieldset')
            // Get field input new password
            const field_new_password = document.getElementById('field-password')
            // bind is_reset_password var to reset password checkbox element value
            is_reset_password = reset_password_element.checked
            // toggle hide if reset password is not true
            fieldset_new_password.hidden = !is_reset_password
            // set to empty every change is reset password value
            field_new_password.value = ""
            // if is reset password checked, set field input new password to required to input
            field_new_password.required = is_reset_password
        }
    </script>
@endpush

<x-layouts.admin-app :title="'Edit Akun ' . $user->name" :breadcrumbs="$breadcrumbs">
    <x-slot:right_item>
        @if (auth()->user()->id !== $user->id)
            <x-partials.delete-item item="user" :route="route('admin.users.delete', ['id' => $user->id])" />
        @endif
    </x-slot:right_item>
    <div class="flex flex-col gap-4">
        {{-- Edit form --}}
        <form action="{{ route('admin.users.update', ['id' => $user->id]) }}"
            class="grid grid-cols-1 gap-2 md:grid-cols-2" method="POST">
            @csrf
            @method('put')

            {{-- nik --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor NIK</legend>
                <input type="text" pattern="[0-9]{16}" minlength="16" maxlength="16" name="nik"
                    value="{{ $user->nik }}" placeholder="Nomor NIK" class="input w-full validator"
                    value="{{ old('nik') }}" required />
                <p class="validator-hint hidden">
                    {{ __('validation.min_digits', ['Attribute' => 'Nomor NIK', 'min' => 16]) }}
                    <br> {{ __('validation.numeric', ['Attribute' => 'Nomor NIK', 'min' => 16]) }}
                </p>
                @error('nik')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>

            {{-- username --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nama pengguna</legend>
                <input type="text" name="name" placeholder="Nama pengguna" class="input w-full validator"
                    value="{{ $user->name ?? '-' }}" required />
                <p class="validator-hint hidden">
                    {{ __('validation.required', ['attribute' => 'Nama pengguna']) }}
                </p>
            </fieldset>

            {{-- email --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Email</legend>
                <input type="email" name="email" placeholder="Email" class="input w-full validator"
                    value="{{ $user->email ?? '-' }}" required />
                <p class="validator-hint hidden">
                    {{ __('validation.email', ['Attribute' => 'Email']) }}
                </p>
            </fieldset>

            {{-- Nomor telepon --}}
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Nomor Whatsapp</legend>
                <input type="text" name="nomor_telepon" placeholder="Nomor Whatsapp" class="input w-full validator"
                    value="{{ $user->nomor_telepon ?? '' }}" pattern="[0-9]{12,16}" minlength="12" maxlength="16"
                    required />
                <p class="validator-hint hidden">
                    {{ __('validation.min_digits', ['Attribute' => 'Nomor whatsapp', 'min' => 12]) }}
                    <br>{{ __('validation.numeric', ['Attribute' => 'Nomor whatsapp']) }}
                </p>
                @error('nomor_telepon')
                    <p class="fieldset-label">{{ $message }}</p>
                @enderror
            </fieldset>

            {{-- role --}}
            <div class="grid md:col-span-2">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Role</legend>
                    <select name="role_id" class="select w-full">
                        @foreach ($list_role as $role)
                            <option value="{{ $role->id }}" @if ($role->id === $user->role_id) selected @endif>
                                {{ Str::of($role->name)->replace('_', ' ')->ucfirst() }}</option>
                        @endforeach
                    </select>
                </fieldset>
            </div>

            {{-- Alamat --}}
            <div class="grid md:col-span-2">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend">Alamat pengguna</legend>
                    <textarea name="alamat" placeholder="Alamat pengguna" class="textarea w-full validator" required>{{ $user->alamat }}</textarea>
                    <p class="validator-hint hidden">
                        {{ __('validation.required', ['Attribute' => 'Alamat pengguna', 'min' => 10]) }}
                    </p>
                    @error('alamat')
                        <p class="fieldset-label">{{ $message }}</p>
                    @enderror
                </fieldset>
            </div>

            {{-- reset password --}}
            <div class="grid md:col-span-2">
                <fieldset class="fieldset">
                    <label class="label">
                        <input type="checkbox" onchange="changed_reset_pass()" class="checkbox" id="reset_password"
                            name="reset_password" />
                        Reset password
                    </label>
                    <p class="validator-hint hidden">
                        {{ __('validation.required', ['attribute' => 'Password baru']) }}
                    </p>
                </fieldset>
            </div>

            {{-- new password --}}
            <div class="grid md:col-span-2">
                <fieldset id="new_password_fieldset" class="fieldset" hidden>
                    <legend class="fieldset-legend">Password baru</legend>
                    <x-ui.input-password name="new_password" placeholder="Password baru" class="validator"
                        pattern="[\w\s]{6,250}" />
                </fieldset>
            </div>

            <div class="grid place-items-center md:col-span-2 pt-6">
                <div class="flex flex-row gap-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('crud.action.save') }}
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-neutral">
                        <span>
                            {{ __('Back') }}
                        </span>
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-layouts.admin-app>
