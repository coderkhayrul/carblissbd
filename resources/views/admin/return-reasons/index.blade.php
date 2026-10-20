@extends('admin.layouts.app')
@section('admin-content')
    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="mb-0">Return Reason List</h5>
            <a href="{{ route('admin.return-reasons.create') }}" class="btn btn-primary">Add Return Reason</a>
        </div>
        <div class="table-responsive">
            <table class="data-table table table-bordered align-middle">
                <thead><tr><th>SL</th><th>Title</th><th>Description</th><th>Serial</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach ($returnReasons as $returnReason)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $returnReason->title }}</td>
                            <td style="min-width: 180px; max-width: 360px; overflow-wrap: anywhere;">{{ $returnReason->description ?? '—' }}</td>
                            <td>{{ $returnReason->serial_no }}</td>
                            <td class="text-nowrap">
                                <form action="{{ route('admin.return-reasons.status.update', $returnReason) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $returnReason->status ? 0 : 1 }}">
                                    <label class="switch switch-primary switch-sm">
                                        <input type="checkbox" class="switch-input return-reason-status" @checked($returnReason->status) aria-label="Toggle status for {{ $returnReason->title }}">
                                        <span class="switch-toggle-slider"></span>
                                        <span class="switch-label">{{ $returnReason->status ? 'Active' : 'Inactive' }}</span>
                                    </label>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.return-reasons.edit', $returnReason) }}" class="btn btn-sm btn-label-primary">Edit</a>
                                    <form action="{{ route('admin.return-reasons.destroy', $returnReason) }}" method="POST" class="return-reason-delete" data-name="{{ $returnReason->title }}">
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
        const returnReasonTable = document.querySelector('.data-table');
        returnReasonTable.addEventListener('change', function(event) {
            const input = event.target.closest('.return-reason-status');
            if (!input) return;
            input.disabled = true;
            input.form.requestSubmit();
        });
        returnReasonTable.addEventListener('submit', function(event) {
            const form = event.target.closest('.return-reason-delete');
            if (!form) return;
            event.preventDefault();
            Swal.fire({
                title: 'Delete return reason?',
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
            columnDefs: [{targets: [4, 5], orderable: false}],
            language: {emptyTable: 'No return reasons found.'},
            layout: {
                topStart: {rowClass: 'row m-3 my-0 justify-content-between', features: [{pageLength: {menu: [10, 25, 50, 100], text: 'Show_MENU_entries'}}]},
                topEnd: {search: {placeholder: 'Search return reasons...'}},
                bottomStart: {rowClass: 'row mx-3 justify-content-between', features: ['info']},
                bottomEnd: 'paging'
            }
        });
    </script>
@endpush
