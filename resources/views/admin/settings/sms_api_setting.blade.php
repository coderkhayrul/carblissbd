@extends('admin.layouts.app')
@section('admin-content')
    <div class="row g-6">
        <!-- Navigation -->
        @include('admin.settings.settings_sidebar')
        <!-- /Navigation -->

        <div class="col-12 col-lg-9">
            <div class="card mb-6 card-action">
                <div class="card-header bg-light mb-4">
                    <h5 class="card-action-title mb-0">SMS Provider Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row gx-6">
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="base_url">Base Url</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="base_url" name="base_url" placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="sender_id">Sender Id</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="sender_id" name="sender_id" placeholder="" />
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
                            <label class="form-label" for="api_key">Api Key</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="api_key" name="api_key" placeholder="" />
                            </div>
                        </div>
                        <div class="mb-4 col-12 col-sm-6">
                            <label class="form-label" for="api_secret">Api Secret</label>
                            <div class="input-group">
                                <input class="form-control" type="text" id="api_secret" name="api_secret"
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
        </div>
    </div>
@endsection
