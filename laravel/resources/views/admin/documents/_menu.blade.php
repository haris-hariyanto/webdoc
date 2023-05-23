<div class="dropdown">
    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{{ __('Menu') }}</button>
    <div class="dropdown-menu">
        <a href="{{ route('document', [$document->{$slug}]) }}" class="dropdown-item" target="_blank">{{ __('Open Page') }}</a>
        
        <a href="{{ route('admin.documents.edit', ['document' => $document]) }}" class="dropdown-item">{{ __('Edit') }}</a>

        <button
            type="button"
            class="dropdown-item"
            data-toggle="modal"
            data-target="#modalDelete"
            data-link-delete="{{ route('admin.documents.destroy', ['document' => $document]) }}"
            data-name="{{ $document->title }}"
            @click="deleteItem"
        >{{ __('Delete') }}</button>
    </div>
</div>