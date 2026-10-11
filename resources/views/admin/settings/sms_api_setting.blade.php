@extends('admin.layouts.app')
@section('admin-content')
    <div class="row g-6">
        <!-- Navigation -->
        @include('admin.settings.settings_sidebar')
        <!-- /Navigation -->

        <div class="col-12 col-lg-9">
            <div class="card mb-6 card-action">
                <div class="card-header bg-light mb-4">
                    <h5 class="card-action-title mb-0">Sms Api Settings</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.sms.api.settings.update') }}" method="POST">
                        @csrf
                        <div class="row gx-6">
                            <div class="mb-4 col-12 col-sm-6">
                                <label class="form-label" for="base_url">Base Url</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" id="base_url" name="base_url" placeholder=""
                                        value="{{ $smsApiSetting->base_url }}" />
                                </div>
                            </div>
                            <div class="mb-4 col-12 col-sm-6">
                                <label class="form-label" for="sender_id">Sender Id</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" id="sender_id" name="sender_id"
                                        placeholder="" value="{{ $smsApiSetting->sender_id }}" />
                                </div>
                            </div>
                            <div class="mb-4 col-12 col-sm-6">
                                <label class="form-label" for="api_key">Api Key</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" id="api_key" name="api_key" placeholder=""
                                        value="{{ $smsApiSetting->api_key }}" />
                                </div>
                            </div>
                            <div class="mb-4 col-12 col-sm-6">
                                <label class="form-label" for="api_secret">Api Secret</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" id="api_secret" name="api_secret"
                                        placeholder="" value="{{ $smsApiSetting->api_secret }}" />
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
        </div>
    </div>
@endsection
