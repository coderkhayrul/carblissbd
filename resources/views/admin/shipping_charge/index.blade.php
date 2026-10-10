@extends('admin.layouts.app')
@section('admin-content')
    <!-- Category List Table -->
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
                    <tr>
                        <td>Inside Dhaka</td>
                        <td>৳60</td>
                        <td>24-48 Hours</td>
                        <td>Active</td>
                        <td>
                            <a href="javascript:;" class="btn btn-icon item-edit"><i
                                    class="icon-base bx bx-edit icon-sm"></i></a>
                            <div class="d-inline-block">
                                <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end m-0">
                                    <li><a href="javascript:;" class="dropdown-item">Details</a></li>
                                    <li><a href="javascript:;" class="dropdown-item">Archive</a></li>
                                    <div class="dropdown-divider"></div>
                                    <li><a href="javascript:;" class="dropdown-item text-danger delete-record">Delete</a>
                                    </li>
                                </ul>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>Inside Dhaka</td>
                        <td>৳60</td>
                        <td>24-48 Hours</td>
                        <td>Active</td>
                        <td>
                            <a href="javascript:;" class="btn btn-icon item-edit"><i
                                    class="icon-base bx bx-edit icon-sm"></i></a>
                            <div class="d-inline-block">
                                <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end m-0">
                                    <li><a href="javascript:;" class="dropdown-item">Details</a></li>
                                    <li><a href="javascript:;" class="dropdown-item">Archive</a></li>
                                    <div class="dropdown-divider"></div>
                                    <li><a href="javascript:;" class="dropdown-item text-danger delete-record">Delete</a>
                                    </li>
                                </ul>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>Inside Dhaka</td>
                        <td>৳60</td>
                        <td>24-48 Hours</td>
                        <td>Active</td>
                        <td>
                            <a href="javascript:;" class="btn btn-icon item-edit"><i
                                    class="icon-base bx bx-edit icon-sm"></i></a>
                            <div class="d-inline-block">
                                <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end m-0">
                                    <li><a href="javascript:;" class="dropdown-item">Details</a></li>
                                    <li><a href="javascript:;" class="dropdown-item">Archive</a></li>
                                    <div class="dropdown-divider"></div>
                                    <li><a href="javascript:;" class="dropdown-item text-danger delete-record">Delete</a>
                                    </li>
                                </ul>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>Inside Dhaka</td>
                        <td>৳60</td>
                        <td>24-48 Hours</td>
                        <td>Active</td>
                        <td>
                            <a href="javascript:;" class="btn btn-icon item-edit"><i
                                    class="icon-base bx bx-edit icon-sm"></i></a>
                            <div class="d-inline-block">
                                <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end m-0">
                                    <li><a href="javascript:;" class="dropdown-item">Details</a></li>
                                    <li><a href="javascript:;" class="dropdown-item">Archive</a></li>
                                    <div class="dropdown-divider"></div>
                                    <li><a href="javascript:;" class="dropdown-item text-danger delete-record">Delete</a>
                                    </li>
                                </ul>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td>Inside Dhaka</td>
                        <td>৳60</td>
                        <td>24-48 Hours</td>
                        <td>Active</td>
                        <td>
                            <a href="javascript:;" class="btn btn-icon item-edit"><i
                                    class="icon-base bx bx-edit icon-sm"></i></a>
                            <div class="d-inline-block">
                                <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown"><i class="icon-base bx bx-dots-vertical-rounded"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end m-0">
                                    <li><a href="javascript:;" class="dropdown-item">Details</a></li>
                                    <li><a href="javascript:;" class="dropdown-item">Archive</a></li>
                                    <div class="dropdown-divider"></div>
                                    <li><a href="javascript:;" class="dropdown-item text-danger delete-record">Delete</a>
                                    </li>
                                </ul>
                            </div>

                        </td>
                    </tr>
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
            <form class="pt-0" id="eCommerceCategoryListForm">
                <!-- Title -->
                <div class="mb-6 form-control-validation">
                    <label class="form-label" for="area_name">Area Name</label>
                    <input type="text" class="form-control" id="area_name" placeholder="Enter Area Name"
                        name="area_name" aria-label="area name" />
                </div>
                <div class="mb-6 form-control-validation">
                    <label class="form-label" for="delivery_charge">Delivery Charge</label>
                    <input type="text" class="form-control" id="delivery_charge" placeholder="Enter Area Name"
                        name="delivery_charge" aria-label="area name" />
                </div>
                <div class="mb-6 form-control-validation">
                    <label class="form-label" for="delivery_time">Delivery Time</label>
                    <input type="text" class="form-control" id="delivery_time" placeholder="Enter Area Name"
                        name="delivery_time" aria-label="area name" />
                </div>
                <!-- Submit and reset -->
                <div class="mb-6">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Add</button>
                    <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Discard</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('backend_script')
    <script>
        // SHOW SIDEBAR MODEL
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
