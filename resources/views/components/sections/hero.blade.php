@props(['datas' => []])
<div class="hero bg-base-200 min-h-screen">
    <div class="hero-content text-center">
        <div class="max-w-md">
            <h1 class="text-5xl font-bold">{{ $datas['title'] ?? '' }}</h1>
            <p class="py-6">
                {{ $datas['subtitle'] ?? '' }}
            </p>
            <a href="{{ route('client.home') }}" class="btn btn-primary">
                <x-lucide-waypoints class="w-4" />
                {{ $datas['cta'] ?? "" }}
            </a>
        </div>
    </div>
</div>
