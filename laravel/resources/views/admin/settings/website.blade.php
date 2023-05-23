<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Website Settings') }}</x-slot:pageTitle>

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

            <form action="{{ route('admin.settings.website') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <b>{{ __('Website Settings') }}</b>
                    </div>
                    <div class="card-body">
                        <x-admin.forms.input-text name="name" :label="__('Website name')" :value="old('name') ?? $settings->get('name', config('app.name'))" />
                        <x-admin.forms.textarea name="top_scripts" :label="__('Script before </head>')">{{ old('top_scripts') ?? $settings->get('top_scripts', '') }}</x-admin.forms.textarea>
                        <x-admin.forms.textarea name="bottom_scripts" :label="__('Script before </body>')">{{ old('bottom_scripts') ?? $settings->get('bottom_scripts', '') }}</x-admin.forms.textarea>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-admin.layouts.app>