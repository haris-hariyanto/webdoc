<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Scribd') }}</x-slot>

    <div class="row">
        <div class="col-12 col-lg-6">

            @if (session('success'))
                <x-admin.components.alert>
                    {{ session('success') }}
                </x-admin.components.alert>
            @endif
        
            @if (session('error'))
                <x-admin.components.alert type="danger">
                    {{ session('error') }}
                </x-admin.components.alert>
            @endif

            <form action="{{ route('admin.settings.scrapers.scribd') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <b>{{ __('Scribd') }}</b>
                    </div>
                    <div class="card-body">
                        <x-admin.forms.textarea name="cookies" :label="__('Cookies')" style="white-space: pre; overflow-wrap: normal; overflow-x: scroll;">{{ old('cookies') ?? $settings->get('cookies', '') }}</x-admin.forms.textarea>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-admin.layouts.app>