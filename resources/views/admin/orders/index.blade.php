@extends('admin.layouts.app')
@section('admin-content')
    <div class="card">
        <div class="card-header"><h5 class="mb-0">All Orders</h5></div>
        <div class="table-responsive">
            <table class="data-table table table-bordered align-middle text-nowrap">
                <thead><tr><th>Invoice</th><th>Date</th><th>Customer</th><th>Phone</th><th>Total</th><th>Payment</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->order_no }}</td><td>{{ $order->date?->format('d M Y') }}</td>
                            <td>{{ $order->customerInfo?->name ?? $order->customer?->name }}</td>
                            <td>{{ $order->customerInfo?->phone ?? $order->customer?->mobile }}</td>
                            <td>&#2547;{{ number_format((float) $order->grand_total, 2) }}</td>
                            <td>{{ $order->payment_type }}<br><small>Due: &#2547;{{ number_format((float) $order->due_amount, 2) }}</small></td>
                            <td><span class="badge bg-label-primary">{{ $order->currentStatus?->name ?? 'Pending' }}</span></td>
                            <td><a class="btn btn-sm btn-label-primary" href="{{ route('admin.orders.show', $order) }}">View Order</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('backend_script')
    <script>
        new DataTable('.data-table', {
            scrollX: true, order: [], columnDefs: [{targets: [7], orderable: false}],
            language: {emptyTable: 'No orders found.'},
            layout: {
                topStart: {rowClass: 'row m-3 my-0 justify-content-between', features: [{pageLength: {menu: [10, 25, 50, 100], text: 'Show_MENU_entries'}}]},
                topEnd: {search: {placeholder: 'Search orders...'}}, bottomStart: 'info', bottomEnd: 'paging'
            }
        });
    </script>
@endpush
