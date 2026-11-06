@extends('admin.layouts.app')
@section('admin-content')
    <div class="row g-6">
        <!-- Navigation -->
        @include('admin.settings.settings_sidebar')
        <!-- /Navigation -->

        <!-- Options -->
        <div class="col-12 col-lg-9">
            <div class="row">
                <div class="card mb-6">
                    <h5 class="card-header">COMPANY CONFIGURATION</h5>
                    <form class="card-body" action="{{ route('admin.company.settings.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @if ($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                        <div class="row g-6">
                            <div class="col-md-6">
                                <label class="form-label" for="name">Company Name
                                    <span class="text-danger">*</span> </label>
                                <input type="text" id="name" name="name"
                                    class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                    placeholder="Car Bliss BD" value="{{ $companySetting->name }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="title">Company Title
                                    <span class="text-danger">*</span> </label>
                                <input type="text" id="title" name="title"
                                    class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                    placeholder="Car Bliss BD" value="{{ $companySetting->title }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="phone">Company Phone
                                    <span class="text-danger">*</span> </label>
                                <input type="text" id="phone" name="phone"
                                    class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" placeholder=""
                                    value="{{ $companySetting->phone }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="hotline">Company Hotline
                                    <span class="text-danger">*</span> </label>
                                <input type="text" id="hotline" name="hotline"
                                    class="form-control {{ $errors->has('hotline') ? 'is-invalid' : '' }}" placeholder=""
                                    value="{{ $companySetting->hotline }}">
                                @error('hotline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="email">Company Email
                                    <span class="text-danger">*</span> </label>
                                <input type="text" id="email" name="email"
                                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder=""
                                    value="{{ $companySetting->email }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="website">Company Website</label>
                                <input type="text" id="website" name="website" class="form-control" placeholder=""
                                    value="{{ $companySetting->website }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="bin">Company BIN</label>
                                <input type="text" id="bin" name="bin" class="form-control" placeholder=""
                                    value="{{ $companySetting->bin }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="app_link">App Link</label>
                                <input type="text" id="app_link" name="app_link" class="form-control" placeholder=""
                                    value="{{ $companySetting->app_link }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="address">Address</label>
                                <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address"
                                    rows="3">{{ $companySetting->address }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="bill_footer">Bill Footer</label>
                                <textarea class="form-control" name="bill_footer" id="bill_footer" rows="3">{{ $companySetting->bill_footer }}</textarea>
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
                                        <img src="{{ $companySetting->logo != null ? asset($companySetting->logo) : asset('backend/default/briefcase.png') }}"
                                            alt="company-logo" class="d-block w-px-100 h-px-100 rounded"
                                            id="companyLogo">
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
                                        <img src="{{ $companySetting->favicon != null ? asset($companySetting->favicon) : asset('backend/default/briefcase.png') }}"
                                            alt="company-favicon" class="d-block w-px-100 h-px-100 rounded"
                                            id="companyFavicon">
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
                                <div class="card-body">
                                    <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
                                        <img src="{{ $companySetting->admin_favicon != null ? asset($companySetting->admin_favicon) : asset('backend/default/briefcase.png') }}"
                                            alt="admin-favicon" class="d-block w-px-100 h-px-100 rounded"
                                            id="adminFavicon">
                                        <div class="button-wrapper">
                                            <label for="adminFaviconUpload" class="btn btn-primary me-3 mb-4"
                                                tabindex="0">
                                                <span class="d-none d-sm-block">Upload new admin favicon</span>
                                                <i class="icon-base bx bx-upload d-block d-sm-none"></i>
                                                <input type="file" id="adminFaviconUpload" name="admin_favicon"
                                                    class="favicon-file-input" hidden=""
                                                    accept="image/png, image/jpeg">
                                            </label>
                                            <button type="button"
                                                class="btn btn-label-secondary admin-favicon-image-reset mb-4">
                                                <i class="icon-base bx bx-reset d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Reset</span>
                                            </button>

                                            <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="auth_bg">Authentication Background</label>
                                <div class="card-body">
                                    <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
                                        <img src="{{ $companySetting->auth_bg != null ? asset($companySetting->auth_bg) : asset('backend/default/auth_bg.png') }}"
                                            alt="admin bg" class="d-block w-px-200 h-px-100 rounded" id="authBg">
                                        <div class="button-wrapper">
                                            <label for="authBgUpload" class="btn btn-primary me-3 mb-4" tabindex="0">
                                                <span class="d-none d-sm-block">Upload new authentication background</span>
                                                <i class="icon-base bx bx-upload d-block d-sm-none"></i>
                                                <input type="file" id="authBgUpload" name="auth_bg"
                                                    class="favicon-file-input" hidden=""
                                                    accept="image/png, image/jpeg">
                                            </label>
                                            <button type="button"
                                                class="btn btn-label-secondary auth-bg-image-reset mb-4">
                                                <i class="icon-base bx bx-reset d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Reset</span>
                                            </button>

                                            <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SEO CONFIGURATION -->
                        <hr class="my-6 mx-n6">
                        <h6>SEO CONFIGURATION</h6>
                        <div class="row g-4 mb-4">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="og_image">Default Social Share Image</label>
                                @if ($companySetting->og_image)<img class="d-block img-fluid rounded mb-2" src="{{ asset($companySetting->og_image) }}" alt="Default social share image" style="max-height:140px;object-fit:contain">@endif
                                <input class="form-control" type="file" id="og_image" name="og_image" accept="image/jpeg,image/png,image/webp">
                                <p class="form-text">Recommended: 1200 x 630 JPG/PNG, maximum 4 MB. Product pages use their own product images.</p>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="google_site_verification">Google Search Console Verification Token</label>
                                <input class="form-control" id="google_site_verification" name="google_site_verification" maxlength="255" value="{{ old('google_site_verification', $companySetting->google_site_verification) }}" placeholder="Content value from google-site-verification">
                                <p class="form-text">Enter only the content token from the HTML verification tag, not the entire tag. Domain properties use DNS verification at your domain provider.</p>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="gtag">Google Analytics 4 Measurement ID</label>
                                <input class="form-control" id="gtag" name="gtag" maxlength="255" value="{{ old('gtag', $companySetting->gtag) }}" placeholder="G-XXXXXXXXXX">
                                <p class="form-text">Paste the Measurement ID only. Do not paste JavaScript or a Tag Manager container ID.</p>
                                <input type="hidden" name="analytics_enabled" value="0">
                                <label class="form-check form-switch"><input class="form-check-input" type="checkbox" name="analytics_enabled" value="1" @checked(old('analytics_enabled', $companySetting->analytics_enabled))><span class="form-check-label">Enable Google Analytics on public storefront pages</span></label>
                            </div>
                            <div class="col-12 col-md-6">
                                <input type="hidden" name="search_indexing_enabled" value="0">
                                <label class="form-check form-switch"><input class="form-check-input" type="checkbox" name="search_indexing_enabled" value="1" @checked(old('search_indexing_enabled', $companySetting->search_indexing_enabled ?? true))><span class="form-check-label">Allow Search Engine Indexing</span></label>
                                <p class="form-text">Local/testing environments and .test domains always use noindex. On the live server set APP_ENV=production and APP_URL to the correct HTTPS domain.</p>
                                <p class="mb-1"><a href="{{ route('seo.sitemap') }}" target="_blank" rel="noopener">XML Sitemap</a> &middot; <a href="{{ route('seo.robots') }}" target="_blank" rel="noopener">robots.txt</a></p>
                                <p class="form-text">Submit /sitemap.xml in Search Console after verifying your live website.</p>
                            </div>
                        </div>
                        <div class="row g-6">
                            <div class="col-md-6">
                                <label class="form-label" for="meta_title">Meta Title</label>
                                <input type="text" id="meta_title" name="meta_title" class="form-control"
                                    placeholder="" value="{{ $companySetting->meta_title }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="meta_keyword">Meta Keyword</label>
                                <input type="text" id="meta_keyword" name="meta_keyword" class="form-control"
                                    placeholder="" value="{{ $companySetting->meta_keyword }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="meta_description">Meta Description</label>
                                <textarea class="form-control" name="meta_description" id="meta_description" rows="3">{{ $companySetting->meta_description }}</textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="footer_description">Footer Description</label>
                                <textarea class="form-control" name="footer_description" id="footer_description" rows="3">{{ $companySetting->footer_description }}</textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="bangla_footer_description">Bangla Footer
                                    Description</label>
                                <textarea class="form-control" name="bangla_footer_description" id="bangla_footer_description" rows="3">{{ $companySetting->bangla_footer_description }}</textarea>
                            </div>
                        </div>
                        <div class="pt-6 text-end">
                            <button type="submit" class="btn btn-primary">
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
