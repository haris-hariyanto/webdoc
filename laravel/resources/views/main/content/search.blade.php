<x-main.layouts.app>
    <x-slot:pageTitle>{{ $settings->getFinal('search.title', __('Search Result') . ' : ' . '[keyword]') }}</x-slot:pageTitle>

    @push('metaData')
        {!! $settings->getFinal('search.meta_data', '') !!}
    @endpush

    <div class="container mb-2">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 py-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('index') }}">{{ __('Home') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $settings->getFinal('search.main_heading', __('Search Result') . ' : ' . '[keyword]') }}</li>
            </ol>
        </nav>
        <!-- [END] Breadcrumb -->

        <h1 class="fs-2 mb-2">{{ $settings->getFinal('search.main_heading', __('Search Result') . ' : ' . '[keyword]') }}</h1>
        @if (!empty($settings->getFinal('search.brief_paragraph', '')))
            <p>{{ $settings->getFinal('search.brief_paragraph', '') }}</p>
        @endif
    </div>

    <div class="container">
        <div class="row g-2">
            @foreach ($documents as $document)
                <div class="col-12 col-md-6 col-lg-4">
                    <x-main.components.content.document :document="$document" :slug="$settings->get('website.slug')" />
                </div>
            @endforeach
        </div>
        <div>
            {{ $documents->links('components.main.components.simple-pagination') }}
        </div>
    </div>
</x-main.layouts.app>