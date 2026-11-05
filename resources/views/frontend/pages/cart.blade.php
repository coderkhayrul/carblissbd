@extends('frontend.layouts.master')

@section('front-content')
    <style>#cart-page-content[hidden], #cart-page-empty[hidden] { display: none !important; }</style>
    <main class="container-md px-3 py-4 py-md-5">
        <nav aria-label="breadcrumb"><ol class="breadcrumb small"><li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li><li class="breadcrumb-item active" aria-current="page">Shopping Cart</li></ol></nav>
        <h1 class="h3 fw-bold mb-4">Shopping Cart</h1>
        <div id="cart-page-empty" class="bg-white rounded-4 border shadow-sm p-4 p-md-5 text-center" hidden>
            <i class="fa-solid fa-cart-shopping text-muted fs-1 mb-3" aria-hidden="true"></i>
            <h2 class="h5 fw-bold">Your cart is empty</h2>
            <p class="text-muted">Add products to start shopping.</p>
            <a class="btn btn-primary" href="{{ route('category') }}">Browse Products</a>
        </div>
        <div class="row g-4" id="cart-page-content" hidden>
            <div class="col-12 col-lg-8">
                <section class="bg-white rounded-4 border shadow-sm p-3 p-md-4">
                    <h2 class="h5 fw-bold mb-4">Your Products</h2>
                    <div id="cart-page-items"></div>
                    <a href="{{ route('category') }}" class="small fw-bold text-decoration-none">Continue Shopping</a>
                </section>
            </div>
            <div class="col-12 col-lg-4">
                <section class="bg-white rounded-4 border shadow-sm p-3 p-md-4">
                    <h2 class="h5 fw-bold mb-4">Cart Summary</h2>
                    <div class="d-flex justify-content-between gap-2 mb-3"><span>Subtotal</span><strong id="cart-page-subtotal"></strong></div>
                    <div class="d-flex justify-content-between gap-2 mb-3"><span>Delivery</span><strong id="cart-page-shipping" class="small text-end"></strong></div>
                    <div class="d-flex justify-content-between gap-2 border-top pt-3 mb-4" aria-live="polite"><strong>{{ $shipping ? 'Total' : 'Total before delivery' }}</strong><strong class="text-primary" id="cart-page-total"></strong></div>
                    <button type="button" class="btn btn-primary w-100 fw-bold py-3" id="cart-page-checkout">Checkout securely</button>
                    <p class="small text-muted mt-3 mb-0">Current prices and delivery charges are verified at checkout.</p>
                </section>
            </div>
        </div>
        <noscript><p class="alert alert-info">Enable JavaScript to view your shopping cart.</p></noscript>
    </main>
    <script>
        (() => {
            const list = document.getElementById('cart-page-items');
            const shippingCharge = @json($shipping?->delivery_charge);
            const money = value => '\u09f3' + Number(value).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.addEventListener('cart:updated', event => {
                const items = event.detail.items;
                document.getElementById('cart-page-empty').hidden = items.length > 0;
                document.getElementById('cart-page-content').hidden = items.length === 0;
                list.replaceChildren();
                let subtotal = 0;
                items.forEach(item => {
                    subtotal += item.price * item.qty;
                    const row = document.createElement('article');
                    row.className = 'd-flex gap-3 border-bottom pb-3 mb-3';
                    row.innerHTML = '<img class="rounded border object-fit-contain flex-shrink-0" width="64" height="64"><div class="flex-grow-1" style="min-width:0"><h3 class="h6 fw-bold cart-item-title" style="overflow-wrap:anywhere"></h3><p class="small text-muted cart-item-variation mb-2"></p><div class="d-flex flex-wrap justify-content-between align-items-center gap-3"><div class="d-flex align-items-center gap-2"><button type="button" class="btn btn-sm btn-light border cart-decrease" aria-label="Decrease quantity">−</button><span class="cart-quantity small fw-bold"></span><button type="button" class="btn btn-sm btn-light border cart-increase" aria-label="Increase quantity">+</button></div><strong class="small text-primary cart-item-total"></strong><button type="button" class="btn btn-sm btn-outline-danger cart-remove">Remove</button></div></div>';
                    const image = row.querySelector('img');
                    image.src = typeof item.img === 'string' && /^https?:\/\//i.test(item.img) ? item.img : @json(asset('backend/assets/extra/preview-image.png'));
                    image.alt = item.product_name || item.name;
                    row.querySelector('.cart-item-title').textContent = item.product_name || item.name;
                    const variation = row.querySelector('.cart-item-variation');
                    variation.textContent = item.variation_label ? 'Variation: ' + item.variation_label : '';
                    variation.hidden = !item.variation_label;
                    row.querySelector('.cart-quantity').textContent = item.qty;
                    row.querySelector('.cart-item-total').textContent = money(item.price * item.qty);
                    const decrease = row.querySelector('.cart-decrease');
                    const increase = row.querySelector('.cart-increase');
                    decrease.disabled = item.qty <= 1;
                    increase.disabled = item.qty >= 99;
                    decrease.addEventListener('click', () => changeQty(item.id, -1));
                    increase.addEventListener('click', () => changeQty(item.id, 1));
                    row.querySelector('.cart-remove').addEventListener('click', () => removeFromCart(item.id));
                    list.append(row);
                });
                const delivery = items.length && shippingCharge !== null ? Number(shippingCharge) : 0;
                document.getElementById('cart-page-subtotal').textContent = money(subtotal);
                document.getElementById('cart-page-shipping').textContent = shippingCharge !== null ? money(delivery) : 'Calculated at checkout';
                document.getElementById('cart-page-total').textContent = money(subtotal + delivery);
            });
            document.getElementById('cart-page-checkout').addEventListener('click', () => proceedToCheckout());
        })();
    </script>
@endsection
