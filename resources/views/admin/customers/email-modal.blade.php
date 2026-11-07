<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg modal-fullscreen-sm-down"><div class="modal-content">
        <form id="sendCustomerEmailForm" method="POST" class="d-flex flex-column overflow-hidden">
            @csrf
            <input type="hidden" name="request_token" id="customerEmailToken">
            <div class="modal-header"><h5 class="modal-title" id="sendEmailModalLabel">Send Email</h5></div>
            <div class="modal-body">
                <p class="small text-muted">Uses your <a href="{{ route('admin.email.settings') }}" target="_blank" rel="noopener">Email Settings</a>. Configure SMTP and enable email sending before submitting.</p>
                <div class="row g-3 mb-3"><div class="col-12 col-sm-6"><label class="form-label" for="emailCustomerName">Customer</label><input class="form-control" id="emailCustomerName" readonly></div><div class="col-12 col-sm-6"><label class="form-label" for="emailCustomerAddress">Recipient Email</label><input class="form-control" id="emailCustomerAddress" readonly></div></div>
                <label class="form-label" for="customerEmailSubject">Subject</label><input class="form-control mb-3" id="customerEmailSubject" name="subject" maxlength="255" required>
                <label class="form-label" for="customerEmailMessage">Message</label><textarea class="form-control" id="customerEmailMessage" name="message" rows="9" maxlength="15000" required placeholder="Write your message..." style="min-height:180px;resize:vertical" aria-describedby="customerEmailCount"></textarea>
                <p class="form-text text-end" id="customerEmailCount">0 / 15000 characters</p>
                <div class="alert alert-danger d-none mb-0" id="customerEmailResult" role="status" aria-live="polite"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-label-secondary" id="cancelCustomerEmail" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary" id="sendCustomerEmailButton">Send Email</button></div>
        </form>
    </div></div>
</div>
@push('backend_script')
    <script src="{{ asset('backend/assets/js/customer-email.js') }}"></script>
@endpush
