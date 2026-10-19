@extends('admin.layouts.app')
@push('backend_style')
    <style>
        .users-datatable {
            min-width: 0;
            max-width: 100%;
        }

        .users-datatable .dt-scroll-body {
            -webkit-overflow-scrolling: touch;
        }
    </style>
@endpush
@section('admin-content')
    <div class="row">
        <!-- User Sidebar -->
        @include('admin.customers.sidebar')
        <!--/ User Sidebar -->

        <!-- User Content -->
        <div class="col-xl-8 col-lg-7 order-0 order-md-1">
            <!-- User Pills -->
            @include('admin.customers.nav')
            <!--/ User Pills -->


            <!-- Invoice table -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Invoice List</h5>
                </div>
                <div class="card-datatable table-responsive">
                    <table class="table datatable-invoice">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Issued Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="sorting_1"><a href="#l"><span>#5089</span></a></td>
                                <td>
                                    <span class="d-inline-block" data-bs-toggle="tooltip">
                                        <span
                                            class="badge badge-center d-flex align-items-center justify-content-center rounded-pill bg-label-success w-px-30 h-px-30"><i
                                                class="bx bx-check icon-xs"></i></span>
                                    </span>
                                </td>
                                <td class="dt-type-numeric">$3077</td>
                                <td>05/02/2020</td>
                                <td>
                                    <div class="d-flex align-items-center">

                                        <a href="app-invoice-preview.html" class="btn btn-icon" data-bs-toggle="tooltip"
                                            aria-label="Preview" data-bs-original-title="Preview">
                                            <i class="bx bx-show icon-md"></i>
                                        </a>
                                        <div class="d-inline-block">
                                            <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded icon-md"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end m-0">
                                                <a href="javascript:;" class="dropdown-item">Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="sorting_1"><a href="#l"><span>#5089</span></a></td>
                                <td>
                                    <span class="d-inline-block" data-bs-toggle="tooltip">
                                        <span
                                            class="badge badge-center d-flex align-items-center justify-content-center rounded-pill bg-label-success w-px-30 h-px-30"><i
                                                class="bx bx-check icon-xs"></i></span>
                                    </span>
                                </td>
                                <td class="dt-type-numeric">$3077</td>
                                <td>05/02/2020</td>
                                <td>
                                    <div class="d-flex align-items-center">

                                        <a href="app-invoice-preview.html" class="btn btn-icon" data-bs-toggle="tooltip"
                                            aria-label="Preview" data-bs-original-title="Preview">
                                            <i class="bx bx-show icon-md"></i>
                                        </a>
                                        <div class="d-inline-block">
                                            <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded icon-md"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end m-0">
                                                <a href="javascript:;" class="dropdown-item">Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="sorting_1"><a href="#l"><span>#5089</span></a></td>
                                <td>
                                    <span class="d-inline-block" data-bs-toggle="tooltip">
                                        <span
                                            class="badge badge-center d-flex align-items-center justify-content-center rounded-pill bg-label-success w-px-30 h-px-30"><i
                                                class="bx bx-check icon-xs"></i></span>
                                    </span>
                                </td>
                                <td class="dt-type-numeric">$3077</td>
                                <td>05/02/2020</td>
                                <td>
                                    <div class="d-flex align-items-center">

                                        <a href="app-invoice-preview.html" class="btn btn-icon" data-bs-toggle="tooltip"
                                            aria-label="Preview" data-bs-original-title="Preview">
                                            <i class="bx bx-show icon-md"></i>
                                        </a>
                                        <div class="d-inline-block">
                                            <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded icon-md"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end m-0">
                                                <a href="javascript:;" class="dropdown-item">Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="sorting_1"><a href="#l"><span>#5089</span></a></td>
                                <td>
                                    <span class="d-inline-block" data-bs-toggle="tooltip">
                                        <span
                                            class="badge badge-center d-flex align-items-center justify-content-center rounded-pill bg-label-success w-px-30 h-px-30"><i
                                                class="bx bx-check icon-xs"></i></span>
                                    </span>
                                </td>
                                <td class="dt-type-numeric">$3077</td>
                                <td>05/02/2020</td>
                                <td>
                                    <div class="d-flex align-items-center">

                                        <a href="app-invoice-preview.html" class="btn btn-icon" data-bs-toggle="tooltip"
                                            aria-label="Preview" data-bs-original-title="Preview">
                                            <i class="bx bx-show icon-md"></i>
                                        </a>
                                        <div class="d-inline-block">
                                            <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded icon-md"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end m-0">
                                                <a href="javascript:;" class="dropdown-item">Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="sorting_1"><a href="#l"><span>#5089</span></a></td>
                                <td>
                                    <span class="d-inline-block" data-bs-toggle="tooltip">
                                        <span
                                            class="badge badge-center d-flex align-items-center justify-content-center rounded-pill bg-label-success w-px-30 h-px-30"><i
                                                class="bx bx-check icon-xs"></i></span>
                                    </span>
                                </td>
                                <td class="dt-type-numeric">$3077</td>
                                <td>05/02/2020</td>
                                <td>
                                    <div class="d-flex align-items-center">

                                        <a href="app-invoice-preview.html" class="btn btn-icon" data-bs-toggle="tooltip"
                                            aria-label="Preview" data-bs-original-title="Preview">
                                            <i class="bx bx-show icon-md"></i>
                                        </a>
                                        <div class="d-inline-block">
                                            <a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded icon-md"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end m-0">
                                                <a href="javascript:;" class="dropdown-item">Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="card-footer">
                    <div class="row mx-3 justify-content-between">
                        <div
                            class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto px-4 mt-0">
                            <div class="dt-info" aria-live="polite" id="DataTables_Table_1_info" role="status">Showing
                                1 to 10 of 50 entries</div>
                        </div>
                        <div
                            class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto px-4 mt-0 gap-2">
                            <div class="dt-paging">
                                <nav aria-label="pagination">
                                    <ul class="pagination">
                                        <li class="dt-paging-button page-item disabled"><button class="page-link previous"
                                                role="link" type="button" aria-controls="DataTables_Table_1"
                                                aria-disabled="true" aria-label="Previous" data-dt-idx="previous"
                                                tabindex="-1"><i
                                                    class="icon-base bx bx-chevron-left scaleX-n1-rtl icon-18px"></i></button>
                                        </li>
                                        <li class="dt-paging-button page-item active"><button class="page-link"
                                                role="link" type="button" aria-controls="DataTables_Table_1"
                                                aria-current="page" data-dt-idx="0">1</button></li>
                                        <li class="dt-paging-button page-item"><button class="page-link" role="link"
                                                type="button" aria-controls="DataTables_Table_1"
                                                data-dt-idx="1">2</button></li>
                                        <li class="dt-paging-button page-item"><button class="page-link" role="link"
                                                type="button" aria-controls="DataTables_Table_1"
                                                data-dt-idx="2">3</button></li>
                                        <li class="dt-paging-button page-item"><button class="page-link" role="link"
                                                type="button" aria-controls="DataTables_Table_1"
                                                data-dt-idx="3">4</button></li>
                                        <li class="dt-paging-button page-item"><button class="page-link" role="link"
                                                type="button" aria-controls="DataTables_Table_1"
                                                data-dt-idx="4">5</button></li>
                                        <li class="dt-paging-button page-item"><button class="page-link next"
                                                role="link" type="button" aria-controls="DataTables_Table_1"
                                                aria-label="Next" data-dt-idx="next"><i
                                                    class="icon-base bx bx-chevron-right scaleX-n1-rtl icon-18px"></i></button>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Invoice table -->
        </div>
        <!--/ User Content -->
    </div>
@endsection

@push('backend_script')
@endpush
