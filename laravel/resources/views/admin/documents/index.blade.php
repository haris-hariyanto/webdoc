<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Documents') }}</x-slot>

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
    </div>

    <div x-data="modalDelete">
        <div class="card">
            <div class="card-body">
                <div id="toolbar">
                    <button class="btn btn-danger disabled" type="button" data-toggle="modal" data-target="#modalBulkDelete" id="bulkDeleteBtn">{{ __('Delete') }}</button>
                </div>
                <table
                    data-toggle="table"
                    data-url="{{ route('admin.documents.index.data') }}"
                    data-pagination="true"
                    data-side-pagination="server"
                    data-search="true"
                    data-show-columns="true"
                    data-show-columns-toggle-all="true"
                    data-toolbar="#toolbar"
                    data-id-field="id"
                    data-select-item-name="documents"
                    id="mainTable"
                >
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-field="id" data-sortable="true" data-width="1" data-visible="false">{{ __('ID') }}</th>
                            <th data-field="title" data-sortable="true" data-visible="true">{{ __('Title') }}</th>
                            <th data-field="downloads" data-sortable="true" data-visible="true">{{ __('Downloads') }}</th>
                            <th data-field="views" data-sortable="true" data-visible="true">{{ __('Views') }}</th>
                            <th data-field="file_size" data-sortable="true" data-visible="false">{{ __('File size') }}</th>
                            <th data-field="author" data-sortable="false" data-visible="false">{{ __('Author') }}</th>
                            <th data-field="disk" data-sortable="false" data-visible="false">{{ __('Disk') }}</th>
                            <th data-field="file_type" data-sortable="false" data-visible="true">{{ __('File type') }}</th>
                            <th data-field="pages" data-sortable="true" data-visible="false">{{ __('Pages') }}</th>
                            <th data-field="language" data-sortable="false" data-visible="false">{{ __('Language') }}</th>
                            <th data-field="menu" data-align="center" data-switchable="false" data-width="1">{{ __('Menu') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <x-admin.components.modal name="delete">
            <x-slot:modalTitle>{{ __('Delete Document?') }}</x-slot>
            <p class="mb-0">
                {{ __('Delete Document:') }} <span class="font-weight-bold" id="documentToDelete" x-text="documentName"></span>.
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
            <x-slot:modalTitle>{{ __('Delete Documents?') }}</x-slot>
            <p class="mb-0">
                {!! __('Delete :count documents?', ['count' => '<span id="totalDocumentsToDelete"></span>']) !!}
                {{ __('Associated data will be deleted.') }}
            </p>
            <x-slot:modalFooter>
                <form action="{{ route('admin.documents.bulk-delete') }}" method="POST" id="formDelete">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="documentsIDToDelete" value="">
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
                    documentName: false,
                    deleteItem(e) {
                        this.linkDelete = e.target.dataset.linkDelete;
                        this.documentName = e.target.dataset.name;
                    },
                }));
            });

            $('#mainTable').bootstrapTable({
                onPostBody: function () {
                    const bulkDeleteButton = document.getElementById('bulkDeleteBtn');
                    const checkboxes = document.querySelectorAll('input[name="documents"], input[name="btSelectAll"]');
                    const totalDocumentsToDelete = document.getElementById('totalDocumentsToDelete');

                    checkboxes.forEach(e => {
                        e.addEventListener('change', () => {
                            const checkedCheckboxes = document.querySelectorAll('input[name="documents"]:checked');
                            if (checkedCheckboxes.length > 0) {
                                bulkDeleteButton.classList.remove('disabled');
                                totalDocumentsToDelete.innerHTML = checkedCheckboxes.length;
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
                const documentsToDelete = [];
                const checkedCheckboxes = document.querySelectorAll('input[name="documents"]:checked');
                checkedCheckboxes.forEach(e => {
                    documentsToDelete.push(e.value);
                });
                const JSONDocumentsToDelete = JSON.stringify(documentsToDelete);

                const documentField = document.querySelectorAll('input[name="documentsIDToDelete"]');
                if (documentField.length == 1) {
                    documentField[0].value = JSONDocumentsToDelete;
                }
            });
        </script>
    @endpush
</x-admin.layouts.app>