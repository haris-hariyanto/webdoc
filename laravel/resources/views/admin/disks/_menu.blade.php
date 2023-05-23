<div class="dropdown">
    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{{ __('Menu') }}</button>
    <div class="dropdown-menu">
        <a href="{{ route('admin.disks.edit', ['disk' => $disk]) }}" class="dropdown-item">{{ __('Edit') }}</a>

        <button
            type="button"
            class="dropdown-item"
            data-toggle="modal"
            data-target="#modalDelete"
            data-link-delete="{{ route('admin.disks.destroy', ['disk' => $disk]) }}"
            data-name="{{ $disk->name }}"
            @click="deleteItem"
        >{{ __('Delete') }}</button>
    </div>
</div>