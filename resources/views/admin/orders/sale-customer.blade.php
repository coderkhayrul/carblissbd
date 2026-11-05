<section class="card mb-4"><div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2"><h5 class="mb-0">Select Customer</h5><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#saleCustomerModal">New Customer</button></div>
    <div class="card-body">
        <label class="form-label" for="sale-customer-search">Search by Name / Mobile Number</label>
        <input id="sale-customer-search" class="form-control" type="search" maxlength="120" autocomplete="off" placeholder="Type at least 2 characters..." data-search-url="{{ route('admin.orders.customers.search') }}">
        <p id="sale-customer-message" class="form-text" role="status" aria-live="polite">Choose a customer, or create one using New Customer.</p>
        <div id="sale-customer-results"></div>
        <input type="hidden" name="customer_id" id="sale-customer-id" value="{{ $selectedCustomer?->id }}">
        <div class="alert alert-info mt-3 mb-0" id="sale-selected-customer" @if (!$selectedCustomer) hidden @endif>{{ $selectedCustomer ? $selectedCustomer->name . ' - ' . $selectedCustomer->mobile : '' }}</div>
        <p class="form-text mb-0 mt-3">Recipient information can be changed below for this order.</p>
    </div>
</section>
