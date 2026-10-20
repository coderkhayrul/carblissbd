@extends('admin.layouts.app')
@section('admin-content')
    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="mb-0">Coupon List</h5>
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">Add Coupon</a>
        </div>
        <div class="table-responsive">
            <table class="data-table table table-bordered align-middle">
                <thead><tr><th>SL</th><th>Name</th><th>Code</th><th>Discount</th><th>Use Type</th><th>Dates</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach ($coupons as $coupon)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $coupon->name }}</td>
                            <td>{{ $coupon->code }}</td>
                            <td>{{ $coupon->amount }} {{ $coupon->discount_type === 'Percentage' ? '%' : 'BDT' }}</td>
                            <td>{{ $coupon->use_type }}</td>
                            <td class="text-nowrap">{{ $coupon->start_date?->format('d M Y') ?? 'Any time' }} — {{ $coupon->end_date?->format('d M Y') ?? 'No expiry' }}</td>
                            <td class="text-nowrap">
                                <form action="{{ route('admin.coupons.status.update', $coupon) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $coupon->status ? 0 : 1 }}">
                                    <label class="switch switch-primary switch-sm">
                                        <input type="checkbox" class="switch-input coupon-status" @checked($coupon->status) aria-label="Toggle status for {{ $coupon->name }}">
                                        <span class="switch-toggle-slider"></span>
                                        <span class="switch-label">{{ $coupon->status ? 'Active' : 'Inactive' }}</span>
                                    </label>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-label-primary">Edit</a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="coupon-delete" data-name="{{ $coupon->name }}">
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
        const couponTable = document.querySelector('.data-table');
        couponTable.addEventListener('change', function(event) {
            const input = event.target.closest('.coupon-status');
            if (!input) return;
            input.disabled = true;
            input.form.requestSubmit();
        });
        couponTable.addEventListener('submit', function(event) {
            const form = event.target.closest('.coupon-delete');
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
            columnDefs: [{targets: [6, 7], orderable: false}],
            language: {emptyTable: 'No coupons found.'},
            layout: {
                topStart: {rowClass: 'row m-3 my-0 justify-content-between', features: [{pageLength: {menu: [10, 25, 50, 100], text: 'Show_MENU_entries'}}]},
                topEnd: {search: {placeholder: 'Search coupons...'}},
                bottomStart: {rowClass: 'row mx-3 justify-content-between', features: ['info']},
                bottomEnd: 'paging'
            }
        });
    </script>
@endpush
