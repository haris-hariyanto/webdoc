<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Scrape Queues') }}</x-slot>

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
        <form action="{{ route('admin.keywords.reset-failed') }}" method="POST">
            @csrf
            @method('PUT')

            <button type="submit" class="btn btn-outline-primary mr-2">{{ __('Reset Failed Queues') }}</button>
        </form>
        <a href="{{ route('admin.keywords.create') }}" class="btn btn-primary">{{ __('Create Keyword') }}</a>
    </div>

    <div x-data="modalDelete">
        <div class="card">
            <div class="card-body">
                <div id="toolbar" class="d-flex align-items-center">
                    <div class="dropdown mr-2" x-data="{ type: 'READY', typeName: '{{ __('Waiting') }}' }" :data-type="type" id="filterRows" x-init="$watch('type', refreshTable)">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{{ __('Status') }} : <span x-text="typeName"></span></button>
                        <div class="dropdown-menu">
                            <button class="dropdown-item" type="button" @click="type = 'all'; typeName = '{{ __('All') }}';">{{ __('All') }}</button>
                            <button class="dropdown-item" type="button" @click="type = 'READY'; typeName = '{{ __('Waiting') }}';">{{ __('Waiting') }}</button>
                            <button class="dropdown-item" type="button" @click="type = 'PROCESS'; typeName = '{{ __('Processing') }}';">{{ __('Processing') }}</button>
                            <button class="dropdown-item" type="button" @click="type = 'DONE'; typeName = '{{ __('Done') }}';">{{ __('Done') }}</button>
                            <button class="dropdown-item" type="button" @click="type = 'FAILED'; typeName = '{{ __('Failed') }}';">{{ __('Failed') }}</button>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle disabled" type="button" data-toggle="dropdown" aria-expanded="false" data-bulk-action="true">{{ __('Bulk Actions') }}</button>
                        <div class="dropdown-menu">
                            <button class="dropdown-item" type="button" data-toggle="modal" data-target="#modalBulkDelete" id="bulkDeleteBtn">{{ __('Delete') }}</button>
                        
                            <form action="{{ route('admin.keywords.bulk-add-priority') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="bulkID" value="">
                                <button type="submit" class="dropdown-item" data-bulk-submit="true">{{ __('Make it High Priotity') }}</button>
                            </form>

                            <form action="{{ route('admin.keywords.bulk-remove-priority') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="bulkID" value="">
                                <button type="submit" class="dropdown-item" data-bulk-submit="true">{{ __('Remove Priority') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
                <table
                    data-toggle="table"
                    data-url="{{ route('admin.keywords.index.data') }}"
                    data-pagination="true"
                    data-side-pagination="server"
                    data-search="true"
                    data-show-columns="true"
                    data-show-columns-toggle-all="true"
                    data-toolbar="#toolbar"
                    data-id-field="id"
                    data-select-item-name="keywords"
                    id="mainTable"
                    class="table-sm"
                    data-page-size="25"
                    data-query-params="filterRows"
                >
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-field="id" data-sortable="true" data-width="1" data-visible="false">{{ __('ID') }}</th>
                            <th data-field="keyword" data-sortable="false" data-visible="true">{{ __('Keyword') }}</th>
                            <th data-field="status" data-sortable="false" data-visible="true">{{ __('Status') }}</th>
                            <th data-field="priority" data-sortable="false" data-visible="true">{{ __('Priority') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <x-admin.components.modal name="delete">
            <x-slot:modalTitle>{{ __('Delete Keyword?') }}</x-slot>
            <p class="mb-0">
                {{ __('Delete Keyword:') }} <span class="font-weight-bold" id="keywordToDelete" x-text="keywordName"></span>.
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
            <x-slot:modalTitle>{{ __('Delete Scrape Queues?') }}</x-slot>
            <p class="mb-0">
                {!! __('Delete :count scrape queues?', ['count' => '<span id="totalKeywordsToDelete"></span>']) !!}
            </p>
            <x-slot:modalFooter>
                <form action="{{ route('admin.keywords.bulk-delete') }}" method="POST" id="formDelete">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="bulkID" value="">
                    <button type="submit" class="btn btn-danger" id="bulkDeleteSubmit" data-bulk-submit="true">{{ __('Delete') }}</button>
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
                    keywordName: false,
                    deleteItem(e) {
                        this.linkDelete = e.target.dataset.linkDelete;
                        this.keywordName = e.target.dataset.name;
                    },
                }));
            });

            $('#mainTable').bootstrapTable({
                onPostBody: function () {
                    const bulkActionBtns = document.querySelectorAll('button[data-bulk-action="true"]');
                    const checkboxes = document.querySelectorAll('input[name="keywords"], input[name="btSelectAll"]');
                    const totalScrapeQueuesToDelete = document.getElementById('totalKeywordsToDelete');

                    checkboxes.forEach(e => {
                        e.addEventListener('change', () => {
                            const checkedCheckboxes = document.querySelectorAll('input[name="keywords"]:checked');
                            bulkActionBtns.forEach(btn => {
                                if (checkedCheckboxes.length > 0) {
                                    btn.classList.remove('disabled');
                                    totalScrapeQueuesToDelete.innerHTML = checkedCheckboxes.length;
                                }
                                else {
                                    btn.classList.add('disabled');
                                }
                            });
                        });
                    });
                },
            });

            const bulkDeleteSubmit = document.getElementById('bulkDeleteSubmit');
            const formDelete = document.getElementById('formDelete');
            bulkDeleteSubmit.addEventListener('click', (e) => {
                const keywordsToDelete = [];
                const checkedCheckboxes = document.querySelectorAll('input[name="keywords"]:checked');
                checkedCheckboxes.forEach(e => {
                    keywordsToDelete.push(e.value);
                });
                const JSONKeywordsToDelete = JSON.stringify(keywordsToDelete);

                const keywordField = document.querySelectorAll('input[name="keywordsIDToDelete"]');
                if (keywordField.length == 1) {
                    keywordField[0].value = JSONKeywordsToDelete;
                }
            });

            const bulkSubmitBtns = document.querySelectorAll('button[data-bulk-submit="true"]');
            bulkSubmitBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const bulkID = [];
                    const checkedCheckboxes = document.querySelectorAll('input[name="keywords"]:checked');
                    checkedCheckboxes.forEach(e => {
                        bulkID.push(e.value);
                    });
                    const JSONBulkID = JSON.stringify(bulkID);

                    const bulkIDFields = document.querySelectorAll('input[name="bulkID"]');
                    bulkIDFields.forEach(field => {
                        field.value = JSONBulkID;
                    });
                });
            });

            function filterRows(params) {
                const type = document.getElementById('filterRows').dataset.type;
                params.status = type;
                return params;
            }

            function refreshTable()
            {
                $(document).ready(function () {
                    const table = $('#mainTable');
                    table.bootstrapTable('refresh');
                });
            }
        </script>
    @endpush
</x-admin.layouts.app>