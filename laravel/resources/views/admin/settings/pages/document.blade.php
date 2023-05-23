<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Page Settings') . ' : ' . __('Document') }}</x-slot>

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

            <form action="{{ route('admin.settings.document') }}" method="POST">
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
                                <li><b>[document_title]</b> &dash; {{ config('variables.document_title') }}</li>
                                <li><b>[document_file_type]</b> &dash; {{ config('variables.document_file_type') }}</li>
                                <li><b>[document_pages]</b> &dash; {{ config('variables.document_pages') }}</li>
                                <li><b>[document_file_size]</b> &dash; {{ config('variables.document_file_size') }}</li>
                            </ul>
                        </div>

                        @if ($pageExample)
                            <p>{{ __('Page example') }} : <a href="{{ $pageExample }}" target="_blank">{{ $pageExample }}</a></p>
                        @endif

                        <div class="form-group">
                            <div>
                                <label>{{ __('Slug') }}</label>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <x-admin.forms.radio name="slug" label="[slug]" value="slug" :selected="$settings->get('slug', 'slug')" />
                                </div>
                                <div class="col-12">
                                    <x-admin.forms.radio name="slug" label="[slug].html" value="alt_slug_1" :selected="$settings->get('slug', 'slug')" />
                                </div>
                                <div class="col-12">
                                    <x-admin.forms.radio name="slug" label="[slug]-[id]" value="alt_slug_2" :selected="$settings->get('slug', 'slug')" />
                                </div>
                                <div class="col-12">
                                    <x-admin.forms.radio name="slug" label="[slug]-[id].html" value="alt_slug_3" :selected="$settings->get('slug', 'slug')" />
                                </div>
                                <div class="col-12">
                                    <x-admin.forms.radio name="slug" label="[id]-[slug]" value="alt_slug_4" :selected="$settings->get('slug', 'slug')" />
                                </div>
                                <div class="col-12">
                                    <x-admin.forms.radio name="slug" label="[id]-[slug].html" value="alt_slug_5" :selected="$settings->get('slug', 'slug')" />
                                </div>
                            </div>
                        </div>

                        <x-admin.forms.input-text name="permalink" :label="__('Permalink')" :value="old('permalink') ?? $settings->get('permalink', 'document')" />
                        <x-admin.forms.input-text name="title" :label="__('Page title') . ' (<title>...</title>)'" :value="old('title') ?? $settings->get('title', '[document_title]')" />
                        <x-admin.forms.input-text name="main_heading" :label="__('Main heading') . ' (<h1>...</h1>)'" :value="old('main_heading') ?? $settings->get('main_heading', '[document_title]')" />
                        
                        <hr>

                        <div class="form-group">
                            <div>
                                <label>{{ __('Show preview') }}</label>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <x-admin.forms.radio name="show_preview" :label="__('Yes')" value="Y" :selected="$settings->get('show_preview', 'Y')" />
                                </div>
                                <div class="col-6">
                                    <x-admin.forms.radio name="show_preview" :label="__('No')" value="N" :selected="$settings->get('show_preview', 'Y')" />
                                </div>
                            </div>
                        </div>

                        <x-admin.forms.input-text name="related_documents" :label="__('Related documents')" :value="old('related_documents') ?? $settings->get('related_documents', 15)" />

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