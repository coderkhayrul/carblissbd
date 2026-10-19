<div class="col-xl-4 col-lg-5 order-1 order-md-0">
    <!-- User Card -->
    <div class="card mb-6">
        <div class="card-body pt-12">
            <div class="user-avatar-section">
                <div class="d-flex align-items-center flex-column">
                    <img class="img-fluid rounded mb-4" src="{{ asset('backend') }}/default/community.png" height="120"
                        width="120" alt="User avatar" />
                    <div class="user-info text-center">
                        <h5>{{ $customer->name }}</h5>
                        <span class="badge bg-label-secondary">Customer</span>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-around flex-wrap my-6 gap-0 gap-md-3 gap-lg-4">
                <div class="d-flex align-items-center me-5 gap-4">
                    <div class="avatar">
                        <div class="avatar-initial bg-label-primary rounded w-px-40 h-px-40">
                            <i class="icon-base bx bx-wallet icon-lg"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="mb-0">৳ 500</h5>
                        <span>Total Purchase</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-4">
                    <div class="avatar">
                        <div class="avatar-initial bg-label-primary rounded w-px-40 h-px-40">
                            <i class="icon-base bx bx-cart check icon-lg"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="mb-0">0</h5>
                        <span>Order Complete</span>
                    </div>
                </div>
            </div>
            <h5 class="pb-4 border-bottom mb-4">Details</h5>
            <div class="info-container">
                <ul class="list-unstyled mb-6">
                    <li class="mb-2">
                        <span class="h6">Username:</span>
                        <span>{{ $customer->username ?? 'N/A' }}</span>
                    </li>
                    <li class="mb-2">
                        <span class="h6">Mobile:</span>
                        <span>{{ $customer->mobile ?? 'N/A' }}</span>
                    </li>
                    <li class="mb-2">
                        <span class="h6">Email:</span>
                        <span>{{ $customer->email ?? 'N/A' }}</span>
                    </li>
                    <li class="mb-2">
                        <span class="h6">Gender:</span>
                        <span>{{ $customer->gender }}</span>
                    </li>
                    <li class="mb-2">
                        <span class="h6">Date Of BirthDay:</span>
                        <span>{{ $customer->date_of_birth ?? 'N/A' }}</span>
                    </li>
                    <li class="mb-2">
                        <span class="h6">Status:</span>
                        <span
                            class="badge bg-label-{{ $customer->status ? 'success' : 'danger' }}">{{ $customer->status ? 'Active' : 'Inactive' }}</span>
                    </li>
                    <li class="mb-2">
                        <span class="h6">Register Form:</span>
                        <span>{{ $customer->register_from }}</span>
                    </li>
                    <li class="mb-2">
                        <span class="h6">Address:</span>
                        <span>{{ $customer->address }}</span>
                    </li>
                    <li class="mb-2">
                        <span class="h6">Shipping Address:</span>
                        <span>{{ $customer->shipping_address }}</span>
                    </li>

                </ul>
                <div class="d-flex justify-content-center">
                    {{-- <a href="javascript:;" class="btn btn-primary me-4" data-bs-target="#editUser"
                        data-bs-toggle="modal">Edit</a> --}}
                    <form action="{{ route('admin.customers.status.update', $customer) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $customer->status ? '0' : '1' }}">
                        <button type="submit"
                            class="btn btn-label-{{ $customer->status ? 'danger' : 'success' }} suspend">
                            {{ $customer->status ? 'Suspend' : 'Activate' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /User Card -->
</div>
