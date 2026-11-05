@extends('admin.layouts.app')
@section('admin-content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h4 class="mb-0">Customer Profile #{{ $customer->id }}</h4>
        <a class="btn btn-label-secondary" href="{{ route('admin.customers.index') }}">Back to Customers</a>
    </div>
    <div class="row g-4">
        @include('admin.customers.sidebar')
        <div class="col-12 col-xl-8 col-lg-7 order-0 order-md-1">
            @include('admin.customers.nav')
            <div class="row g-3 mb-4">
                @foreach (['Total Orders' => number_format($summary['total_orders']), 'Pending Orders' => number_format($summary['pending_orders']), 'Completed Orders' => number_format($summary['completed_orders'])] as $label => $value)
                    <div class="col-12 col-sm-4"><div class="card h-100"><div class="card-body"><span class="text-muted">{{ $label }}</span><h4 class="mt-2 mb-0">{{ $value }}</h4></div></div></div>
                @endforeach
            </div>
            <div class="card mb-4"><h5 class="card-header">Order Payment Summary</h5><div class="card-body row g-3">
                @foreach (['Order Total' => $summary['order_total'], 'Paid' => $summary['paid_total'], 'Due' => $summary['due_total']] as $label => $value)
                    <div class="col-12 col-sm-4"><span class="text-muted d-block">{{ $label }}</span><strong>&#2547;{{ number_format($value, 2) }}</strong></div>
                @endforeach
                <p class="small text-muted mb-0">Totals include all recorded orders, including cancelled or returned orders. Payment adjustments follow the order records.</p>
            </div></div>
            <div class="card"><div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2"><h5 class="mb-0">Order &amp; Invoice History</h5><a class="btn btn-sm btn-label-primary" href="{{ route('admin.orders.index', ['customer_id' => $customer->id]) }}">View in Order List</a></div>
                <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Invoice / Date</th><th>Status / Source</th><th>Recipient</th><th>Total / Paid / Due</th><th>Action</th></tr></thead><tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_no }}</a><small class="d-block text-muted text-nowrap">{{ $order->date?->format('d M Y') ?? $order->created_at?->format('d M Y') }}</small></td>
                            <td><span class="badge bg-label-primary">{{ $order->currentStatus?->name ?? 'Unknown' }}</span><small class="d-block mt-1">{{ $order->order_source }}</small></td>
                            <td><span>{{ $order->customerInfo?->name ?? $customer->name }}</span><small class="d-block text-nowrap">{{ $order->customerInfo?->phone ?? $customer->mobile }}</small></td>
                            <td class="text-nowrap"><strong>&#2547;{{ number_format((float) $order->grand_total, 2) }}</strong><small class="d-block text-success">Paid: {{ number_format((float) $order->paid_amount, 2) }}</small><small class="d-block text-danger">Due: {{ number_format((float) $order->due_amount, 2) }}</small></td>
                            <td><div class="d-flex gap-2"><a class="btn btn-sm btn-label-primary" href="{{ route('admin.orders.show', $order) }}">View</a><a class="btn btn-sm btn-label-secondary" target="_blank" rel="noopener" href="{{ route('admin.orders.invoice', [$order, 'customer']) }}">Invoice</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">This customer has no orders yet.</td></tr>
                    @endforelse
                </tbody></table></div>
                <div class="card-footer"><p class="small text-muted">Showing {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders</p>{{ $orders->links('pagination::bootstrap-5') }}</div>
            </div>
        </div>
    </div>
@endsection
