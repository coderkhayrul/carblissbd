@extends('admin.layouts.app')
@section('admin-content')
    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="mb-0">Attribute List</h5>
            <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary">Add Attribute</a>
        </div>
        <div class="table-responsive">
            <table class="data-table table table-bordered align-middle">
                <thead><tr><th>SL</th><th>Name</th><th>Type</th><th>Slug</th><th>Color Code</th><th>Description</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach ($attributes as $attribute)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $attribute->name }}</td>
                            <td>{{ $attribute->attributeType?->name ?? '—' }}</td>
                            <td>{{ $attribute->slug }}</td>
                            <td>{{ $attribute->color_code ?? '—' }}</td>
                            <td style="min-width: 180px; max-width: 360px; overflow-wrap: anywhere;">{{ $attribute->description ?? '—' }}</td>
                            <td class="text-nowrap">
                                <form action="{{ route('admin.attributes.status.update', $attribute) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $attribute->status ? 0 : 1 }}">
                                    <label class="switch switch-primary switch-sm">
                                        <input type="checkbox" class="switch-input attribute-status" @checked($attribute->status) aria-label="Toggle status for {{ $attribute->name }}">
                                        <span class="switch-toggle-slider"></span>
                                        <span class="switch-label">{{ $attribute->status ? 'Active' : 'Inactive' }}</span>
                                    </label>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.attributes.edit', $attribute) }}" class="btn btn-sm btn-label-primary">Edit</a>
                                    <form action="{{ route('admin.attributes.destroy', $attribute) }}" method="POST" class="attribute-delete" data-name="{{ $attribute->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-label-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('backend_script')
    <script>
        const attributeTable = document.querySelector('.data-table');
        attributeTable.addEventListener('change', function(event) {
            const input = event.target.closest('.attribute-status');
            if (!input) return;
            input.disabled = true;
            input.form.requestSubmit();
        });
        attributeTable.addEventListener('submit', function(event) {
            const form = event.target.closest('.attribute-delete');
            if (!form) return;
            event.preventDefault();
            Swal.fire({
                title: 'Delete attribute?',
                text: 'Delete "' + form.dataset.name + '"? This cannot be undone.',
                icon: 'warning', showCancelButton: true, focusCancel: true,
                confirmButtonText: 'Yes, delete it!', cancelButtonText: 'Cancel',
                customClass: {confirmButton: 'btn btn-danger me-3', cancelButton: 'btn btn-label-secondary'},
                buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.querySelector('button').disabled = true;
                    HTMLFormElement.prototype.submit.call(form);
                }
            });
        });
        new DataTable('.data-table', {
            scrollX: true,
            order: [],
            columnDefs: [{targets: [6, 7], orderable: false}],
            language: {emptyTable: 'No attributes found.'},
            layout: {
                topStart: {rowClass: 'row m-3 my-0 justify-content-between', features: [{pageLength: {menu: [10, 25, 50, 100], text: 'Show_MENU_entries'}}]},
                topEnd: {search: {placeholder: 'Search attributes...'}},
                bottomStart: {rowClass: 'row mx-3 justify-content-between', features: ['info']},
                bottomEnd: 'paging'
            }
        });
    </script>
@endpush
