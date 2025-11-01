@if ($paginator->hasPages())
    <nav class="flex items-center justify-between">
        {{-- Mobile --}}
        <div class="flex justify-between flex-1 sm:hidden">
            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <button class="btn btn-sm btn-disabled gap-2">
                    @svg('lucide-chevron-left', 'w-4 h-4')
                    {{ __('Prev') }}
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-sm btn-secondary gap-2">
                    @svg('lucide-chevron-left', 'w-4 h-4')
                    {{ __('Prev') }}
                </a>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-sm btn-secondary gap-2">
                    {{ __('Next') }}
                    @svg('lucide-chevron-right', 'w-4 h-4')
                </a>
            @else
                <button class="btn btn-sm btn-disabled gap-2">
                    {{ __('Next') }}
                    @svg('lucide-chevron-right', 'w-4 h-4')
                </button>
            @endif
        </div>

        {{-- Desktop --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div class="text-sm opacity-70">
                {{ __('Showing') }}
                <span class="font-semibold">{{ $paginator->firstItem() }}</span>
                {{ __('to') }}
                <span class="font-semibold">{{ $paginator->lastItem() }}</span>
                {{ __('of') }}
                <span class="font-semibold">{{ $paginator->total() }}</span>
                {{ __('results') }}
            </div>

            <div class="join">
                {{-- Prev --}}
                @if ($paginator->onFirstPage())
                    <button class="join-item btn btn-sm btn-disabled">
                        @svg('lucide-chevron-left', 'w-4 h-4')
                    </button>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="join-item btn btn-sm btn-secondary">
                        @svg('lucide-chevron-left', 'w-4 h-4')
                    </a>
                @endif

                {{-- Pages --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <button class="join-item btn btn-sm btn-disabled">{{ $element }}</button>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <button class="join-item btn btn-sm btn-primary">{{ $page }}</button>
                            @else
                                <a href="{{ $url }}"
                                    class="join-item btn btn-sm btn-secondary">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="join-item btn btn-sm btn-secondary">
                        @svg('lucide-chevron-right', 'w-4 h-4')
                    </a>
                @else
                    <button class="join-item btn btn-sm btn-disabled">
                        @svg('lucide-chevron-right', 'w-4 h-4')
                    </button>
                @endif
            </div>
        </div>
    </nav>
@else
    <div class="text-sm opacity-70">
        {{ __('Showing') }}
        <span class="font-semibold">{{ $paginator->total() }}</span>
        {{ __('results') }}
    </div>
@endif
