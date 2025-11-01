<div class="overflow-x-auto w-full border rounded-box border-base-300">
    <table {{ $attributes->merge(['class' => 'table table-zebra']) }}>
        {{ $slot }}
    </table>
</div>
