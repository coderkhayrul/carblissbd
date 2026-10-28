@extends('frontend.layouts.master')

@section('front-content')
    <main class="container-md px-3 py-4 py-md-5">
        <nav aria-label="breadcrumb"><ol class="breadcrumb small"><li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li><li class="breadcrumb-item active" aria-current="page">Checkout</li></ol></nav>
        <h1 class="h3 fw-bolder mb-4">Checkout</h1>
        @if (session('error'))<div class="alert alert-danger" role="alert">{{ session('error') }}</div>@endif
        @if (session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
        @if ($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @if (count($summary['items']))
            @guest
                <div class="bg-white border rounded-3 p-3 mb-4 small">
                    <strong>Guest checkout is available.</strong> Fill in your delivery details to place an order without signing in.
                    Already have an account? <a href="{{ route('login') }}">Sign in</a>, or use your registered mobile number below.
                </div>
            @else
                @if (auth()->user()->role === 'customer')
                    <div class="bg-white border rounded-3 p-3 mb-4 small">
                        Ordering with your account: <strong>{{ auth()->user()->name }}</strong>.
                        You can enter a different recipient or delivery address below.
                    </div>
                @endif
            @endguest
            <form action="{{ route('checkout.place-order') }}" method="POST" id="checkoutForm">
                @csrf
                <input type="hidden" name="checkout_token" value="{{ $checkoutToken }}">
                <div class="row g-4 align-items-start">
                    <div class="col-12 col-lg-7">
                        <section class="bg-white rounded-4 border shadow-sm p-3 p-md-4 mb-4">
                            <h2 class="h5 fw-bold mb-4">Delivery Details</h2>
                            <div class="row g-3">
                                <div class="col-12 col-sm-6"><label class="form-label" for="name">Full Name</label><input class="form-control" id="name" name="name" autocomplete="name" maxlength="255" required value="{{ old('name', $details['name'] ?? auth()->user()?->name ?? '') }}"></div>
                                <div class="col-12 col-sm-6"><label class="form-label" for="phone">Mobile Number</label><input class="form-control" type="tel" id="phone" name="phone" autocomplete="tel" maxlength="30" placeholder="01712345678 or +8801712345678" required aria-describedby="phone-help" value="{{ old('phone', $details['phone'] ?? auth()->user()?->mobile ?? '') }}"><div class="form-text" id="phone-help">Enter 11 digits. +88 will be added automatically.</div></div>
                                <div class="col-12"><label class="form-label" for="email">Email (Optional)</label><input class="form-control" type="email" id="email" name="email" autocomplete="email" maxlength="255" value="{{ old('email', $details['email'] ?? auth()->user()?->email ?? '') }}"></div>
                                @guest
                                    <div class="col-12">
                                        <details class="border rounded-3 p-3">
                                            <summary class="small fw-bold">Set a password for a new account (optional)</summary>
                                            <p class="small text-muted mt-2">New customers can set a password to sign in later. An existing account's password will stay unchanged.</p>
                                            <div class="row g-3">
                                                <div class="col-12 col-sm-6"><label class="form-label" for="password">Password</label><input class="form-control" type="password" name="password" id="password" autocomplete="new-password" minlength="8" maxlength="72"></div>
                                                <div class="col-12 col-sm-6"><label class="form-label" for="password_confirmation">Confirm Password</label><input class="form-control" type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" minlength="8" maxlength="72"></div>
                                            </div>
                                        </details>
                                    </div>
                                @endguest
                                <div class="col-12"><label class="form-label" for="address">Delivery Address</label><textarea class="form-control" id="address" name="address" autocomplete="street-address" rows="3" maxlength="2000" required>{{ old('address', $details['address'] ?? '') }}</textarea></div>
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
                                            <div><label class="small me-2" for="qty-{{ $index }}">Qty</label><input class="form-control form-control-sm d-inline-block checkout-qty" style="width: 80px;" type="number" id="qty-{{ $index }}" name="items[{{ $index }}][qty]" min="1" max="99" step="1" required value="{{ old('items.' . $index . '.qty', $item['qty']) }}"></div>
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
                            <div class="border rounded-3 p-3 mb-3"><strong class="small">Cash on Delivery</strong><p class="small text-muted mb-0">Pay the total amount when your order arrives.</p></div>
                            <button class="btn btn-primary w-100 fw-bold py-3 mb-2" id="placeOrderButton" type="submit" @disabled($summary['shippingOptions']->isEmpty())>Place Order</button>
                            <button class="btn btn-outline-primary w-100" type="submit" formaction="{{ route('checkout.update') }}" formnovalidate @disabled($summary['shippingOptions']->isEmpty())>Update Checkout</button>
                            <p class="small text-muted mt-3 mb-0">Your order is confirmed after you click Place Order. Payment will be collected on delivery.</p>
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
            const phoneInput = document.getElementById('phone');
            function normalizeCheckoutPhone() {
                const banglaDigits = '০১২৩৪৫৬৭৮৯';
                let value = phoneInput.value.replace(/[০-৯]/g, digit => banglaDigits.indexOf(digit)).replace(/[\s().-]/g, '');
                if (/^01[0-9]{9}$/.test(value)) value = '+88' + value;
                else if (/^8801[0-9]{9}$/.test(value)) value = '+' + value;
                if (/^\+8801[0-9]{9}$/.test(value)) phoneInput.value = value;
                return value;
            }
            phoneInput.addEventListener('input', () => {
                phoneInput.setCustomValidity('');
                normalizeCheckoutPhone();
            });
            phoneInput.addEventListener('blur', () => {
                const value = normalizeCheckoutPhone();
                phoneInput.setCustomValidity(value && !/^\+8801[0-9]{9}$/.test(value) ? 'Enter an 11-digit Bangladesh mobile number, with or without +88.' : '');
            });
            normalizeCheckoutPhone();
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
            form.addEventListener('submit', event => {
                if (event.submitter?.id !== 'placeOrderButton') return;
                const button = document.getElementById('placeOrderButton');
                button.disabled = true;
                button.textContent = 'Placing Order...';
            });
            updateSummary();
        })();
    </script>
@endsection
