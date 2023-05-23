@props(['document', 'slug'])

<div class="card shadow-sm">
    <div class="card-body p-2">
        <div class="d-flex">
            <div class="flex-shrink-0 me-2 overflow-hidden rounded tw-shadow-md">
                <img src="{{ !empty($document->thumbnail) ? $document->thumbnail : asset('assets/main/images/thumbnail.jpg') }}" alt="{{ !empty($document->thumbnail) ? $document->title : __('Document') }}" class="tw-w-32 tw-h-40 tw-object-cover" loading="lazy">
            </div>
            <div class="d-flex flex-column w-100">
                <!-- Document title -->
                <div>
                    <a href="{{ route('document', [$document->{$slug}]) }}" class="tw-line-clamp-2 tw-text-base tw-font-semibold text-break">{{ $document->title }}</a>
                </div>
                <!-- [END] Document title -->

                <!-- Total pages -->
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
                            <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                        </svg>
                    </div>
                    <div class="mt-1">{{ trans_choice('main.total_pages', $document->pages) }}</div>
                </div>
                <!-- [END] Total pages -->

                <!-- Document views -->
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-binary" viewBox="0 0 16 16">
                            <path d="M7.05 11.885c0 1.415-.548 2.206-1.524 2.206C4.548 14.09 4 13.3 4 11.885c0-1.412.548-2.203 1.526-2.203.976 0 1.524.79 1.524 2.203zm-1.524-1.612c-.542 0-.832.563-.832 1.612 0 .088.003.173.006.252l1.559-1.143c-.126-.474-.375-.72-.733-.72zm-.732 2.508c.126.472.372.718.732.718.54 0 .83-.563.83-1.614 0-.085-.003-.17-.006-.25l-1.556 1.146zm6.061.624V14h-3v-.595h1.181V10.5h-.05l-1.136.747v-.688l1.19-.786h.69v3.633h1.125z"/>
                            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                        </svg>
                    </div>
                    <div class="mt-1">{{ strtoupper($document->file_type) }}</div>
                </div>
                <!-- [END] Document views -->

                <div class="mt-auto text-end w-100">
                    <a href="{{ route('document', [$document->{$slug}]) }}" class="btn btn-outline-secondary btn-sm">{{ __('Document Detail') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>