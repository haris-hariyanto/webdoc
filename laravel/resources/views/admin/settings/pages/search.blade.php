<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Page Settings') . ' : ' . __('Search Page') }}</x-slot>

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

            <form action="{{ route('admin.settings.search') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <b>{{ __('Page Settings') . ' : ' . __('Search Page') }}</b>
                    </div>
                    <div class="card-body">
                        <div class="callout callout-info">
                            <div><b>{{ __('Variables') }}</b></div>
                            <ul>
                                <li><b>[app_name]</b> &dash; {{ config('variables.app_name') }}</li>
                                <li><b>[current_url]</b> &dash; {{ config('variables.current_url') }}</li>
                                <li><b>[keyword]</b> &dash; {{ config('variables.keyword') }}</li>
                            </ul>
                        </div>

                        @if ($pageExample)
                            <p>{{ __('Page example') }} : <a href="{{ $pageExample }}" target="_blank">{{ $pageExample }}</a></p>
                        @endif

                        <x-admin.forms.input-text name="permalink" :label="__('Permalink')" :value="old('permalink') ?? $settings->get('permalink', 'document')" />
                        <x-admin.forms.input-text name="title" :label="__('Page title') . ' (<title>...</title>)'" :value="old('title') ?? $settings->get('title', __('Search Result') . ' : ' . '[keyword]')" />
                        <x-admin.forms.input-text name="main_heading" :label="__('Main heading') . ' (<h1>...</h1>)'" :value="old('main_heading') ?? $settings->get('main_heading', __('Search Result') . ' : ' . '[keyword]')" />
                        <x-admin.forms.textarea name="brief_paragraph" :label="__('Brief paragraph')" rows="3">{{ old('brief_paragraph') ?? $settings->get('brief_paragraph', '') }}</x-admin.forms.textarea>                        
                        <x-admin.forms.input-text name="total_documents" :label="__('Total documents')" :value="old('total_documents') ?? $settings->get('total_documents', 12)" />
                        
                        <hr>

                        <x-admin.forms.textarea name="meta_data" :label="__('Meta Data')">{{ old('meta_data') ?? $settings->get('meta_data', '') }}</x-admin.forms.textarea>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-admin.layouts.app>