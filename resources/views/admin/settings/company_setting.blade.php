@extends('admin.layouts.app')
@section('admin-content')
    <div class="row g-6">
        <!-- Navigation -->
        @include('admin.settings.settings_sidebar')
        <!-- /Navigation -->

        <!-- Options -->
        <div class="col-12 col-lg-10">
            <div class="row">
                <div class="card mb-6">
                    <h5 class="card-header">COMPANY CONFIGURATION</h5>
                    <form class="card-body">
                        <div class="row g-6">
                            <div class="col-md-6">
                                <label class="form-label" for="company_name">Company Name
                                    <span class="text-danger">*</span> </label>
                                <input type="text" id="company_name" name="name" class="form-control"
                                    placeholder="Car Bliss BD">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="company_title">Company Title
                                    <span class="text-danger">*</span> </label>
                                <input type="text" id="company_title" name="title" class="form-control"
                                    placeholder="Car Bliss BD">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="company_phone">Company Phone
                                    <span class="text-danger">*</span> </label>
                                <input type="text" id="company_phone" name="phone" class="form-control" placeholder="">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="company_hotline">Company Hotline
                                    <span class="text-danger">*</span> </label>
                                <input type="text" id="company_hotline" name="hotline" class="form-control"
                                    placeholder="">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="company_email">Company Email
                                    <span class="text-danger">*</span> </label>
                                <input type="text" id="company_email" name="email" class="form-control" placeholder="">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="company_website">Company Website
                                    <span class="text-danger">*</span> </label>
                                <input type="text" id="company_website" name="website" class="form-control"
                                    placeholder="">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="company_bin">Company BIN
                                    <span class="text-danger">*</span> </label>
                                <input type="text" id="company_bin" name="bin" class="form-control" placeholder="">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="app_link">App Link
                                    <span class="text-danger">*</span> </label>
                                <input type="text" id="app_link" name="app_link" class="form-control" placeholder="">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="address">Address
                                    <span class="text-danger">*</span> </label>
                                <textarea class="form-control" name="address" id="address" rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="bill_footer">Bill Footer
                                    <span class="text-danger">*</span> </label>
                                <textarea class="form-control" name="bill_footer" id="bill_footer" rows="3"></textarea>
                            </div>
                        </div>
                        <!-- ASSETS CONFIGURATION -->
                        <hr class="my-6 mx-n6">
                        <h6>ASSETS FILE CONFIGURATION</h6>
                        <div class="row g-6">
                            <div class="col-md-6">
                                <label class="form-label" for="logo">Company Logo</label>
                                <div class="card-body">
                                    <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
                                        <img src="{{ asset('backend/default/briefcase.png') }}" alt="user-avatar"
                                            class="d-block w-px-100 h-px-100 rounded" id="companyLogo">
                                        <div class="button-wrapper">
                                            <label for="logoUpload" class="btn btn-primary me-3 mb-4" tabindex="0">
                                                <span class="d-none d-sm-block">Upload new logo</span>
                                                <i class="icon-base bx bx-upload d-block d-sm-none"></i>
                                                <input type="file" id="logoUpload" name="logo"
                                                    class="logo-file-input" hidden=""
                                                    accept="image/png, image/jpeg">
                                            </label>
                                            <button type="button" class="btn btn-label-secondary logo-image-reset mb-4">
                                                <i class="icon-base bx bx-reset d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Reset</span>
                                            </button>

                                            <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="favicon">Company Favicon</label>
                                <div class="card-body">
                                    <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
                                        <img src="{{ asset('backend/default/briefcase.png') }}" alt="user-avatar"
                                            class="d-block w-px-100 h-px-100 rounded" id="companyFavicon">
                                        <div class="button-wrapper">
                                            <label for="faviconUpload" class="btn btn-primary me-3 mb-4" tabindex="0">
                                                <span class="d-none d-sm-block">Upload new favicon</span>
                                                <i class="icon-base bx bx-upload d-block d-sm-none"></i>
                                                <input type="file" id="faviconUpload" name="favicon"
                                                    class="favicon-file-input" hidden=""
                                                    accept="image/png, image/jpeg">
                                            </label>
                                            <button type="button"
                                                class="btn btn-label-secondary favicon-image-reset mb-4">
                                                <i class="icon-base bx bx-reset d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Reset</span>
                                            </button>

                                            <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="admin_favicon">Admin Favicon</label>
                                <input type="text" id="admin_favicon" name="admin_favicon" class="form-control"
                                    placeholder="">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="auth_bg">Authentication Background</label>
                                <input type="text" id="auth_bg" name="auth_bg" class="form-control"
                                    placeholder="">
                            </div>
                        </div>

                        <!-- SEO CONFIGURATION -->
                        <hr class="my-6 mx-n6">
                        <h6>SEO CONFIGURATION</h6>
                        <div class="row g-6">
                            <div class="col-md-6">
                                <label class="form-label" for="meta_title">Meta Title</label>
                                <input type="text" id="meta_title" name="meta_title" class="form-control"
                                    placeholder="">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="meta_keyword">Meta Keyword</label>
                                <input type="text" id="meta_keyword" name="meta_keyword" class="form-control"
                                    placeholder="">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="meta_description">Meta Description</label>
                                <textarea class="form-control" name="meta_description" id="meta_description" rows="3"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="footer_description">Footer Description</label>
                                <textarea class="form-control" name="footer_description" id="footer_description" rows="3"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="bangla_footer_description">Bangla Footer
                                    Description</label>
                                <textarea class="form-control" name="bangla_footer_description" id="bangla_footer_description" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="pt-6 text-end">
                            <button type="button" class="btn btn-primary">
                                <span class="icon-base bx bx-check-circle icon-sm me-2"></span>Update Setting
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <!-- /Options-->
    </div>
@endsection
