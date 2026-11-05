<div class="card mt-4">
    <h5 class="card-header">Return Records</h5>
    <div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Date</th><th>Product</th><th>Reason</th><th>Qty</th><th>Value</th><th>Note</th></tr></thead><tbody>
        @forelse ($returns as $record)<tr><td>{{ $record->created_at }}</td><td>{{ $order->details->firstWhere('id', $record->order_detail_id)?->product_name }}</td><td>{{ $record->reason }}</td><td>{{ $record->quantity }}</td><td>{{ number_format((float) $record->amount, 2) }}</td><td>{{ $record->note }}</td></tr>
        @empty<tr><td colspan="6" class="text-muted text-center">No returns recorded.</td></tr>@endforelse
    </tbody></table></div>
</div>
<div class="card mt-4">
    <h5 class="card-header">Complaints</h5>
    <div class="card-body">
        @forelse ($complaints as $complaint)<div class="border-bottom mb-3 pb-3"><strong>{{ $complaint->subject }}</strong><span class="badge bg-label-warning ms-2">{{ $complaint->status }}</span><p class="mb-1 mt-2" style="white-space:pre-line;overflow-wrap:anywhere">{{ $complaint->message }}</p><small class="text-muted">{{ $complaint->created_at }}</small></div>
        @empty<p class="text-muted mb-0">No complaints recorded.</p>@endforelse
    </div>
</div>
