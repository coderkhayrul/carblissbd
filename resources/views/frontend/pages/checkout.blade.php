@extends('frontend.layouts.master')

@section('front-content')
    <main class="container-md px-3 py-4 py-md-5">
        <nav aria-label="breadcrumb"><ol class="breadcrumb small"><li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li><li class="breadcrumb-item active" aria-current="page">Checkout</li></ol></nav>
        <h1 class="h3 fw-bolder mb-4">Checkout</h1>
        @if (session('error'))<div class="alert alert-danger" role="alert">{{ session('error') }}</div>@endif
        @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
        @if ($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @if (count($summary['items']))
            <form action="{{ route('checkout.update') }}" method="POST" id="checkoutForm">
                @csrf
                <div class="row g-4 align-items-start">
                    <div class="col-12 col-lg-7">
                        <section class="bg-white rounded-4 border shadow-sm p-3 p-md-4 mb-4">
                            <h2 class="h5 fw-bold mb-4">Delivery Details</h2>
                            <div class="row g-3">
                                <div class="col-12 col-sm-6"><label class="form-label" for="name">Full Name</label><input class="form-control" id="name" name="name" autocomplete="name" maxlength="255" value="{{ old('name', $details['name'] ?? '') }}"></div>
                                <div class="col-12 col-sm-6"><label class="form-label" for="phone">Mobile Number</label><input class="form-control" type="tel" id="phone" name="phone" autocomplete="tel" maxlength="30" value="{{ old('phone', $details['phone'] ?? '') }}"></div>
                                <div class="col-12"><label class="form-label" for="address">Delivery Address</label><textarea class="form-control" id="address" name="address" autocomplete="street-address" rows="3" maxlength="2000">{{ old('address', $details['address'] ?? '') }}</textarea></div>
                                <div class="col-12">
                                    <label class="form-label" for="shipping_id">Delivery Area</label>
                                    <select class="form-select" id="shipping_id" name="shipping_id" required @disabled($summary['shippingOptions']->isEmpty())>
                                        @forelse ($summary['shippingOptions'] as $option)
                                            <option value="{{ $option->id }}" data-charge="{{ $option->delivery_charge }}" data-time="{{ $option->delivery_time }}" @selected(old('shipping_id', $summary['shipping']?->id) == $option->id)>{{ $option->area_name }} — &#2547;{{ number_format((float) $option->delivery_charge, 2) }}</option>
                                        @empty
                                            <option value="">Delivery is currently unavailable</option>
                                        @endforelse
                                    </select>
                                    <p class="form-text mb-0" id="delivery-time" aria-live="polite">{{ $summary['shipping']?->delivery_time }}</p>
                                </div>
                                <div class="col-12"><label class="form-label" for="notes">Delivery Notes (Optional)</label><textarea class="form-control" id="notes" name="notes" rows="2" maxlength="2000">{{ old('notes', $details['notes'] ?? '') }}</textarea></div>
                            </div>
                        </section>
                        <section class="bg-white rounded-4 border shadow-sm p-3 p-md-4">
                            <h2 class="h5 fw-bold mb-4">Your Products</h2>
                            @foreach ($summary['items'] as $index => $item)
                                <div class="checkout-item d-flex gap-3 border-bottom pb-3 mb-3" data-price="{{ $item['price'] }}">
                                    <a href="{{ $item['url'] }}" class="flex-shrink-0"><img src="{{ $item['img'] }}" width="64" height="64" class="rounded object-fit-contain border" alt="{{ $item['name'] }}"></a>
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        <a href="{{ $item['url'] }}" class="fw-bold small text-dark text-decoration-none" style="overflow-wrap: anywhere;">{{ $item['name'] }}</a>
                                        <div class="text-muted small mb-2">SKU: {{ $item['sku'] }}</div>
                                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                            <div><label class="small me-2" for="qty-{{ $index }}">Qty</label><input class="form-control form-control-sm d-inline-block checkout-qty" style="width: 80px;" type="number" id="qty-{{ $index }}" name="items[{{ $index }}][qty]" min="1" max="99" step="1" required value="{{ $item['qty'] }}"></div>
                                            <span class="checkout-line-total fw-bold text-primary small">&#2547;{{ number_format($item['line_total'], 2) }}</span>
                                        </div>
                                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item['id'] }}">
                                    </div>
                                </div>
                            @endforeach
                            <a href="{{ route('category') }}" class="text-decoration-none small fw-bold">Continue Shopping</a>
                        </section>
                    </div>
                    <div class="col-12 col-lg-5">
                        <section class="bg-white rounded-4 border shadow-sm p-3 p-md-4" aria-labelledby="checkout-summary-title">
                            <h2 class="h5 fw-bold mb-4" id="checkout-summary-title">Order Summary</h2>
                            <div class="d-flex justify-content-between gap-3 mb-3"><span>Subtotal</span><strong id="checkout-subtotal">&#2547;{{ number_format($summary['subtotal'], 2) }}</strong></div>
                            <div class="d-flex justify-content-between gap-3 mb-3"><span>Delivery Charge</span><strong id="checkout-shipping">{{ $summary['shipping'] ? '৳' . number_format($summary['shipping_charge'], 2) : 'Unavailable' }}</strong></div>
                            <div class="d-flex justify-content-between gap-3 border-top pt-3 mb-4" aria-live="polite"><span class="fw-bold">Total</span><strong class="text-primary fs-4" id="checkout-total">&#2547;{{ number_format($summary['total'], 2) }}</strong></div>
                            @if ($summary['shippingOptions']->isEmpty())<div class="alert alert-warning small">No active delivery areas are available. Please contact the store.</div>@endif
                            <button class="btn btn-primary w-100 fw-bold py-3" type="submit" @disabled($summary['shippingOptions']->isEmpty())>Update Checkout</button>
                            <p class="small text-muted mt-3 mb-0">Save your delivery details and quantities. Prices and delivery charges are verified by the store.</p>
                        </section>
                    </div>
                </div>
            </form>
        @else
            <section class="bg-white rounded-4 border shadow-sm p-4 p-md-5 text-center">
                <h2 class="h5 fw-bold">Your checkout is empty</h2>
                <p class="text-muted">Add products to your cart, then choose Checkout securely.</p>
                <a class="btn btn-primary" href="{{ route('category') }}">Browse Products</a>
            </section>
        @endif
    </main>
    <script>
        window.checkoutCartData = @json($cartDisplay);
        (() => {
            const form = document.getElementById('checkoutForm');
            if (!form) return;
            const shipping = document.getElementById('shipping_id');
            const money = cents => '\u09f3' + (cents / 100).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            function updateSummary() {
                let subtotal = 0;
                document.querySelectorAll('.checkout-item').forEach(row => {
                    const qty = Number(row.querySelector('.checkout-qty').value);
                    const line = Math.round(Number(row.dataset.price) * 100) * (Number.isInteger(qty) && qty >= 1 && qty <= 99 ? qty : 0);
                    subtotal += line;
                    row.querySelector('.checkout-line-total').textContent = money(line);
                });
                const selected = shipping.selectedOptions[0];
                const charge = selected?.dataset.charge != null ? Math.round(Number(selected.dataset.charge) * 100) : 0;
                document.getElementById('checkout-subtotal').textContent = money(subtotal);
                document.getElementById('checkout-shipping').textContent = selected?.dataset.charge != null ? money(charge) : 'Unavailable';
                document.getElementById('checkout-total').textContent = money(subtotal + charge);
                document.getElementById('delivery-time').textContent = selected?.dataset.time || '';
            }
            form.addEventListener('input', updateSummary);
            form.addEventListener('change', updateSummary);
            updateSummary();
        })();
    </script>
@endsection
