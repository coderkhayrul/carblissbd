<div class="row g-3" data-pathao-locations data-location-url="{{ url('/admin/configuration/courier/pathao/locations') }}">
    <div class="col-12"><button type="button" class="btn btn-sm btn-label-primary" data-load-pathao-cities>Load Pathao Cities</button><span class="small text-muted ms-2" role="status" data-location-status></span></div>
    <div class="col-12 col-md-4"><label class="form-label">City<select name="recipient_city" class="form-select" data-pathao-city><option value="">Select city</option></select></label></div>
    <div class="col-12 col-md-4"><label class="form-label">Zone<select name="recipient_zone" class="form-select" data-pathao-zone disabled><option value="">Select zone</option></select></label></div>
    <div class="col-12 col-md-4"><label class="form-label">Area<select name="recipient_area" class="form-select" data-pathao-area disabled><option value="">Select area</option></select></label></div>
</div>
