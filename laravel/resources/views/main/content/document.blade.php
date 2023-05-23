<x-main.layouts.app>
    <x-slot:pageTitle>{{ $settings->getFinal('document.title', '[document_title]') }}</x-slot>

    @push('metaData')
        {!! $settings->getFinal('document.meta_data', '') !!}
        {!! $structuredData->render() !!}
    @endpush

    <div class="container mb-2">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 py-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('index') }}">{{ __('Home') }}</a>
                </li>
                <li class="breadcrumb-item active tw-line-clamp-1">{{ $settings->getFinal('document.main_heading', '[document_title]') }}</li>
            </ol>
        </nav>
        <!-- [END] Breadcrumb -->

        <h1 class="fs-2 mb-2">{{ $settings->getFinal('document.main_heading', '[document_title]') }}</h1>
    </div>

    <div class="container">
        <div class="row g-2">
            <div class="col-12 col-lg-8">
                @if ($settings->getFinal('document.show_preview', 'Y') == 'Y')
                    <!-- Viewer -->
                    <div class="mb-2">
                        <iframe src="https://drive.google.com/viewerng/viewer?url={{ $document->file_url }}&embedded=true" frameborder="0" class="w-100 tw-h-[500px] rounded border"></iframe>
                    </div>
                    <!-- [END] Viewer -->
                @endif

                <!-- File detail -->
                <div class="card shadow-sm mb-2">
                    <div class="card-body">
                        <h2 class="fs-4 mb-2">{{ $document->title }}</h2>

                        <!-- Author name -->
                        <div class="d-flex align-items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                            </svg>
                            <div class="ms-2 tw-line-clamp-1"><span class="visually-hidden">{{ __('Author') }}</span> {{ $document->author }}</div>
                        </div>
                        <!-- [END] Author name -->

                        <!-- Total views -->
                        <div class="d-flex align-items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                            </svg>
                            <div class="ms-2">{{ trans_choice('main.total_views', $document->views) }}</div>
                        </div>
                        <!-- [END] Total views -->

                        <!-- Total downloads -->
                        <div class="d-flex align-items-center mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-arrow-down-fill" viewBox="0 0 16 16">
                                <path d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2zm2.354 6.854-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 9.293V5.5a.5.5 0 0 1 1 0v3.793l1.146-1.147a.5.5 0 0 1 .708.708z"/>
                            </svg>
                            <div class="ms-2">{{ trans_choice('main.total_downloads', $document->downloads) }}</div>
                        </div>
                        <!-- [END] Total downloads -->

                        <!-- File size -->
                        <div class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-binary-fill" viewBox="0 0 16 16">
                                <path d="M5.526 9.273c-.542 0-.832.563-.832 1.612 0 .088.003.173.006.252l1.56-1.143c-.126-.474-.375-.72-.733-.72zm-.732 2.508c.126.472.372.718.732.718.54 0 .83-.563.83-1.614 0-.085-.003-.17-.006-.25l-1.556 1.146z"/>
                                <path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM7.05 10.885c0 1.415-.548 2.206-1.524 2.206C4.548 13.09 4 12.3 4 10.885c0-1.412.548-2.203 1.526-2.203.976 0 1.524.79 1.524 2.203zm3.805 1.52V13h-3v-.595h1.181V9.5h-.05l-1.136.747v-.688l1.19-.786h.69v3.633h1.125z"/>
                            </svg>
                            <div class="ms-2">{{ \App\Helpers\Text::fileSize($document->file_size) }}</div>
                        </div>
                        <!-- [END] File size -->

                        <hr>

                        <div>
                            <form action="{{ route('download', [$document->{$settings->get('document.slug')}]) }}" method="POST" target="_blank">
                                @csrf
                                <button class="btn btn-outline-secondary px-5" type="submit" id="downloadButton">
                                    <div class="d-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                        </svg>
                                        <span class="ms-2">{{ __('Save Document') }}</span>
                                    </div>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
                <!-- [END] File detail -->

                <!-- Transcript -->
                <div class="card shadow-sm mb-2">
                    <div class="card-body">{!! \App\Helpers\Text::transcript($documentTranscript) !!}</div>
                </div>
                <!-- [END] Transcript -->
            </div>
            <div class="col-12 col-lg-4">
                <h2 class="fs-5 mb-2">{{ __('Related Documents') }}</h2>

                <!-- Related documents -->
                @foreach ($relatedDocuments as $relatedDocument)
                    <div class="mb-2">
                        <x-main.components.content.document :document="$relatedDocument" :slug="$settings->get('document.slug')" />
                    </div>
                @endforeach
                <!-- [END] Related documents -->
            </div>
        </div>
    </div>
</x-main.layouts.app>