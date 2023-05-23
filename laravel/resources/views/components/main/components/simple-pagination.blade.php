@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Page navigation">
        <div class="row justify-content-between align-items-center gy-0 gx-2 my-2">
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 d-grid">

                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <button type="button" class="btn btn-outline-secondary" disabled>{!! __('pagination.previous') !!}</button>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-outline-secondary" rel="prev">{!! __('pagination.previous') !!}</a>
                @endif

            </div>

            <div class="d-none d-md-block col-md-6 col-lg-8 text-center">
                <span>{{ __('pagination.page_position_simple', ['page' => $paginator->currentPage()]) }}</span>
            </div>

            <div class="col-6 col-sm-4 col-md-3 col-lg-2 d-grid">

                {{-- Next Page Link ---}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-outline-secondary" rel="next">{!! __('pagination.next') !!}</a>
                @else
                    <button type="button" class="btn btn-outline-secondary" disabled>{!! __('pagination.next') !!}</button>
                @endif

            </div>
        </div>
    </nav>
@endif