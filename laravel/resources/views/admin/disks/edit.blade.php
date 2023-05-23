<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Edit Disk') }}</x-slot>
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

            <form action="{{ route('admin.disks.update', ['disk' => $disk]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-body">
                        <x-admin.forms.input-text name="name" :label="__('Disk name')" placeholder="Cth: Disk 1" :is-required="true" :value="old('name') ?? $disk->name" />
                        <x-admin.forms.input-text name="endpoint" :label="__('Endpoint')" placeholder="Cth: https://sjc1.vultrobjects.com" :is-required="true" :value="old('endpoint') ?? $disk->endpoint" />
                        <x-admin.forms.input-text name="region" :label="__('Region')" placeholder="Cth: sjc1" :value="old('region') ?? $disk->region" />
                        <x-admin.forms.input-text name="access_key" :label="__('Access key')" :is-required="true" :value="old('access_key') ?? $disk->access_key" />
                        <x-admin.forms.input-text name="secret_key" :label="__('Secret key')" :is-required="true" :value="old('secret_key') ?? $disk->secret_key" />
                        <x-admin.forms.input-text name="bucket" :label="__('Bucket')" :is-required="true" :value="old('bucket') ?? $disk->bucket" />

                        <div class="form-group">
                            <div>
                                <label>{{ __('Active') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <x-admin.forms.radio name="is_active" :label="__('Yes')" value="Y" :selected="$disk->is_active" />
                                </div>
                                <div class="col-6">
                                    <x-admin.forms.radio name="is_active" :label="__('No')" value="N" :selected="$disk->is_active" />
                                </div>
                            </div>
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