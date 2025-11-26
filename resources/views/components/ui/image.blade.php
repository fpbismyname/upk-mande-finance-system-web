@props(['src' => ''])
<img {{ $attributes->merge(['class' => 'skeleton']) }} src="{{ $src }}" alt="icon upk mande" loading="lazy"
    decoding="async" fetchpriority="high" onload="this.style.opacity=1;this.classList.remove('skeleton')">
