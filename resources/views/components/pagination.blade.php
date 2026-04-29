@if ($paginator->hasPages())
    <div class="pagination">
        <div class="pagination-summary">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </div>

        <div class="pagination-links">
            @if ($paginator->onFirstPage())
                <span class="button-link secondary">Previous</span>
            @else
                <a class="button-link secondary" href="{{ $paginator->previousPageUrl() }}">Previous</a>
            @endif

            <span class="pagination-summary">Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</span>

            @if ($paginator->hasMorePages())
                <a class="button-link secondary" href="{{ $paginator->nextPageUrl() }}">Next</a>
            @else
                <span class="button-link secondary">Next</span>
            @endif
        </div>
    </div>
@endif
