@extends('admin.layouts.app')
@section('admin-content')
    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="mb-0">Brand List</h5>
            <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">Add Brand</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-nowrap">
                <thead><tr><th>SL</th><th>Brand</th><th>Slug</th><th>Serial</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse ($brands as $brand)
                        <tr>
                            <td>{{ $brands->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $brand->logo ? asset($brand->logo) : asset('backend/assets/extra/preview-image.png') }}" alt="{{ $brand->name }}" width="48" height="48" class="rounded" style="object-fit: contain;">
                                    <span>{{ $brand->name }}</span>
                                </div>
                            </td>
                            <td>{{ $brand->slug }}</td>
                            <td>{{ $brand->serial }}</td>
                            <td>
                                <form action="{{ route('admin.brands.status.update', $brand) }}" method="POST" class="brand-status-form">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $brand->status ? 0 : 1 }}">
                                    <label class="switch switch-primary switch-sm">
                                        <input type="checkbox" class="switch-input brand-status-switch" @checked($brand->status) aria-label="Toggle status for {{ $brand->name }}">
                                        <span class="switch-toggle-slider"></span>
                                        <span class="switch-label">{{ $brand->status ? 'Active' : 'Inactive' }}</span>
                                    </label>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-sm btn-label-primary">Edit</a>
                                    <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="brand-delete-form" data-brand-name="{{ $brand->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-label-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5">No brands found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body overflow-auto">{{ $brands->links() }}</div>
    </div>
@endsection
@push('backend_script')
    <script>
        document.querySelectorAll('.brand-status-switch').forEach(function(input) {
            input.addEventListener('change', function() {
                input.disabled = true;
                input.form.requestSubmit();
            });
        });
        document.querySelectorAll('.brand-delete-form').forEach(function(form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Delete brand?',
                    text: 'Delete "' + form.dataset.brandName + '" and its images? This cannot be undone.',
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
        });
    </script>
@endpush
