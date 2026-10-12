@extends('admin.layouts.app')
@push('backend_style')
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
@endpush
@section('admin-content')
    <!-- Shipping Chargea List Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-baseline">
            <h5>Shipping Charge Setting List</h5>
            <div>
                <button class="btn btn-primary create-new" id="form-add-new-record">
                    <span class="d-flex align-items-center gap-2"><i class="icon-base bx bx-plus icon-sm"></i>
                        <span class="d-none d-sm-inline-block">Add New Record</span>
                    </span>
                </button>
            </div>
        </div>
        <div class="card-datatable text-nowrap">
            <table class="shipping-charge-table table table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Area Name</th>
                        <th>Delivery Charge</th>
                        <th>Estimated Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shippingChargeSettings as $row)
                        <tr>
                            <td>{{ $row->area_name }}</td>
                            <td>{{ $row->delivery_charge }}</td>
                            <td>{{ $row->delivery_time }}</td>
                            <td>
                                <span class="badge bg-label-{{ $row->status ? 'success' : 'danger' }}"
                                    text-capitalized="">{{ $row->status ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td>
                                <a href="javascript:;" class="btn btn-icon item-edit"
                                    data-update-url="{{ route('admin.shipping-charge-settings.update', $row->id) }}"
                                    data-area-name="{{ $row->area_name }}"
                                    data-delivery-charge="{{ $row->delivery_charge }}"
                                    data-delivery-time="{{ $row->delivery_time }}"
                                    data-status="{{ $row->status ? '1' : '0' }}"><i
                                        class="icon-base bx bx-edit icon-sm"></i></a>
                                <div class="d-inline-block">
                                    <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end m-0">
                                        {{-- <li><a href="javascript:;" class="dropdown-item">Details</a></li>
                                        <li><a href="javascript:;" class="dropdown-item">Archive</a></li>
                                        <div class="dropdown-divider"></div> --}}
                                        <li>
                                            <form method="POST"
                                                action="{{ route('admin.shipping-charge-settings.destroy', $row->id) }}"
                                                class="shipping-charge-delete-form" data-area-name="{{ $row->area_name }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="dropdown-item text-danger delete-record">Delete</button>
                                            </form>
                                        </li>
                                    </ul>
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
                    <input type="text" class="form-control" id="area_name" placeholder="Enter Area Name" name="area_name"
                        aria-label="area name" />
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
