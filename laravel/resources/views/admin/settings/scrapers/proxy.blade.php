<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Proxy') }}</x-slot>

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

            <form action="{{ route('admin.settings.scrapers.proxy') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <b>{{ __('Proxy') }}</b>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div>
                                <label>{{ __('Use proxy') }}</label>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <x-admin.forms.radio name="use_proxy" :label="__('Yes')" value="Y" :selected="$settings->get('use_proxy', 'Y')" />
                                </div>
                                <div class="col-6">
                                    <x-admin.forms.radio name="use_proxy" :label="__('No')" value="N" :selected="$settings->get('use_proxy', 'Y')" />
                                </div>
                            </div>
                        </div>

                        <x-admin.forms.textarea name="proxies" :label="__('Proxies')">{{ old('proxies') ?? $settings->get('proxies') }}</x-admin.forms.textarea>
                        <p>Format: <code>IP:port:username:password</code></p>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-admin.layouts.app>