<div class="modal fade" id="saleCustomerModal" tabindex="-1" aria-labelledby="saleCustomerModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"><div class="modal-content">
        <form id="saleCustomerForm" class="d-flex flex-column overflow-hidden" style="max-height:100%" action="{{ route('admin.orders.customers.store') }}" method="POST">
            @csrf
            <div class="modal-header"><h5 class="modal-title" id="saleCustomerModalLabel">New Customer</h5></div>
            <div class="modal-body">
                <div class="alert alert-danger" id="sale-customer-errors" role="alert" hidden></div>
                <label class="form-label" for="new_customer_name">Name</label><input class="form-control mb-3" id="new_customer_name" name="name" maxlength="255" required>
                <label class="form-label" for="new_customer_phone">Mobile Number</label><input class="form-control mb-3" id="new_customer_phone" name="phone" type="tel" maxlength="30" placeholder="01XXXXXXXXX or +8801XXXXXXXXX" required>
                <label class="form-label" for="new_customer_email">Email (Optional)</label><input class="form-control mb-3" id="new_customer_email" name="email" type="email" maxlength="255">
                <label class="form-label" for="new_customer_address">Address</label><textarea class="form-control" id="new_customer_address" name="address" maxlength="255" rows="3" required></textarea>
                <p class="form-text mb-0 mt-3">An existing customer with this mobile number will be selected. New customers receive a customer login record automatically.</p>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary" id="save-sale-customer">Save &amp; Select Customer</button></div>
        </form>
    </div></div>
</div>
