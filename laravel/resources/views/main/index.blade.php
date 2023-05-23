<x-main.layouts.app>
    <x-slot:pageTitle>{{ $settings->getFinal('home.title', '[app_name]') }}</x-slot>

    @push('metaData')
        {!! $settings->getFinal('home.meta_data', '') !!}
    @endpush

    <div class="container">

        <!-- Hero -->
        <div class="card shadow-sm text-center">
            <div class="card-body py-5">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-8">
                        <h1>{{ $settings->getFinal('home.main_heading', '[app_name]') }}</h1>
                        @if (!empty($settings->getFinal('home.brief_paragraph', '')))
                            <p>{{ $settings->getFinal('home.brief_paragraph', '') }}</p>
                        @endif

                        <!-- Search bar -->
                        <div>
                            <form action="{{ route('search') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search documents" aria-label="Search documents" aria-describedby="searchButton" name="q">
                                    <button type="submit" class="btn btn-primary px-4" id="searchButton">{{ __('Search') }}</button>
                                </div>
                            </form>
                        </div>
                        <!-- [END] Search bar -->
                    </div>
                </div>
            </div>
        </div>
        <!-- [END] Hero -->

        <!-- Popular documents -->
        <div class="my-5">
            <h2 class="fs-3 mb-2">{{ $settings->get('home.popular_documents_subheading', __('Top Documents')) }}</h2>
            @if (!empty($settings->getFinal('home.popular_documents_paragraph', '')))
                <p>{{ $settings->getFinal('home.popular_documents_paragraph', '') }}</p>
            @endif
            <div class="row g-2 mb-2">
                @foreach ($popularDocuments as $document)
                    <div class="col-12 col-md-6 col-lg-4">
                        <x-main.components.content.document :document="$document" :slug="$settings->get('website.slug')" />
                    </div>
                @endforeach
            </div>
            <div class="text-end">
                <a href="{{ route('popular-documents') }}" class="btn btn-primary">{{ __('Show All') }}</a>
            </div>
        </div>
        <!-- [END] Popular documents -->

        <!-- New documents -->
        <div class="my-5">
            <h2 class="fs-3 mb-2">{{ $settings->getFinal('home.new_documents_subheading', __('New Documents')) }}</h2>
            @if (!empty($settings->getFinal('home.new_documents_paragraph', '')))
                <p>{{ $settings->getFinal('home.new_documents_paragraph', '') }}</p>
            @endif
            <div class="row g-2 mb-2">
                @foreach ($newDocuments as $document)
                    <div class="col-12 col-md-6 col-lg-4">
                        <x-main.components.content.document :document="$document" :slug="$settings->get('website.slug')" />
                    </div>
                @endforeach
            </div>
            <div class="text-end">
                <a href="{{ route('new-documents') }}" class="btn btn-primary">{{ __('Show All') }}</a>
            </div>
        </div>
        <!-- [END] New documents -->

        <!-- Article -->
        @if (!empty($settings->getFinal('home.article', '')))
            <div class="my-5">
                {!! \App\Helpers\Text::article($settings->getFinal('home.article', '')) !!}
            </div>
        @endif
        <!-- [END] Article -->

    </div>
</x-main.layouts.app>