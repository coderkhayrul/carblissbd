@extends('admin.layouts.app')
@section('admin-content')
    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="mb-0">Product List</h5>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add Product</a>
        </div>
        <div class="table-responsive">
            <table class="data-table table table-bordered align-middle">
                <thead><tr><th>SL</th><th>Product</th><th>SKU</th><th>Category / Brand</th><th>Price (BDT)</th><th>Variations</th><th>Published</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><div class="d-flex align-items-center gap-3">
                                <img class="rounded" src="{{ $product->thumbnail_image ? asset($product->thumbnail_image) : asset('backend/assets/extra/preview-image.png') }}" width="48" height="48" style="object-fit: contain;" alt="{{ $product->name }}">
                                <span>{{ $product->name }}</span>
                            </div></td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->category?->name ?? '—' }}<br><small class="text-muted">{{ $product->brand?->name ?? '—' }}</small></td>
                            <td>{{ number_format((float) $product->sale_price, 2) }}</td>
                            <td>{{ $product->variations_count }}</td>
                            <td>{{ $product->publish ? 'Yes' : 'No' }}</td>
                            <td class="text-nowrap">
                                <form action="{{ route('admin.products.status.update', $product) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $product->status ? 0 : 1 }}">
                                    <label class="switch switch-primary switch-sm">
                                        <input type="checkbox" class="switch-input product-status" @checked($product->status) aria-label="Toggle status for {{ $product->name }}">
                                        <span class="switch-toggle-slider"></span>
                                        <span class="switch-label">{{ $product->status ? 'Active' : 'Inactive' }}</span>
                                    </label>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-label-primary">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="product-delete" data-name="{{ $product->name }}">
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
        const productTable = document.querySelector('.data-table');
        productTable.addEventListener('change', function(event) {
            const input = event.target.closest('.product-status');
            if (!input) return;
            input.disabled = true;
            input.form.requestSubmit();
        });
        productTable.addEventListener('submit', function(event) {
            const form = event.target.closest('.product-delete');
            if (!form) return;
            event.preventDefault();
            Swal.fire({
                title: 'Delete product?',
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
            columnDefs: [{targets: [7, 8], orderable: false}],
            language: {emptyTable: 'No products found.'},
            layout: {
                topStart: {rowClass: 'row m-3 my-0 justify-content-between', features: [{pageLength: {menu: [10, 25, 50, 100], text: 'Show_MENU_entries'}}]},
                topEnd: {search: {placeholder: 'Search products...'}},
                bottomStart: {rowClass: 'row mx-3 justify-content-between', features: ['info']},
                bottomEnd: 'paging'
            }
        });
    </script>
@endpush
