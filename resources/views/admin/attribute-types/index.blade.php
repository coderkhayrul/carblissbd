@extends('admin.layouts.app')
@section('admin-content')
    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="mb-0">Attribute Type List</h5>
            <a href="{{ route('admin.attribute-types.create') }}" class="btn btn-primary">Add Attribute Type</a>
        </div>
        <div class="table-responsive">
            <table class="data-table table table-bordered align-middle">
                <thead><tr><th>SL</th><th>Name</th><th>Description</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach ($attributeTypes as $attributeType)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $attributeType->name }}</td>
                            <td style="min-width: 180px; max-width: 360px; overflow-wrap: anywhere;">{{ $attributeType->description ?? '—' }}</td>
                            <td class="text-nowrap">
                                <form action="{{ route('admin.attribute-types.status.update', $attributeType) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $attributeType->status ? 0 : 1 }}">
                                    <label class="switch switch-primary switch-sm">
                                        <input type="checkbox" class="switch-input attribute-type-status" @checked($attributeType->status) aria-label="Toggle status for {{ $attributeType->name }}">
                                        <span class="switch-toggle-slider"></span>
                                        <span class="switch-label">{{ $attributeType->status ? 'Active' : 'Inactive' }}</span>
                                    </label>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.attribute-types.edit', $attributeType) }}" class="btn btn-sm btn-label-primary">Edit</a>
                                    <form action="{{ route('admin.attribute-types.destroy', $attributeType) }}" method="POST" class="attribute-type-delete" data-name="{{ $attributeType->name }}">
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
        const attributeTypeTable = document.querySelector('.data-table');
        attributeTypeTable.addEventListener('change', function(event) {
            const input = event.target.closest('.attribute-type-status');
            if (!input) return;
            input.disabled = true;
            input.form.requestSubmit();
        });
        attributeTypeTable.addEventListener('submit', function(event) {
            const form = event.target.closest('.attribute-type-delete');
            if (!form) return;
            event.preventDefault();
            Swal.fire({
                title: 'Delete attribute type?',
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
            columnDefs: [{targets: [3, 4], orderable: false}],
            language: {emptyTable: 'No attribute types found.'},
            layout: {
                topStart: {rowClass: 'row m-3 my-0 justify-content-between', features: [{pageLength: {menu: [10, 25, 50, 100], text: 'Show_MENU_entries'}}]},
                topEnd: {search: {placeholder: 'Search attribute types...'}},
                bottomStart: {rowClass: 'row mx-3 justify-content-between', features: ['info']},
                bottomEnd: 'paging'
            }
        });
    </script>
@endpush
