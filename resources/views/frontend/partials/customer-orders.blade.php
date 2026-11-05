<div class="mb-4">
    <h3 class="h4 fw-black text-dark mb-1">My Orders</h3>
    <p class="text-muted">View your orders, payment details and current order status.</p>
</div>
<div class="bg-white rounded-4 shadow-sm border border-light overflow-hidden">
    <form method="GET" action="{{ route('profile') }}" class="p-3 p-md-4 bg-light border-bottom">
        <input type="hidden" name="tab" value="orders">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label small fw-bold" for="customer-order-search">Order Number</label>
                <input class="form-control" type="search" id="customer-order-search" name="orders_q" value="{{ request('orders_q') }}" placeholder="Search by Order ID / Invoice" maxlength="255">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label small fw-bold" for="customer-order-status">Status</label>
                <select class="form-select" id="customer-order-status" name="order_status">
                    <option value="">All Orders</option>
                    @foreach ($orderStatuses as $status)<option value="{{ $status->id }}" @selected(request('order_status') == $status->id)>{{ $status->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-grow-1" type="submit">Search</button>
                <a href="{{ route('profile', ['tab' => 'orders']) }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>
    @forelse ($customerOrders as $order)
        <article class="p-3 p-md-4 border-bottom">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                <div style="min-width: 0;">
                    <h4 class="h6 fw-bold mb-2" style="overflow-wrap: anywhere;">{{ $order->order_no }}</h4>
                    <span class="badge bg-primary">{{ $order->currentStatus?->name ?? 'Pending' }}</span>
                    <span class="small text-muted ms-2">{{ $order->date?->format('d M Y') }}</span>
                </div>
                <strong class="text-primary">&#2547;{{ number_format((float) $order->grand_total, 2) }}</strong>
            </div>
            <div class="d-flex flex-column gap-3 mb-3">
                @foreach ($order->details->take(3) as $item)
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ $item->image ?: asset('backend/assets/extra/preview-image.png') }}" class="rounded border object-fit-contain flex-shrink-0" width="56" height="56" alt="{{ $item->product_name }}" loading="lazy">
                        <div style="min-width: 0;">
                            <p class="small fw-bold mb-1" style="overflow-wrap: anywhere;">{{ $item->product_name ?: 'Product' }}</p>
                            @if ($item->variation_name)<p class="small text-muted mb-1">{{ $item->variation_name }}</p>@endif
                            <span class="small text-muted">Qty: {{ $item->quantity }}</span>
                        </div>
                    </div>
                @endforeach
                @if ($order->details->count() > 3)<span class="small text-muted">+{{ $order->details->count() - 3 }} more items</span>@endif
            </div>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <span class="small text-muted">{{ $order->payment_type === 'COD' ? 'Cash on Delivery' : 'Online Payment' }} · {{ (float) $order->due_amount > 0 ? 'Due: ৳' . number_format((float) $order->due_amount, 2) : 'Paid' }}</span>
                <a class="btn btn-outline-primary btn-sm rounded-pill px-4" href="{{ route('profile.orders.show', $order) }}">View Details</a>
            </div>
        </article>
    @empty
        <div class="text-center p-4 p-md-5" role="status">
            <i class="fa-solid fa-box-open fs-1 text-muted mb-3" aria-hidden="true"></i>
            <h4 class="h6 fw-bold">{{ $totalOrders ? 'No orders match your search.' : 'You have not placed any orders yet.' }}</h4>
            <a href="{{ route('category') }}" class="btn btn-primary rounded-pill mt-3">Browse Products</a>
        </div>
    @endforelse
    @if ($customerOrders->hasPages())
        <nav class="p-3 d-flex justify-content-between align-items-center gap-2" aria-label="My order pages">
            @if ($customerOrders->previousPageUrl())<a class="btn btn-sm btn-outline-primary" href="{{ $customerOrders->previousPageUrl() }}">Previous</a>@else<span></span>@endif
            <span class="small text-muted">{{ $customerOrders->currentPage() }} / {{ $customerOrders->lastPage() }}</span>
            @if ($customerOrders->nextPageUrl())<a class="btn btn-sm btn-outline-primary" href="{{ $customerOrders->nextPageUrl() }}">Next</a>@else<span></span>@endif
        </nav>
    @endif
</div>
