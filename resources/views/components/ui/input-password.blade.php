@props(['prefix_icon'])
@push('scripts')
    <script>
        let is_visible_password = false
        const field_password = document.getElementById("field-password")
        const icon_pass_view = document.getElementById("icon-pass-view")
        const icon_pass_hide = document.getElementById("icon-pass-hide")

        function toggle_visible_password() {
            is_visible_password = !is_visible_password
            field_password.type = is_visible_password ? "text" : "password"
            icon_pass_view.hidden = is_visible_password == true
            icon_pass_hide.hidden = is_visible_password == false
        }
    </script>
@endpush
<label class="input validator w-full">
    @isset($prefix_icon)
        <div class="join-item">
            <x-dynamic-component :component="'lucide-' . $prefix_icon" class="w-4" />
        </div>
    @endisset
    <input id="field-password" {{ $attributes->merge(['class' => '']) }} type="password" class="join-item" />
    <span type="button" class="cursor-pointer" onclick="toggle_visible_password()">
        <span id="icon-pass-view">
            <x-lucide-eye class="w-4" />
        </span>
        <span id="icon-pass-hide" hidden>
            <x-lucide-eye-off class="w-4" />
        </span>
    </span>
</label>
<p class="validator-hint hidden">
    Password minimal terdiri dari 6 karakter
</p>
@error($attributes->has('name'))
    <small class="text-error">{{ $message }}</small>
@enderror
