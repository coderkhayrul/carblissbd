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


            <!-- Change Password -->
            <div class="card mb-6">
                <h5 class="card-header">Change Password</h5>
                <div class="card-body">
                    <form id="formChangePassword" method="POST" action="{{ route('admin.customers.password.update', $customer) }}">
                        @csrf
                        @method('PATCH')
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="alert alert-warning alert-dismissible" role="alert">
                            <h5 class="alert-heading mb-1">Ensure that these requirements are met</h5>
                            <span>8–72 characters, including uppercase, lowercase and a symbol.</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <div class="row gx-6">
                            <div class="mb-4 col-12 col-sm-6 form-password-toggle form-control-validation">
                                <label class="form-label" for="newPassword">New Password</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" id="newPassword" name="password"
                                        required minlength="8" maxlength="72" autocomplete="new-password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i
                                            class="icon-base bx bx-hide"></i></span>
                                </div>
                            </div>

                            <div class="mb-4 col-12 col-sm-6 form-password-toggle form-control-validation">
                                <label class="form-label" for="confirmPassword">Confirm New Password</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" name="password_confirmation" id="confirmPassword"
                                        required minlength="8" maxlength="72" autocomplete="new-password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i
                                            class="icon-base bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary me-2">Change Password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--/ Change Password -->

            <!-- Recent Devices -->
            <div class="card mb-6">
                <h5 class="card-header">Recent Devices</h5>
                <div class="table-responsive table-border-bottom-0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-truncate">Browser</th>
                                <th class="text-truncate">Device</th>
                                <th class="text-truncate">Location</th>
                                <th class="text-truncate">Recent Activities</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-truncate">
                                    <i class="icon-base bx bxl-windows icon-md text-info me-4"></i>
                                    <span class="text-heading">Chrome on Windows</span>
                                </td>
                                <td class="text-truncate">HP Spectre 360</td>
                                <td class="text-truncate">Switzerland</td>
                                <td class="text-truncate">10, July 2021 20:07</td>
                            </tr>
                            <tr>
                                <td class="text-truncate">
                                    <i class="icon-base bx bx-mobile-alt icon-md text-danger me-4"></i>
                                    <span class="text-heading">Chrome on iPhone</span>
                                </td>
                                <td class="text-truncate">iPhone 12x</td>
                                <td class="text-truncate">Australia</td>
                                <td class="text-truncate">13, July 2021 10:10</td>
                            </tr>
                            <tr>
                                <td class="text-truncate">
                                    <i class="icon-base bx bxl-android icon-md text-success me-4"></i>
                                    <span class="text-heading">Chrome on Android</span>
                                </td>
                                <td class="text-truncate">Oneplus 9 Pro</td>
                                <td class="text-truncate">Dubai</td>
                                <td class="text-truncate">14, July 2021 15:15</td>
                            </tr>
                            <tr>
                                <td class="text-truncate">
                                    <i class="icon-base bx bxl-apple icon-md text-secondary me-4"></i>
                                    <span class="text-heading">Chrome on MacOS</span>
                                </td>
                                <td class="text-truncate">Apple iMac</td>
                                <td class="text-truncate">India</td>
                                <td class="text-truncate">16, July 2021 16:17</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--/ Recent Devices -->
        </div>
        <!--/ User Content -->
    </div>
@endsection

@push('backend_script')
@endpush
