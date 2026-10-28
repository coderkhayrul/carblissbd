@extends('frontend.layouts.master')

@section('front-content')
    <main class="container-md px-3 py-4 py-md-5">
        <section class="bg-white rounded-4 border shadow-sm p-3 p-md-5 mb-4 text-center">
            <i class="fa-solid fa-circle-check text-success display-3 mb-3" aria-hidden="true"></i>
            <h1 class="h3 fw-bold">Your order is confirmed!</h1>
            <p class="text-muted">Thank you, {{ $order->customerInfo?->name }}. We have received your order.</p>
            <p class="fs-5 fw-bold text-primary mb-2">Invoice: {{ $order->order_no }}</p>
            <p class="small mb-0">{{ $order->currentStatus?->name ?? 'Pending' }} · Cash on Delivery · Pay &#2547;{{ number_format((float) $order->grand_total, 2) }} when delivered.</p>
        </section>
        <div class="row g-4">
            <div class="col-12 col-lg-7">
                <section class="bg-white rounded-4 border shadow-sm p-3 p-md-4">
                    <h2 class="h5 fw-bold mb-4">Ordered Products</h2>
                    @foreach ($order->details as $item)
                        <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                            @if ($item->image)<img src="{{ $item->image }}" alt="{{ $item->product_name }}" class="rounded object-fit-contain border" width="64" height="64">@endif
                            <div style="min-width: 0;">
                                <h3 class="h6 fw-bold" style="overflow-wrap: anywhere;">{{ $item->product_name }}</h3>
                                @if ($item->variation_name)<p class="small text-muted mb-1">{{ $item->variation_name }}</p>@endif
                                <p class="small mb-1">SKU: {{ $item->sku }} · Qty: {{ $item->quantity }}</p>
                                <strong class="small text-primary">&#2547;{{ number_format((float) $item->total_amount, 2) }}</strong>
                            </div>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><strong>&#2547;{{ number_format((float) $order->subtotal, 2) }}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Delivery</span><strong>&#2547;{{ number_format((float) $order->shipping_cost, 2) }}</strong></div>
                    <div class="d-flex justify-content-between border-top pt-3"><strong>Total</strong><strong class="text-primary">&#2547;{{ number_format((float) $order->grand_total, 2) }}</strong></div>
                </section>
            </div>
            <div class="col-12 col-lg-5">
                <section class="bg-white rounded-4 border shadow-sm p-3 p-md-4">
                    <h2 class="h5 fw-bold mb-3">Delivery Information</h2>
                    <p class="mb-1 fw-bold">{{ $order->customerInfo?->name }}</p>
                    <p class="mb-1">{{ $order->customerInfo?->phone }}</p>
                    <p style="white-space: pre-line; overflow-wrap: anywhere;">{{ $order->customerInfo?->address }}</p>
                    <p class="small text-muted">{{ $order->delivery_area }}@if ($order->delivery_time) · {{ $order->delivery_time }}@endif</p>
                    @if ($order->customerInfo?->order_note ?? $order->notes)<p class="small" style="overflow-wrap: anywhere;">Notes: {{ $order->customerInfo?->order_note ?? $order->notes }}</p>@endif
                    <a class="btn btn-primary w-100" href="{{ route('category') }}">Continue Shopping</a>
                </section>
            </div>
        </div>
    </main>
    @if ($clearCart)
        <script>window.checkoutCartData = []; try { localStorage.removeItem('carbliss_cart'); } catch (error) {}</script>
    @endif
@endsection
