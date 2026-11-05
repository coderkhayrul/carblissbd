<div class="order-action-buttons d-flex align-items-center gap-1">
    <button type="button" class="btn btn-sm btn-link p-1 text-primary order-menu-toggle"
        data-order-menu="invoice-menu-{{ $order->id }}" aria-controls="invoice-menu-{{ $order->id }}"
        aria-expanded="false" aria-haspopup="true" title="Invoice copies"><i class="bx bx-printer fs-5"
            aria-hidden="true"></i><span class="visually-hidden">Invoice copies for
            {{ $order->order_no }}</span></button>
    <div class="dropdown-menu order-action-menu" id="invoice-menu-{{ $order->id }}" hidden>
        @foreach (\App\Services\AdminOrderService::COPIES as $copy => $label)
            <a class="dropdown-item" href="{{ route('admin.orders.invoice', ['order' => $order, 'copy' => $copy]) }}"
                target="_blank" rel="noopener"><i class="bx bx-printer me-2"
                    aria-hidden="true"></i>{{ $label }}</a>
        @endforeach
    </div>
    <button type="button" class="btn btn-sm btn-link p-1 text-danger order-menu-toggle"
        data-order-menu="action-menu-{{ $order->id }}" aria-controls="action-menu-{{ $order->id }}"
        aria-expanded="false" aria-haspopup="true" title="Order actions"><i class="bx bx-dots-horizontal-rounded fs-5"
            aria-hidden="true"></i><span class="visually-hidden">Actions for {{ $order->order_no }}</span></button>
    <div class="dropdown-menu order-action-menu" id="action-menu-{{ $order->id }}" hidden>
        <a class="dropdown-item" href="{{ route('admin.orders.show', $order) }}"><i class="bx bx-show me-2"
                aria-hidden="true"></i>View</a>
        <a class="dropdown-item" href="#"><i class="bx bx-edit me-2" aria-hidden="true"></i>Edit</a>
        <a class="dropdown-item"
            href="{{ route('admin.orders.action', ['order' => $order, 'action' => 'status']) }}"><i
                class="bx bx-check-circle me-2" aria-hidden="true"></i>Status</a>
        <a class="dropdown-item"
            href="{{ route('admin.orders.action', ['order' => $order, 'action' => 'complaint']) }}"><i
                class="bx bx-error me-2" aria-hidden="true"></i>Add Complaint</a>
        <a class="dropdown-item"
            href="{{ route('admin.orders.action', ['order' => $order, 'action' => 'return']) }}"><i
                class="bx bx-undo me-2" aria-hidden="true"></i>Return</a>
        <a class="dropdown-item" href="{{ route('admin.orders.show', $order) }}#order-payments"><i
                class="bx bx-bar-chart me-2" aria-hidden="true"></i>Transaction</a>
        <a class="dropdown-item"
            href="{{ route('admin.orders.action', ['order' => $order, 'action' => 'duplicate']) }}"><i
                class="bx bx-copy me-2" aria-hidden="true"></i>Duplicate Order</a>
    </div>
    <button type="button" class="btn btn-sm btn-link p-1 text-warning order-menu-toggle"
        data-order-menu="courier-menu-{{ $order->id }}" aria-controls="courier-menu-{{ $order->id }}"
        aria-expanded="false" aria-haspopup="true" title="Assign courier"><i class="bx bxs-truck fs-5"
            aria-hidden="true"></i><span class="visually-hidden">Assign courier for
            {{ $order->order_no }}</span></button>
    <div class="dropdown-menu order-action-menu" id="courier-menu-{{ $order->id }}" hidden>
        @foreach (\App\Services\AdminOrderService::COURIERS as $courier)
            <form method="POST" action="{{ route('admin.orders.courier.assign', $order) }}" class="order-courier-form"
                data-courier="{{ $courier }}">
                @csrf @method('PATCH')
                <input type="hidden" name="courier" value="{{ $courier }}">
                <button class="dropdown-item" type="submit"><i class="bx bxs-truck me-2" aria-hidden="true"></i>Assign
                    {{ $courier }}</button>
            </form>
        @endforeach
    </div>
</div>
