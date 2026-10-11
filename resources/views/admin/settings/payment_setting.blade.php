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
                    <form action="{{ route('admin.payment.settings.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="bkash">
                        <div class="row gx-6">
                            <div class="mb-4 col-12 col-sm-6">
                                <label class="form-label" for="bkash_active">Bkash Status</label>
                                <div class="input-group">
                                    <select class="form-select" id="bkash_active" name="bkash_active">
                                        <option value="1" {{ $paymentSetting->bkash_active === 1 ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="0" {{ $paymentSetting->bkash_active === 0 ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4 col-12 col-sm-6">
                                <label class="form-label" for="bkash_url">Bkash Base Url</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" id="bkash_url" name="bkash_url"
                                        placeholder="" value="{{ $paymentSetting->bkash_url }}" />
                                </div>
                            </div>
                            <div class="mb-4 col-12 col-sm-6">
                                <label class="form-label" for="bkash_app_key">Bkash App Key</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" id="bkash_app_key" name="bkash_app_key"
                                        placeholder="" value="{{ $paymentSetting->bkash_app_key }}" />
                                </div>
                            </div>
                            <div class="mb-4 col-12 col-sm-6">
                                <label class="form-label" for="bkash_app_secret">Bkash App Secret</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" id="bkash_app_secret" name="bkash_app_secret"
                                        placeholder="" value="{{ $paymentSetting->bkash_app_secret }}" />
                                </div>
                            </div>
                            <div class="mb-4 col-12 col-sm-6">
                                <label class="form-label" for="bkash_username">Bkash Username</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" id="bkash_username" name="bkash_username"
                                        placeholder="" value="{{ $paymentSetting->bkash_username }}" />
                                </div>
                            </div>
                            <div class="mb-4 col-12 col-sm-6">
                                <label class="form-label" for="bkash_password">Bkash Password</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" id="bkash_password" name="bkash_password"
                                        placeholder="" value="{{ $paymentSetting->bkash_password }}" />
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <span class="icon-base bx bx-check-circle icon-sm me-2"></span>Update Setting
                                </button>
                            </div>
                        </div>
                    </form>
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
                    <form action="{{ route('admin.payment.settings.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="ssl">
                        <div class="row gx-6">
                            <div class="mb-4 col-12 col-sm-6">
                                <label class="form-label" for="ssl_active">SSLCOMMERZ Status</label>
                                <div class="input-group">
                                    <select class="form-select" id="ssl_active" name="ssl_active">
                                        <option value="0" {{ $paymentSetting->ssl_active === 0 ? 'selected' : '' }}>
                                            Inactive</option>
                                        <option value="1" {{ $paymentSetting->ssl_active === 1 ? 'selected' : '' }}>
                                            Active
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4 col-12 col-sm-6">
                                <label class="form-label" for="ssl_store_id">STORE ID</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" id="ssl_store_id" name="ssl_store_id"
                                        placeholder="" value="{{ $paymentSetting->ssl_store_id }}" />
                                </div>
                            </div>
                            <div class="mb-4 col-12 col-sm-6">
                                <label class="form-label" for="ssl_store_password">STORE PASSWORD</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" id="ssl_store_password"
                                        name="ssl_store_password" placeholder=""
                                        value="{{ $paymentSetting->ssl_store_password }}" />
                                </div>
                            </div>
                            <div class="mb-4 col-12 col-sm-6">
                                <label class="form-label" for="ssl_store_url">API DOMAIN URL</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" id="ssl_store_url" name="ssl_store_url"
                                        placeholder="" value="{{ $paymentSetting->ssl_store_url }}" />
                                </div>
                            </div>
                            <div class="mb-4 col-12 col-sm-6">
                                <label class="form-label" for="ssl_store_callback_url">CALLBACK URL</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" id="ssl_store_callback_url"
                                        name="ssl_store_callback_url" placeholder=""
                                        value="{{ $paymentSetting->ssl_store_callback_url }}" />
                                </div>
                            </div>
                            <div class="mb-4 col-12 col-sm-6">
                                <label class="form-label" for="ssl_store_failure_url">FAILURE URL</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" id="ssl_store_failure_url"
                                        name="ssl_store_failure_url" placeholder=""
                                        value="{{ $paymentSetting->ssl_store_failure_url }}" />
                                </div>
                            </div>
                            <div class="mb-4 col-12 col-sm-6">
                                <label class="form-label" for="ssl_is_localhost">IS LOCALHOST</label>
                                <div class="input-group">
                                    <select class="form-select" id="ssl_is_localhost" name="ssl_is_localhost">
                                        <option value="0" {{ $paymentSetting->ssl_active === 0 ? 'selected' : '' }}>
                                            No
                                        </option>
                                        <option value="1" {{ $paymentSetting->ssl_active === 1 ? 'selected' : '' }}>
                                            Yes
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <span class="icon-base bx bx-check-circle icon-sm me-2"></span>Update Setting
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--/ SSLCOMMERZ PAYMENT CONFIGURATION -->
        </div>
    </div>
@endsection
