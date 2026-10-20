@extends('admin.layouts.app')
@section('admin-content')
    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="mb-0">Menu List</h5>
            <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">Add Menu</a>
        </div>
        <div class="table-responsive">
            <table class="data-table table table-bordered align-middle">
                <thead><tr><th>SL</th><th>Name</th><th>Slug</th><th>Parent</th><th>Serial</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach ($menus as $menu)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $menu->name }}</td>
                            <td>{{ $menu->slug }}</td>
                            <td>{{ $menu->parent?->name ?? 'Main Menu' }}</td>
                            <td>{{ $menu->serial_no }}</td>
                            <td class="text-nowrap">
                                <form action="{{ route('admin.menus.status.update', $menu) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $menu->status ? 0 : 1 }}">
                                    <label class="switch switch-primary switch-sm">
                                        <input type="checkbox" class="switch-input menu-status" @checked($menu->status) aria-label="Toggle status for {{ $menu->name }}">
                                        <span class="switch-toggle-slider"></span>
                                        <span class="switch-label">{{ $menu->status ? 'Active' : 'Inactive' }}</span>
                                    </label>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-sm btn-label-primary">Edit</a>
                                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="menu-delete" data-name="{{ $menu->name }}">
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
        const menuTable = document.querySelector('.data-table');
        menuTable.addEventListener('change', function(event) {
            const input = event.target.closest('.menu-status');
            if (!input) return;
            input.disabled = true;
            input.form.requestSubmit();
        });
        menuTable.addEventListener('submit', function(event) {
            const form = event.target.closest('.menu-delete');
            if (!form) return;
            event.preventDefault();
            Swal.fire({
                title: 'Delete status?',
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
            columnDefs: [{targets: [5, 6], orderable: false}],
            language: {emptyTable: 'No menus found.'},
            layout: {
                topStart: {rowClass: 'row m-3 my-0 justify-content-between', features: [{pageLength: {menu: [10, 25, 50, 100], text: 'Show_MENU_entries'}}]},
                topEnd: {search: {placeholder: 'Search menus...'}},
                bottomStart: {rowClass: 'row mx-3 justify-content-between', features: ['info']},
                bottomEnd: 'paging'
            }
        });
    </script>
@endpush
