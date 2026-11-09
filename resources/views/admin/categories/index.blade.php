@extends('admin.layouts.app')
@section('admin-content')
    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="mb-0">Category List</h5>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Add Category</a>
        </div>
        <p class="px-4 mb-3 small text-muted">Set the serial below and click Save. Smaller numbers appear first on desktop and mobile. Categories with the same serial keep their creation order.</p>
        @if ($errors->any())<div class="alert alert-danger mx-4"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <div class="table-responsive">
            <table class="data-table table table-bordered align-middle text-nowrap">
                <thead><tr><th>SL</th><th>Category</th><th>Parent</th><th>Slug</th><th>Serial</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $category->image ? asset($category->image) : asset('backend/assets/extra/preview-image.png') }}" alt="{{ $category->name }}" width="48" height="48" class="rounded" style="object-fit: contain;">
                                    <span>{{ $category->name }}</span>
                                </div>
                            </td>
                            <td>{{ $category->parent?->name ?? 'Main Category' }}</td>
                            <td>{{ $category->slug }}</td>
                            <td data-order="{{ $category->menu_serial }}" data-search="{{ $category->menu_serial }}">
                                <form action="{{ route('admin.categories.serial.update', $category) }}" method="POST" class="d-flex gap-2 align-items-center">
                                    @csrf @method('PATCH')
                                    <input type="number" class="form-control form-control-sm" name="menu_serial" required min="0" max="2147483647" value="{{ $category->menu_serial }}" style="width:90px" aria-label="Serial for {{ $category->name }}">
                                    <button class="btn btn-sm btn-label-primary" type="submit">Save</button>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('admin.categories.status.update', $category) }}" method="POST" class="category-status-form">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $category->status ? 0 : 1 }}">
                                    <label class="switch switch-primary switch-sm">
                                        <input type="checkbox" class="switch-input category-status-switch" @checked($category->status) aria-label="Toggle status for {{ $category->name }}">
                                        <span class="switch-toggle-slider"></span>
                                        <span class="switch-label">{{ $category->status ? 'Active' : 'Inactive' }}</span>
                                    </label>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-label-primary">Edit</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="category-delete-form" data-category-name="{{ $category->name }}">
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
        const categoryTable = document.querySelector('.data-table');
        categoryTable.addEventListener('change', function(event) {
            const input = event.target.closest('.category-status-switch');
            if (!input) return;
            input.disabled = true;
            input.form.requestSubmit();
        });
        categoryTable.addEventListener('submit', function(event) {
                const form = event.target.closest('.category-delete-form');
                if (!form) return;
                event.preventDefault();
                Swal.fire({
                    title: 'Delete category?',
                    text: 'Delete "' + form.dataset.categoryName + '" and its images? This cannot be undone.',
                    icon: 'warning', showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!', cancelButtonText: 'Cancel', focusCancel: true,
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
            columnDefs: [{ targets: [5, 6], orderable: false }],
            language: { emptyTable: 'No categories found.' },
            layout: {
                topStart: { rowClass: 'row m-3 my-0 justify-content-between', features: [{ pageLength: { menu: [10, 25, 50, 100], text: 'Show_MENU_entries' } }] },
                topEnd: { search: { placeholder: 'Search categories...' } },
                bottomStart: { rowClass: 'row mx-3 justify-content-between', features: ['info'] },
                bottomEnd: 'paging'
            }
        });
    </script>
@endpush
