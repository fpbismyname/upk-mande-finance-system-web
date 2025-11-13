@props(['message' => 'messages', 'type' => 'info'])
@push('scripts')
    <script>
        function hide_toast() {
            const toast = document.getElementById('toast-message')
            toast.classList.add(['opacity-0']);
            setTimeout(() => {
                toast.hidden = true;
            }, 500)
        }
    </script>
@endpush
<div id="toast-message" class="toast toast-top toast-center z-10 shadow-xl rounded-box transition-all">
    <div class="{{ "alert alert-{$type}" }}">
        <div class="flex flex-row items-center gap-4">
            <x-dynamic-component :component="'lucide-' . ($type === 'error' ? 'x-circle' : ($type === 'success' ? 'circle-check' : 'info'))" class="w-5" />
            <span>{{ $message }}</span>
        </div>
        <x-lucide-x class="cursor-pointer w-4 stroke-3" role="button" onclick="hide_toast()" />
    </div>
</div>
