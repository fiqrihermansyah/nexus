@if ($paginator->hasPages())
<nav class="flex items-center gap-1">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="px-3 py-1.5 text-xs text-gray-300 rounded-lg cursor-not-allowed">← Prev</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1.5 text-xs text-gray-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors">← Prev</a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="px-2 text-xs text-gray-400">...</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="w-8 h-7 flex items-center justify-center text-xs font-bold text-white bg-emerald-700 rounded-lg">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="w-8 h-7 flex items-center justify-center text-xs text-gray-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1.5 text-xs text-gray-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors">Next →</a>
    @else
        <span class="px-3 py-1.5 text-xs text-gray-300 rounded-lg cursor-not-allowed">Next →</span>
    @endif
</nav>
@endif
