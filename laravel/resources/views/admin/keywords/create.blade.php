<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Create Keyword') }}</x-slot>
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

            <form action="{{ route('admin.keywords.store') }}" method="POST">
                @csrf
                @method('POST')

                <div class="card">
                    <div class="card-body">
                        <x-admin.forms.textarea name="keyword" :label="__('Keyword') . ' (' . __('separate with commas') . ')'"></x-admin.forms.textarea>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="checkboxHighPriority" name="high_priority" value="Y">
                            <label for="checkboxHighPriority" class="custom-control-label">{{ __('High priority') }}</label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-admin.layouts.app>