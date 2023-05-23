<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Edit Document') }}</x-slot>
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

            <form action="{{ route('admin.documents.update', ['document' => $document]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-body">
                        <x-admin.forms.input-text name="title" :label="__('Title')" :value="old('title') ?? $document->title" :is-required="true" />
                        <x-admin.forms.input-text name="slug" :label="__('Slug')" :value="old('slug') ?? $document->slug" :is-required="true" />
                        <x-admin.forms.input-text name="author" :label="__('Author')" :value="old('author') ?? $document->author" :is-required="true" />
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-admin.layouts.app>