@extends('admin.layouts.app')
@push('backend_style')
<style>
    .sale-list-heading { font-size: 1.3rem; }
    .sale-filters .row > * { min-width: 0; }
    .sale-filters .form-label { font-size: .75rem; margin-bottom: .35rem; }
    .sale-filters .select2-container { width: 100% !important; }
    .sale-list-table { font-size: .8rem; }
    .sale-list-table thead th { background: #60899d !important; color: #fff !important; text-align: center; text-transform: none; white-space: nowrap; font-size: .78rem; letter-spacing: 0; padding: 12px 10px; }
    .sale-list-table tbody td { vertical-align: middle; padding: 16px 12px; }
    .sale-list-table tbody tr:nth-child(odd) td { background-color: #f8f9fb; }
    .sale-list-table .invoice-cell, .sale-list-table .customer-cell, .sale-list-table .date-cell, .sale-list-table .payment-cell { text-align: center; }
    .sale-list-table .customer-cell { min-width: 190px; max-width: 260px; white-space: normal; overflow-wrap: anywhere; }
    .sale-list-table .invoice-cell { min-width: 155px; }
    .sale-list-table .date-cell { min-width: 140px; }
    .sale-list-table .amount-cell { min-width: 200px; font-family: ui-monospace, SFMono-Regular, Consolas, monospace; }
    .sale-amount-row { display: flex; justify-content: space-between; gap: 24px; border-bottom: 1px dashed #e4e7eb; padding: 3px 0; }
    .sale-amount-row:last-child { border-bottom: 0; }
    .sale-source { background: #9851df; color: white; }
    .sale-courier { min-width: 200px; }
    .sale-list-card .dt-layout-row:first-child { background: #edf3f8; padding: 12px 16px; margin: 0; }
    .sale-list-card .dt-layout-row:last-child { padding: 12px 16px; }
    .sale-list-card .dt-length label { font-size: .8rem; }
    @media (max-width: 575.98px) { .sale-filters .btn { flex: 1; } .sale-list-heading { font-size: 1.1rem; } }
</style>
@endpush
@section('admin-content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h4 class="sale-list-heading mb-0"><i class="bx bx-list-ul me-1" aria-hidden="true"></i>Sale / Order List</h4>
        <a class="btn btn-sm btn-primary" href="{{ route('admin.orders.export', request()->only(['customer_id', 'order_source', 'payment_type', 'courier', 'status_id', 'date_from', 'date_to', 'order_no'])) }}">
            <i class="bx bx-download me-1" aria-hidden="true"></i>Excel CSV
        </a>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <form class="card sale-filters mb-4" method="GET" action="{{ route('admin.orders.index') }}">
        <div class="card-body p-3">
            <div class="row g-3">
                <div class="col-12 col-md-6 col-xl-4">
                    <label class="form-label" for="filter-customer">Customer</label>
                    <select id="filter-customer" name="customer_id" class="form-select order-customer-select" data-placeholder="All Customer">
                        <option value="">All Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" @selected(request('customer_id') == $customer->id)>{{ $customer->name }}{{ $customer->mobile ? ' — ' . $customer->mobile : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3 col-xl-2">
                    <label class="form-label" for="filter-source">Order Source</label>
                    <select class="form-select" id="filter-source" name="order_source">
                        <option value="">All Source</option>
                        @foreach (['Website', 'GSale', 'App'] as $source)<option value="{{ $source }}" @selected(request('order_source') === $source)>{{ $source }}</option>@endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3 col-xl-2">
                    <label class="form-label" for="filter-payment">Payment Type</label>
                    <select class="form-select" id="filter-payment" name="payment_type">
                        <option value="">All Payment Types</option>
                        @foreach (['COD', 'Online'] as $payment)<option value="{{ $payment }}" @selected(request('payment_type') === $payment)>{{ $payment }}</option>@endforeach
                    </select>
                </div>
                <div class="col-6 col-md-6 col-xl-2">
                    <label class="form-label" for="filter-courier">Courier</label>
                    <select class="form-select" id="filter-courier" name="courier">
                        <option value="">All Courier</option>
                        @foreach (['unassigned' => 'Not Assigned', 'Pathao' => 'Pathao', 'Steadfast' => 'Steadfast', 'RedX' => 'RedX'] as $value => $label)<option value="{{ $value }}" @selected(request('courier') === $value)>{{ $label }}</option>@endforeach
                    </select>
                </div>
                <div class="col-6 col-md-6 col-xl-2">
                    <label class="form-label" for="filter-status">Current Status</label>
                    <select class="form-select" id="filter-status" name="status_id">
                        <option value="">All Status</option>
                        @foreach ($statuses as $status)<option value="{{ $status->id }}" @selected(request('status_id') == $status->id)>{{ $status->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label" for="filter-from">From Date</label>
                    <input class="form-control" type="date" name="date_from" id="filter-from" value="{{ request('date_from') }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label" for="filter-to">To Date</label>
                    <input class="form-control" type="date" name="date_to" id="filter-to" value="{{ request('date_to') }}" @if(request('date_from')) min="{{ request('date_from') }}" @endif>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="filter-invoice">Order / Invoice Number</label>
                    <input class="form-control" name="order_no" id="filter-invoice" placeholder="Order No" maxlength="255" value="{{ request('order_no') }}">
                </div>
                <div class="col-12 col-md-2 d-flex align-items-end gap-2">
                    <button class="btn btn-primary" type="submit"><i class="bx bx-search me-1" aria-hidden="true"></i>Search</button>
                    <a class="btn btn-label-secondary" href="{{ route('admin.orders.index') }}">Reset</a>
                </div>
            </div>
        </div>
    </form>
    <div class="card sale-list-card">
        <div class="px-3 pt-3 small text-muted">{{ number_format($orders->count()) }} orders match your filters</div>
        <div class="table-responsive">
            <table class="data-table sale-list-table table table-bordered mb-0">
                <thead><tr><th>SL</th><th>Order &amp; Invoice</th><th>Customer</th><th>Date &amp; Time</th><th>Amount Details</th><th>Payment &amp; Status</th><th>Courier / Source / Action</th></tr></thead>
                <tbody>
                    @foreach ($orders as $order)
                        @php
                            $paid = (float) $order->paid_amount;
                            $due = (float) $order->due_amount;
                            $paymentLabel = $due <= 0 ? 'Paid' : ($paid > 0 ? 'Partially Paid' : 'Pending');
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="invoice-cell">
                                <span class="badge bg-label-success mb-2">{{ $order->currentStatus?->name ?? 'Unknown' }}</span>
                                <a class="d-block fw-bold text-body mb-2" href="{{ route('admin.orders.show', $order) }}">{{ $order->order_no }}</a>
                                <span class="badge sale-source">{{ $order->order_source }}</span>
                            </td>
                            <td class="customer-cell">
                                <a class="fw-bold text-body d-block mb-1" href="{{ route('admin.customers.show', $order->customer_id) }}">{{ $order->customerInfo?->name ?? $order->customer?->name }}</a>
                                <span class="small text-muted">{{ $order->customerInfo?->phone ?? $order->customer?->mobile }}</span>
                            </td>
                            <td class="date-cell" data-order="{{ $order->date?->format('Y-m-d') }} {{ $order->created_at?->timezone('Asia/Dhaka')->format('H:i:s') }}">
                                <div>{{ $order->date?->format('d M, Y') }}</div>
                                <small class="text-muted">{{ $order->created_at?->timezone('Asia/Dhaka')->format('h:i A') }}</small>
                            </td>
                            <td class="amount-cell" data-order="{{ $order->grand_total }}">
                                <div class="sale-amount-row"><span>Qty:</span><strong class="text-success">{{ number_format((float) $order->total_quantity, 2) }}</strong></div>
                                <div class="sale-amount-row"><span>Paid:</span><strong class="text-success">{{ number_format($paid, 2) }} Tk</strong></div>
                                <div class="sale-amount-row"><span>Due:</span><strong class="text-danger">{{ number_format($due, 2) }} Tk</strong></div>
                                <div class="sale-amount-row fw-bold"><span>Total:</span><span class="text-primary">{{ number_format((float) $order->grand_total, 2) }} Tk</span></div>
                            </td>
                            <td class="payment-cell">
                                <span class="badge bg-info mb-2">{{ $order->payment_type }}</span><br>
                                <span class="badge {{ $due <= 0 ? 'bg-label-success' : 'bg-label-secondary' }}">{{ $paymentLabel }}</span>
                            </td>
                            <td class="sale-courier">
                                <div class="d-flex justify-content-between align-items-center gap-3">
                                    <div>
                                        <span class="badge {{ $order->is_courier_assigned ? 'bg-label-primary' : 'bg-warning text-white' }}">{{ $order->is_courier_assigned ? trim($order->courier_name ?? 'Assigned') : 'Not Assigned' }}</span>
                                        @if ($order->courier_status)<div class="small text-muted mt-1">{{ $order->courier_status }}</div>@endif
                                        <div class="small text-muted mt-2">{{ $order->order_source }}</div>
                                    </div>
                                    <a class="btn btn-sm btn-label-primary" href="{{ route('admin.orders.show', $order) }}"><i class="bx bx-show me-1" aria-hidden="true"></i>View</a>
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
        $('.order-customer-select').select2({width: '100%', placeholder: 'All Customer', allowClear: true});
        const dateFrom = document.getElementById('filter-from');
        const dateTo = document.getElementById('filter-to');
        dateFrom.addEventListener('change', () => { dateTo.min = dateFrom.value; });
        new DataTable('.data-table', {
            scrollX: true,
            pageLength: 50,
            order: [],
            columnDefs: [{targets: [5, 6], orderable: false}],
            language: {emptyTable: 'No orders match the selected filters.'},
            layout: {
                topStart: {features: [{pageLength: {menu: [10, 25, 50, 100], text: 'Display _MENU_ records'}}]},
                topEnd: {search: {placeholder: 'Search these results...'}},
                bottomStart: 'info',
                bottomEnd: 'paging'
            }
        });
    </script>
@endpush
