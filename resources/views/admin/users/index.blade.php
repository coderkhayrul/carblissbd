@extends('admin.layouts.app')
@push('backend_style')
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
@endpush
@section('admin-content')
    <div class="row g-6 mb-6">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Session</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">21,459</h4>
                                <p class="text-success mb-0">(+29%)</p>
                            </div>
                            <small class="mb-0">Total Users</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="icon-base bx bx-group icon-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Paid Users</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">4,567</h4>
                                <p class="text-success mb-0">(+18%)</p>
                            </div>
                            <small class="mb-0">Last week analytics </small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="icon-base bx bx-user-plus icon-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Active Users</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">19,860</h4>
                                <p class="text-danger mb-0">(-14%)</p>
                            </div>
                            <small class="mb-0">Last week analytics</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="icon-base bx bx-user-check icon-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Pending Users</span>
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">237</h4>
                                <p class="text-success mb-0">(+42%)</p>
                            </div>
                            <small class="mb-0">Last week analytics</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="icon-base bx bx-user-voice icon-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- User List Table -->
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">Search Filters</h5>
            <div class="d-flex justify-content-between align-items-center row pt-4 gap-md-0 g-6">
                <div class="col-md-4 user_role">
                    <select id="UserRole" class="form-select text-capitalize">
                        <option value="">Select Role</option>
                        <option value="Admin">Admin</option>
                        <option value="Author">Author</option>
                        <option value="Editor">Editor</option>
                        <option value="Maintainer">Maintainer</option>
                        <option value="Subscriber">Subscriber</option>
                    </select>
                </div>
                <div class="col-md-4 user_plan">
                    <select id="UserPlan" class="form-select text-capitalize">
                        <option value="">Select Plan</option>
                        <option value="Basic">Basic</option>
                        <option value="Company">Company</option>
                        <option value="Enterprise">Enterprise</option>
                        <option value="Team">Team</option>
                    </select>
                </div>
                <div class="col-md-4 user_status">
                    <select id="FilterTransaction" class="form-select text-capitalize">
                        <option value="">Select Status</option>
                        <option value="Pending" class="text-capitalize">Pending</option>
                        <option value="Active" class="text-capitalize">Active</option>
                        <option value="Inactive" class="text-capitalize">Inactive</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-datatable text-nowrap">
            <table class="users-table table table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Mobile</th>
                        <th>User Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $row)
                        <tr>
                            <td>
                                <div class="d-flex justify-content-start align-items-center user-name">
                                    <div class="avatar-wrapper">
                                        <div class="avatar avatar-sm me-4">
                                            <img src="{{ asset('backend/assets/img/avatars/1.png') }}" alt="Avatar"
                                                class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <a href="" class="text-heading text-truncate">
                                            <span class="fw-medium"> Admin User</span>
                                        </a>
                                        <small>admin@gmail.com</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-truncate d-flex align-items-center text-heading"><i
                                        class="icon-base bx bx-user text-success me-2"></i>Maintainer</span>
                            </td>
                            <td><span class="text-heading">CoderKhayrul</span></td>
                            <td><span class="text-heading">CoderKhayrul</span></td>
                            <td>
                                <span class="badge bg-label-{{ $row->status ? 'success' : 'danger' }}"
                                    text-capitalized="">{{ $row->status ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:;" class="btn btn-icon delete-record">
                                        <i class="icon-base bx bx-trash icon-md"></i>
                                    </a>
                                    <a href="app-user-view-account.html" class="btn btn-icon">
                                        <i class="icon-base bx bx-show icon-md"></i>
                                    </a>
                                    <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="icon-base bx bx-dots-vertical-rounded icon-md"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end m-0">
                                        <a href="javascript:;" class="dropdown-item">Edit</a>
                                        <a href="javascript:;" class="dropdown-item">Suspend</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Offcanvas to add Shipping Charge -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasShippingCharge"
        aria-labelledby="offcanvasShippingChargeSetting">
        <!-- Offcanvas Header -->
        <div class="offcanvas-header py-6">
            <h5 id="offcanvasShippingChargeSetting" class="offcanvas-title">Add Shipping Charge</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <!-- Offcanvas Body -->
        <div class="offcanvas-body border-top">
            <form class="pt-0" id="" method="POST"
                action="{{ route('admin.shipping-charge-settings.store') }}">
                @csrf
                <!-- Title -->
                <div class="mb-6 form-control-validation">
                    <label class="form-label" for="area_name">Area Name</label>
                    <input type="text" class="form-control" id="area_name" placeholder="Enter Area Name"
                        name="area_name" aria-label="area name" />
                </div>
                <div class="mb-6 form-control-validation">
                    <label class="form-label" for="delivery_charge">Delivery Charge</label>
                    <input type="number" class="form-control" id="delivery_charge" placeholder="Enter Delivery Charge"
                        name="delivery_charge" aria-label="area name" />
                </div>
                <div class="mb-6 form-control-validation">
                    <label class="form-label" for="delivery_time">Delivery Time</label>
                    <input type="text" class="form-control" id="delivery_time" placeholder="Enter 1-2 Days"
                        name="delivery_time" aria-label="area name" />
                </div>
                <div class="mb-6 form-control-validation">
                    <label class="form-label" for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <!-- Submit and reset -->
                <div class="mb-6">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Add</button>
                    <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Discard</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Offcanvas to Edit Shipping Charge -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasShippingChargeEdit"
        aria-labelledby="offcanvasShippingChargeSettingEdit">
        <!-- Offcanvas Header -->
        <div class="offcanvas-header py-6">
            <h5 id="offcanvasShippingChargeSettingEdit" class="offcanvas-title">Edit Shipping Charge</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <!-- Offcanvas Body -->
        <div class="offcanvas-body border-top">
            <form class="pt-0" id="shipping-charge-edit-form" method="POST" action="">
                @csrf
                @method('PUT')
                <!-- Title -->
                <div class="mb-6 form-control-validation">
                    <label class="form-label" for="edit_area_name">Area Name</label>
                    <input type="text" class="form-control" id="edit_area_name" placeholder="Enter Area Name"
                        name="area_name" aria-label="area name" />
                </div>
                <div class="mb-6 form-control-validation">
                    <label class="form-label" for="edit_delivery_charge">Delivery Charge</label>
                    <input type="number" class="form-control" id="edit_delivery_charge" min="0" step="0.01"
                        placeholder="Enter Delivery Charge" name="delivery_charge" aria-label="area name" />
                </div>
                <div class="mb-6 form-control-validation">
                    <label class="form-label" for="edit_delivery_time">Delivery Time</label>
                    <input type="text" class="form-control" id="edit_delivery_time" placeholder="Enter 1-2 Days"
                        name="delivery_time" aria-label="area name" />
                </div>
                <div class="mb-6 form-control-validation">
                    <label class="form-label" for="edit_status">Status</label>
                    <select name="status" id="edit_status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <!-- Submit and reset -->
                <div class="mb-6">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">Update</button>
                    <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Discard</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('backend_script')
    <script src="{{ asset('backend/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script>
        document.querySelector('.shipping-charge-table').addEventListener('submit', function(event) {
            const form = event.target.closest('.shipping-charge-delete-form');
            if (!form) return;

            event.preventDefault();
            Swal.fire({
                title: 'Delete shipping charge?',
                text: 'Are you sure you want to delete the shipping charge for "' + form.dataset.areaName +
                    '"?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                focusCancel: true,
                customClass: {
                    confirmButton: 'btn btn-danger me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    HTMLFormElement.prototype.submit.call(form);
                }
            });
        });

        // SHOW CREATE SIDEBAR MODEL
        setTimeout(() => {
            const newRecord = document.querySelector('.create-new'),
                offCanvasElement = document.querySelector('#offcanvasShippingCharge');

            // To open offCanvas, to add new record
            if (newRecord) {
                newRecord.addEventListener('click', function() {
                    offCanvasEl = new bootstrap.Offcanvas(offCanvasElement);
                    offCanvasEl.show();
                });
            }
        }, 200);

        // Delegate clicks so editing also works after table pagination and filtering.
        document.querySelector('.shipping-charge-table').addEventListener('click', function(event) {
            const editButton = event.target.closest('.item-edit');
            if (!editButton) return;

            event.preventDefault();
            const form = document.querySelector('#shipping-charge-edit-form');
            form.action = editButton.dataset.updateUrl;
            form.elements.area_name.value = editButton.dataset.areaName;
            form.elements.delivery_charge.value = editButton.dataset.deliveryCharge;
            form.elements.delivery_time.value = editButton.dataset.deliveryTime;
            form.elements.status.value = editButton.dataset.status;

            bootstrap.Offcanvas.getOrCreateInstance(document.querySelector('#offcanvasShippingChargeEdit')).show();
        });


        // SHIPPING CHARGE TABLE
        new DataTable('.shipping-charge-table', {
            layout: {
                topStart: {
                    rowClass: 'row m-3 my-0 justify-content-between',
                    features: [{
                        pageLength: {
                            menu: [10, 25, 50, 100],
                            text: 'Show_MENU_entries'
                        }
                    }]
                },
                topEnd: {
                    search: {
                        placeholder: ''
                    }
                },
                bottomStart: {
                    rowClass: 'row mx-3 justify-content-between',
                    features: ['info']
                }
            },
        });
    </script>
@endpush
