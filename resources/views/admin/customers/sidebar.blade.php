<div class="col-12 col-xl-4 col-lg-5 order-1 order-md-0">
    <div class="card mb-4"><div class="card-body">
        <div class="d-flex align-items-center flex-column text-center mb-4">
            <img class="rounded mb-3" src="{{ asset($customer->image ?: 'backend/default/community.png') }}" width="120" height="120" style="object-fit:cover" alt="{{ $customer->name }}" onerror="this.onerror=null;this.src='{{ asset('backend/default/community.png') }}'">
            <h5 class="text-break">{{ $customer->name }}</h5><span class="badge bg-label-secondary">Customer #{{ $customer->id }}</span>
        </div>
        <div class="row g-3 text-center mb-4">
            <div class="col-12 col-sm-6"><strong class="d-block">&#2547;{{ number_format($summary['total_purchase'], 2) }}</strong><small>Completed Order Value</small></div>
            <div class="col-12 col-sm-6"><strong class="d-block">{{ number_format($summary['completed_orders']) }}</strong><small>Completed Orders</small></div>
        </div>
        <h5 class="border-bottom pb-3">Customer Details</h5>
        <dl class="row mb-4" style="overflow-wrap:anywhere">
            <dt class="col-5">Mobile</dt><dd class="col-7">{{ $customer->mobile ?: 'N/A' }}</dd>
            <dt class="col-5">Email</dt><dd class="col-7">{{ $customer->email ?: 'N/A' }}</dd>
            <dt class="col-5">Gender</dt><dd class="col-7">{{ $customer->gender ?: 'N/A' }}</dd>
            <dt class="col-5">Date of Birth</dt><dd class="col-7">{{ $customer->date_of_birth ?: 'N/A' }}</dd>
            <dt class="col-5">Status</dt><dd class="col-7"><span class="badge bg-label-{{ $customer->status ? 'success' : 'danger' }}">{{ $customer->status ? 'Active' : 'Inactive' }}</span></dd>
            <dt class="col-5">Verified</dt><dd class="col-7">{{ $customer->is_verified ? 'Yes' : 'No' }}</dd>
            <dt class="col-5">Registered From</dt><dd class="col-7">{{ $customer->register_from ?: 'N/A' }}</dd>
            <dt class="col-5">Joined</dt><dd class="col-7">{{ $customer->created_at?->timezone('Asia/Dhaka')->format('d M Y') ?? 'N/A' }}</dd>
            <dt class="col-5">Address</dt><dd class="col-7" style="white-space:pre-line">{{ $customer->address ?: 'N/A' }}</dd>
            <dt class="col-5">Shipping Address</dt><dd class="col-7" style="white-space:pre-line">{{ $customer->shipping_address ?: 'N/A' }}</dd>
            <dt class="col-5">Country</dt><dd class="col-7">{{ $customer->country ?: 'N/A' }}</dd>
        </dl>
        <form action="{{ route('admin.customers.status.update', $customer) }}" method="POST">
            @csrf @method('PATCH')
            <input type="hidden" name="status" value="{{ $customer->status ? '0' : '1' }}">
            <button type="submit" class="btn w-100 btn-label-{{ $customer->status ? 'danger' : 'success' }} suspend">{{ $customer->status ? 'Suspend' : 'Activate' }}</button>
        </form>
    </div></div>
</div>
