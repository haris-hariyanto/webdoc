<x-main.layouts.app :use-recaptcha="true">
    <x-slot:pageTitle>{{ __('Contact Us') }}</x-slot>

    <div class="container">
        <div class="row g-2 justify-content-center">
            <div class="col-12 col-sm-10 col-md-8">

                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 py-2">
                        <li class="breadcrumb-item">
                            <a href="{{ route('index') }}">{{ __('Home') }}</a>
                        </li>
                        <li class="breadcrumb-item active tw-line-clamp-1">{{ __('Contact Us') }}</li>
                    </ol>
                </nav>
                <!-- [END] Breadcrumb -->

                <h1 class="fs-2 mb-3">{{ __('Contact Us') }}</h1>

                @if (session('status'))
                    <div class="alert alert-success mb-2" role="alert">{{ session('status') }}</div>
                @endif

                @if (session('recaptchaInvalid'))
                    <div class="alert alert-danger mb-2">{{ session('recaptchaInvalid') }}</div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('contact') }}" method="POST">
                            @csrf

                            <x-main.forms.input-text name="name" :label="__('Your name')" />
                            <x-main.forms.input-text name="email" :label="__('Your email')" />
                            <x-main.forms.input-text name="subject" :label="__('Subject')" />
                            <x-main.forms.textarea name="message" :label="__('Message')" rows="5">{{ old('message') }}</x-main.forms.textarea>

                            <div class="mb-3">
                                <x-main.forms.recaptcha size="normal" />
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary px-5">{{ __('Send') }}</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-main.layouts.app>