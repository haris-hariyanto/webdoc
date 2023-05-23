<x-main.layouts.app>
    <x-slot:pageTitle>{{ $settings->getFinal('new-documents.title', __('New Documents')) }}</x-slot>

    @push('metaData')
        {!! $settings->getFinal('new-documents.meta_data', '') !!}
    @endpush

    <div class="container mb-2">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 py-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('index') }}">{{ __('Home') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $settings->getFinal('new-documents.main_heading', __('New Documents')) }}</li>
            </ol>
        </nav>
        <!-- [END] Breadcrumb -->

        <h1 class="fs-2 mb-2">{{ $settings->getFinal('new-documents.main_heading', __('New Documents')) }}</h1>
        @if (!empty($settings->getFinal('new-documents.brief_paragraph', '')))
            <p>{{ $settings->getFinal('new-documents.brief_paragraph', '') }}</p>
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

        <!-- Article -->
        @if (!empty($settings->getFinal('new-documents.article', '')))
            <div class="my-5">
                {!! \App\Helpers\Text::article($settings->getFinal('new-documents.article', '')) !!}
            </div>
        @endif
        <!-- [END] Article -->
    </div>
</x-main.layouts.app>