<x-main.layouts.app :use-recaptcha="true">
    <x-slot:pageTitle>{{ $document->title }}</x-slot>

    <div class="container">
        <div class="row g-0 justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h1 class="fs-4">{{ __('Save Document') }} - {{ $document->title }}</h1>

                        <form action="{{ route('get-file', [$document->{$settings->get('slug')}]) }}" method="POST" class="my-5">
                            @csrf

                            <div class="mb-3 d-flex justify-content-center">
                                <div>
                                    <x-main.forms.recaptcha size="normal" />
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary px-5">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                    </svg>
                                    <span class="ms-2">{{ __('Download') }}</span>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main.layouts.app>