<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Page Settings') . ' : ' . __('Home') }}</x-slot>

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

            <form action="{{ route('admin.settings.home') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <b>{{ __('Page Settings') . ' : ' . __('Home') }}</b>
                    </div>
                    <div class="card-body">
                        <div class="callout callout-info">
                            <div><b>{{ __('Variables') }}</b></div>
                            <ul>
                                <li><b>[app_name]</b> &dash; {{ config('variables.app_name') }}</li>
                                <li><b>[current_url]</b> &dash; {{ config('variables.current_url') }}</li>
                            </ul>
                        </div>

                        <p>{{ __('URL') }} : <a href="{{ route('index') }}" target="_blank">{{ route('index') }}</a></p>

                        <x-admin.forms.input-text name="title" :label="__('Page title') . ' (<title>...</title>)'" :value="old('title') ?? $settings->get('title', '[app_name]')" />
                        <x-admin.forms.input-text name="main_heading" :label="__('Main heading') . ' (<h1>...</h1>)'" :value="old('main_heading') ?? $settings->get('main_heading', '[app_name]')" />
                        <x-admin.forms.textarea name="brief_paragraph" :label="__('Brief paragraph')" rows="3">{{ old('brief_paragraph') ?? $settings->get('brief_paragraph', '') }}</x-admin.forms.textarea>

                        <hr>

                        <x-admin.forms.input-text name="popular_documents_subheading" :label="__('Popular documents heading')" :value="old('popular_documents_subheading') ?? $settings->get('popular_documents_subheading', __('Top Documents'))" />
                        <x-admin.forms.textarea name="popular_documents_paragraph" :label="__('Popular documents paragraph')" rows="3">{{ old('popular_documents_paragraph') ?? $settings->get('popular_documents_paragraph', '') }}</x-admin.forms.textarea>
                        <x-admin.forms.input-text name="popular_documents_total" :label="__('Popular documents total')" :value="old('popular_documents_total') ?? $settings->get('popular_documents_total', 12)" />

                        <hr>

                        <x-admin.forms.input-text name="new_documents_subheading" :label="__('New documents heading')" :value="old('new_documents_subheading') ?? $settings->get('new_documents_subheading', __('New Documents'))" />
                        <x-admin.forms.textarea name="new_documents_paragraph" :label="__('New documents paragraph')" rows="3">{{ old('new_documents_paragraph') ?? $settings->get('new_documents_paragraph', '') }}</x-admin.forms.textarea>
                        <x-admin.forms.input-text name="new_documents_total" :label="__('New documents total')" :value="old('new_documents_total') ?? $settings->get('new_documents_total', 12)" />

                        <hr>

                        <x-admin.forms.textarea name="article" :label="__('Article')">{{ old('article') ?? $settings->get('article', '') }}</x-admin.forms.textarea>
                        <p class="text-muted">
                            {{ __('Title') }} : <code>&lt;h2&gt;</code><br>
                            {{ __('Subtitle') }} : <code>&lt;h3&gt;</code><br>
                        </p>
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