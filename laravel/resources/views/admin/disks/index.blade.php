<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Disks') }}</x-slot>

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

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.disks.create') }}" class="btn btn-primary">{{ __('Create Disk') }}</a>
    </div>

    <div x-data="modalDelete">
        <div class="card">
            <div class="card-body">
                <div id="toolbar">
                    <button class="btn btn-danger disabled" type="button" data-toggle="modal" data-target="#modalBulkDelete" id="bulkDeleteBtn">{{ __('Delete') }}</button>
                </div>
                <table
                    data-toggle="table"
                    data-url="{{ route('admin.disks.index.data') }}"
                    data-pagination="true"
                    data-side-pagination="server"
                    data-search="true"
                    data-show-columns="true"
                    data-show-columns-toggle-all="true"
                    data-toolbar="#toolbar"
                    data-id-field="id"
                    data-select-item-name="disks"
                    id="mainTable"
                >
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-field="id" data-sortable="true" data-width="1" data-visible="false">{{ __('ID') }}</th>
                            <th data-field="name" data-sortable="true" data-visible="true">{{ __('Disk name') }}</th>
                            <th data-field="total_size" data-sortable="true" data-visible="true">{{ __('Size') }}</th>
                            <th data-field="total_files" data-sortable="true" data-visible="true">{{ __('Total documents') }}</th>
                            <th data-field="is_active" data-sortable="false" data-visible="true">{{ __('Active') }}</th>
                            <th data-field="endpoint" data-sortable="false" data-visible="false">{{ __('Endpoint') }}</th>
                            <th data-field="bucket" data-sortable="false" data-visible="false">{{ __('Bucket name') }}</th>
                            <th data-field="menu" data-align="center" data-switchable="false" data-width="1">{{ __('Menu') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <x-admin.components.modal name="delete">
            <x-slot:modalTitle>{{ __('Delete Disk?') }}</x-slot>
            <p class="mb-0">
                {{ __('Delete Disk:') }} <span class="font-weight-bold" id="diskToDelete" x-text="diskName"></span>.
                {{ __('Associated data will be deleted.') }}
            </p>
            <x-slot:modalFooter>
                <form :action="linkDelete" method="POST" id="formDelete">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">{{ __('Delete') }}</button>
                </form>
            </x-slot>
        </x-admin.components.modal>

        <x-admin.components.modal name="bulkDelete">
            <x-slot:modalTitle>{{ __('Delete Disks?') }}</x-slot>
            <p class="mb-0">
                {!! __('Delete :count disks?', ['count' => '<span id="totalDisksToDelete"></span>']) !!}
                {{ __('Associated data will be deleted.') }}
            </p>
            <x-slot:modalFooter>
                <form action="{{ route('admin.disks.bulk-delete') }}" method="POST" id="formDelete">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="disksIDToDelete" value="">
                    <button type="submit" class="btn btn-danger" id="bulkDeleteSubmit">{{ __('Delete') }}</button>
                </form>
            </x-slot>
        </x-admin.components.modal>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="{{ asset('assets/admin/plugins/bootstrap-table/bootstrap-table.min.css') }}">
    @endpush

    @push('scriptsBottom')
        <script src="{{ asset('assets/admin/plugins/bootstrap-table/bootstrap-table.min.js') }}"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('modalDelete', () => ({
                    linkDelete: false,
                    diskName: false,
                    deleteItem(e) {
                        this.linkDelete = e.target.dataset.linkDelete;
                        this.diskName = e.target.dataset.name;
                    },
                }));
            });

            $('#mainTable').bootstrapTable({
                onPostBody: function () {
                    const bulkDeleteButton = document.getElementById('bulkDeleteBtn');
                    const checkboxes = document.querySelectorAll('input[name="disks"], input[name="btSelectAll"]');
                    const totalDisksToDelete = document.getElementById('totalDisksToDelete');

                    checkboxes.forEach(e => {
                        e.addEventListener('change', () => {
                            const checkedCheckboxes = document.querySelectorAll('input[name="disks"]:checked');
                            if (checkedCheckboxes.length > 0) {
                                bulkDeleteButton.classList.remove('disabled');
                                totalDisksToDelete.innerHTML = checkedCheckboxes.length;
                            }
                            else {
                                bulkDeleteButton.classList.add('disabled');
                            }
                        });
                    });
                },
            });

            const bulkDeleteSubmit = document.getElementById('bulkDeleteSubmit');
            const formDelete = document.getElementById('formDelete');
            bulkDeleteSubmit.addEventListener('click', (e) => {
                const disksToDelete = [];
                const checkedCheckboxes = document.querySelectorAll('input[name="disks"]:checked');
                checkedCheckboxes.forEach(e => {
                    disksToDelete.push(e.value);
                });
                const JSONDisksToDelete = JSON.stringify(disksToDelete);

                const diskField = document.querySelectorAll('input[name="disksIDToDelete"]');
                if (diskField.length == 1) {
                    diskField[0].value = JSONDisksToDelete;
                }
            });
        </script>
    @endpush
</x-admin.layouts.app>