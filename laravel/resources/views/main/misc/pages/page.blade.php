<x-main.layouts.app>
    <x-slot:pageTitle>{{ $page->title }}</x-slot>

    @push('metaData')
        {!! $metaData->render() !!}
    @endpush

    <div class="container">
        <div class="row g-2 justify-content-center">
            <div class="col-12 col-sm-10 col-md-8">

                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 py-2">
                        <li class="breadcrumb-item">
                            <a href="{{ route('index') }}">{{ __('Home') }}</a>
                        </li>
                        <li class="breadcrumb-item active tw-line-clamp-1">{{ $page->title }}</li>
                    </ol>
                </nav>
                <!-- [END] Breadcrumb -->

                <h1 class="fs-2 mb-3">{{ $page->title }}</h1>

                <div>
                    {!! $page->content() !!}
                </div>

            </div>
        </div>
    </div>
</x-main.layouts.app>