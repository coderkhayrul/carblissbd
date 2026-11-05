@php
    $cancelStatusIds = $statuses->filter(fn ($status) => in_array(strtolower(trim($status->name)), ['cancelled', 'canceled', 'cancel'], true))->pluck('id')->values()->all();
    $isCancelling = in_array((int) old('status_id', $order->current_status), $cancelStatusIds);
@endphp
<div class="mb-4 order-cancellation-field" data-cancel-statuses="{{ json_encode($cancelStatusIds) }}" @if (!$isCancelling) hidden @endif>
    <label class="form-label" for="cancel_reason">Cancellation Reason <span class="text-danger">*</span></label>
    <textarea class="form-control" name="cancel_reason" id="cancel_reason" rows="4" maxlength="2000" @required($isCancelling) @disabled(!$isCancelling) placeholder="Why did the customer cancel this order?">{{ old('cancel_reason', $order->cancel_reason) }}</textarea>
    <p class="form-text mb-0">A reason is required to cancel the order and will appear in the order's status history.</p>
</div>
@once
    @push('backend_script')
        <script src="{{ asset('backend/assets/js/order-cancellation.js') }}"></script>
    @endpush
@endonce
