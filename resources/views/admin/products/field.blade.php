@php($value = old($field, $product->{$field}))
<div class="col-12 {{ ($wide ?? false) ? '' : 'col-md-6' }} mb-4">
    <label class="form-label" for="{{ $field }}">{{ $label }}</label>
    @if (($type ?? 'text') === 'textarea')
        <textarea class="form-control" id="{{ $field }}" name="{{ $field }}" rows="{{ $rows ?? 4 }}" maxlength="{{ $max ?? 10000 }}">{{ $value }}</textarea>
    @elseif (($type ?? 'text') === 'boolean')
        <select class="form-select" id="{{ $field }}" name="{{ $field }}">
            <option value="1" @selected($value == 1)>{{ $yes ?? 'Yes' }}</option>
            <option value="0" @selected($value == 0)>{{ $no ?? 'No' }}</option>
        </select>
    @else
        <input class="form-control" type="{{ $type ?? 'text' }}" id="{{ $field }}" name="{{ $field }}"
            value="{{ $value }}" @required($required ?? false)
            @if (($type ?? 'text') === 'number') min="0" max="99999999.99" step="0.01" @else maxlength="{{ $max ?? 255 }}" @endif>
    @endif
    @if (isset($help))<div class="form-text">{{ $help }}</div>@endif
    @error($field)<div class="text-danger small mt-1">{{ $message }}</div>@enderror
</div>
