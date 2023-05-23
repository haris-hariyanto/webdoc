<x-main.layouts.app>
    <x-slot:pageTitle>{{ __('Account Settings') }}</x-slot>

    <div class="container">
        <h1 class="fs-3 mb-3 mt-3">{{ __('Account Settings') }}</h1>
        <div class="row g-2 justify-content-center">
            <div class="col-12 col-sm-10 col-md-5 col-lg-3">
                @include('main.account.account-settings._sidebar')
            </div>
            <div class="col-12 col-sm-10 col-md-7 col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="pb-2 border-bottom">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-bold">{{ __('Username') }}</div>
                                    <div class="text-break">{{ Auth::user()->username }}</div>
                                </div>
                                <div class="ms-2">
                                    <a href="{{ route('account.account-settings.username.edit') }}">{{ __('Change') }}</a>
                                </div>
                            </div>
                        </div>

                        <div class="pb-2 border-bottom">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-bold">{{ __('Email') }}</div>
                                    <div class="text-break">{{ Auth::user()->email }}</div>
                                </div>
                                <div class="ms-2">
                                    <a href="{{ route('account.account-settings.email.edit') }}">{{ __('Change') }}</a>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-bold">{{ __('Password') }}</div>
                                    <div class="text-break">********</div>
                                </div>
                                <div class="ms-2">
                                    <a href="{{ route('account.account-settings.password.edit') }}">{{ __('Change') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main.layouts.app>