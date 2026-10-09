@extends('admin.layouts.app')
@section('admin-content')
    <div class="row g-6">
        <!-- Navigation -->
        @include('admin.settings.settings_sidebar')
        <!-- /Navigation -->
        <div class="col-12 col-lg-9">
            <div class="card mb-6 card-action">
                <div class="card-header bg-light mb-4">
                    <h5 class="card-action-title mb-0">Email Settings</h5>
                    {{-- <div class="card-action-element">
                        <img src="{{ asset('backend') }}/default/ssl.png" alt="bkash" class="me-4" height="32">
                    </div> --}}
                </div>
                <div class="card-body">
                    <div class="row gx-6">
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="sender_name">Sender Name</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="sender_name" name="sender_name"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="sender_email">Sender Email</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="sender_email" name="sender_email"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="mail_mailer">Mail Mailer</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="mail_mailer" name="mail_mailer"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="mail_host">Mail Host</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="mail_host" name="mail_host" placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="mail_port">Mail Port</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="mail_port" name="mail_port" placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="mail_username">Mail UserName</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="mail_username" name="mail_username"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="mail_password">Mail Password</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="mail_password" name="mail_password"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="mail_password">Mail Password</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="mail_password" name="mail_password"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="mail_encryption">Mail Encryption</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="mail_encryption" name="mail_encryption"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <span class="icon-base bx bx-check-circle icon-sm me-2"></span>Update Setting
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ SSLCOMMERZ PAYMENT CONFIGURATION -->
        </div>
    </div>
@endsection
