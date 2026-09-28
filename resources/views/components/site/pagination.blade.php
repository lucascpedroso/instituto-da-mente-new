@if ($paginator->hasPages())
    <nav aria-label="Paginação" class="mt-14 flex items-center justify-center gap-2 text-sm">
        @if ($paginator->onFirstPage())
            <span class="rounded-full border border-areia px-4 py-2 text-cinza/50">Anterior</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="rounded-full border border-areia px-4 py-2 text-marrom hover:border-marrom">Anterior</a>
        @endif

        <span class="px-3 text-cinza">Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}</span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="rounded-full border border-areia px-4 py-2 text-marrom hover:border-marrom">Próxima</a>
        @else
            <span class="rounded-full border border-areia px-4 py-2 text-cinza/50">Próxima</span>
        @endif
    </nav>
@endif
