@forelse ($recentOrders as $order)
    <tr>
        <td class="fw-bold text-dark px-3 py-3">{{ $order->order_no }}</td>
        <td class="text-muted small">{{ $order->date?->format('d M Y') }}</td>
        <td class="fw-bold text-dark">&#2547;{{ number_format((float) $order->grand_total, 2) }}</td>
        <td><span class="badge bg-primary order-status-badge">{{ $order->currentStatus?->name ?? 'Pending' }}</span></td>
        <td class="text-end px-3"><a class="btn btn-sm btn-outline-primary rounded-pill" href="{{ route('profile.orders.show', $order) }}">View</a></td>
    </tr>
@empty
    <tr><td colspan="5" class="text-center text-muted py-4">You have not placed any orders yet.</td></tr>
@endforelse
