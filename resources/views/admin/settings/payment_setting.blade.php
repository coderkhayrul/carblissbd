@extends('admin.layouts.app')
@section('admin-content')
    <div class="row g-6">
        <!-- Navigation -->
        @include('admin.settings.settings_sidebar')
        <!-- /Navigation -->
        <div class="col-12 col-lg-9">
            <!-- BKASH PAYMENT CONFIGURATION -->
            <div class="card mb-6 card-action">
                <div class="card-header bg-light mb-4">
                    <h5 class="card-action-title mb-0">Bkash Payment Configuration</h5>
                    <div class="card-action-element">
                        <img src="{{ asset('backend') }}/default/bkash.png" alt="bkash" class="me-4" height="32">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row gx-6">
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="bkash_active">Bkash Status</label>
                            <div class="input-group">
                                <select class="form-select" id="bkash_active" name="bkash_active">
                                    <option value="0">Inactive</option>
                                    <option value="1">Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="bkash_url">Bkash Base Url</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="bkash_url" name="bkash_url" placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="bkash_app_key">Bkash App Key</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="bkash_app_key" name="bkash_app_key"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="bkash_app_secret">Bkash App Secret</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="bkash_app_secret" name="bkash_app_secret"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="bkash_username">Bkash Username</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="bkash_username" name="bkash_username"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="bkash_password">Bkash Password</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="bkash_password" name="bkash_password"
                                    placeholder="" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ BKASH PAYMENT CONFIGURATION -->

            <!-- SSLCOMMERZ PAYMENT CONFIGURATION -->
            <div class="card mb-6 card-action">
                <div class="card-header bg-light mb-4">
                    <h5 class="card-action-title mb-0">SSLCOMMERZ Payment Configuration</h5>
                    <div class="card-action-element">
                        <img src="{{ asset('backend') }}/default/ssl.png" alt="bkash" class="me-4" height="32">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row gx-6">
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="ssl_active">SSLCOMMERZ Status</label>
                            <div class="input-group">
                                <select class="form-select" id="ssl_active" name="ssl_active">
                                    <option value="0">Inactive</option>
                                    <option value="1">Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="ssl_store_id">STORE ID</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="ssl_store_id" name="ssl_store_id"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="ssl_store_password">STORE PASSWORD</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="ssl_store_password"
                                    name="ssl_store_password" placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="ssl_store_url">API DOMAIN URL</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="ssl_store_url" name="ssl_store_url"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="ssl_store_callback_url">CALLBACK URL</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="ssl_store_callback_url"
                                    name="ssl_store_callback_url" placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="ssl_store_failure_url">FAILURE URL</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="ssl_store_failure_url"
                                    name="ssl_store_failure_url" placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="ssl_is_localhost">IS LOCALHOST</label>
                            <div class="input-group">
                                <select class="form-select" id="ssl_is_localhost" name="ssl_is_localhost">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ SSLCOMMERZ PAYMENT CONFIGURATION -->

            <!-- UPDATE BUTTON -->
            <div class="card mb-6 card-action text-r">
                <button type="submit" class="btn btn-primary">
                    <span class="icon-base bx bx-check-circle icon-sm me-2"></span>Update Setting
                </button>
            </div>
        </div>
    </div>
@endsection
