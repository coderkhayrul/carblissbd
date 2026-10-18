@extends('admin.layouts.app')
@push('backend_style')
    <style>
        .social-edit .row>*,
        .social-edit .ecommerce-select2-dropdown {
            min-width: 0;
        }
    </style>
@endpush
@section('admin-content')
    <div class="app-ecommerce social-edit">
        <form action="{{ route('admin.social-links.update', $social_link) }}" method="POST">
            @csrf
            @method('PUT')
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Edit Social Link</h4>
                    <p class="mb-0">Social Link placed across your store</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <div class="d-flex gap-4">
                        <a href="{{ route('admin.social-links.index') }}" class="btn btn-label-secondary"><i
                                class="bx bx-arrow-back me-2 fs-5"></i> Back</a>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-2 fs-5"></i> Update Social
                        Link</button>
                </div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row">
                <!-- First column-->
                <div class="col-12 col-lg-8">
                    <!-- Slider Information -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Social Link information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-6">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Facebook"
                                    name="name" value="{{ old('name', $social_link->name) }}" required maxlength="255"
                                    aria-label="Social Link Name" />
                            </div>
                            <div class="row g-4 mb-6">
                                <div class="col-12 col-sm-6">
                                    <label class="form-label" for="url">Social URL</label>
                                    <input type="url" class="form-control" id="url"
                                        placeholder="https://www.facebook.com/yourpage" name="url"
                                        value="{{ old('url', $social_link->url) }}" required maxlength="255"
                                        aria-label="Social URL" />
                                </div>
                                <div class="col-12 col-sm-6">
                                    <label class="form-label" for="icon">Icon</label>
                                    <input type="text" class="form-control" id="icon"
                                        placeholder="fab fa-facebook-f" name="icon"
                                        value="{{ old('icon', $social_link->icon) }}" maxlength="255"
                                        aria-label="FontAwesome Icon" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Second column -->

                <!-- Extra Info -->
                <div class="col-12 col-lg-4">
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Extra Info</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-6">
                                <label class="form-label" for="background_color">Background Color</label>
                                <input type="text" class="form-control" id="background_color"
                                    placeholder="Background Color" name="background_color"
                                    value="{{ old('background_color', $social_link->background_color) }}" maxlength="255"
                                    aria-label="Background Color" />
                            </div>
                            <div class="mb-6">
                                <label class="form-label" for="foreground_color">Foreground Color</label>
                                <input type="text" class="form-control" id="foreground_color"
                                    placeholder="Foreground Color" name="foreground_color"
                                    value="{{ old('foreground_color', $social_link->foreground_color) }}" maxlength="255"
                                    aria-label="Foreground Color" />
                            </div>
                            <!-- Status -->
                            <div class="mb-6 col ecommerce-select2-dropdown">
                                <label class="form-label mb-1" for="status-org">Status </label>
                                <select name="status" id="status-org" class="select2 form-select"
                                    data-placeholder="Select Status">
                                    <option value="1" @selected(old('status', $social_link->status) == '1')>Active</option>
                                    <option value="0" @selected(old('status', $social_link->status) == '0')>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /Extra Info -->
                </div>
                <!-- /Second column -->
            </div>
        </form>
    </div>
@endsection
