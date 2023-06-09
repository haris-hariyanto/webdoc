{{-- Data Properties ['label', 'isRequired'] Attributes ['name'] --}}

@props(['label', 'isRequired' => false])

<div class="form-group">
    <label for="field{{ Str::studly($attributes->get('name')) }}">
        {{ $label }}
        @if ($isRequired)
            <span class="text-danger">*</span>
        @endif
    </label>
    <input
        {{ $attributes
            ->class(['form-control', 'is-invalid' => $errors->has($attributes->get('name'))]) 
            ->merge(['type' => 'text', 'autocomplete' => 'off', 'id' => 'field' . Str::studly($attributes->get('name'))]) }}
        value="{{ empty($attributes->get('value')) ? old($attributes->get('name')) : $attributes->get('value') }}"
        @error($attributes->get('name')) aria-describedby="validation{{ Str::studly($attributes->get('name')) }}Feedback" @enderror
    >
    @error($attributes->get('name'))
        <div class="invalid-feedback" id="validation{{ Str::studly($attributes->get('name')) }}Feedback">
            {{ $message }}
        </div>
    @enderror
</div>