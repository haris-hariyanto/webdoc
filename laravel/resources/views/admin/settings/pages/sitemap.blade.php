<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Page Settings') . ' : ' . __('Sitemap') }}</x-slot>

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

            <form action="{{ route('admin.settings.sitemap') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <b>{{ __('Page Settings') . ' : ' . __('Sitemap') }}</b>
                    </div>
                    <div class="card-body">
                        <x-admin.forms.input-text name="total_items" :label="__('Total items')" :value="old('total_items') ?? $settings->get('total_items', 3000)" />
                        <x-admin.forms.input-text name="change_frequency" :label="__('Change frequency')" :value="old('change_frequency') ?? $settings->get('change_frequency', '')" />
                        <x-admin.forms.input-text name="priority" :label="__('Priority')" :value="old('priority') ?? $settings->get('priority')" />
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-admin.layouts.app>