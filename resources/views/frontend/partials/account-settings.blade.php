@if (session('profile_success'))<div class="alert alert-success" role="status">{{ session('profile_success') }}</div>@endif
@if (session('profile_error'))<div class="alert alert-danger" role="alert">{{ session('profile_error') }}</div>@endif
@if ($errors->any())
    <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
<form action="{{ route('profile.settings.update') }}" method="POST" id="accountSettingsForm">
    @csrf
    @method('PATCH')
    <h5 class="fw-bold text-dark mb-3 border-bottom border-light pb-2">Personal Information</h5>
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6"><label class="form-label small fw-bold text-secondary" for="account-first-name">First Name</label><input class="form-control bg-light" type="text" id="account-first-name" name="first_name" value="{{ old('first_name', $firstName) }}" required maxlength="120" autocomplete="given-name"></div>
        <div class="col-12 col-md-6"><label class="form-label small fw-bold text-secondary" for="account-last-name">Last Name</label><input class="form-control bg-light" type="text" id="account-last-name" name="last_name" value="{{ old('last_name', $lastName) }}" maxlength="120" autocomplete="family-name"></div>
        <div class="col-12 col-md-6"><label class="form-label small fw-bold text-secondary" for="account-email">Email Address</label><input class="form-control bg-light" type="email" id="account-email" name="email" value="{{ old('email', $profileUser->email) }}" maxlength="255" autocomplete="email"></div>
        <div class="col-12 col-md-6"><label class="form-label small fw-bold text-secondary" for="account-mobile">Mobile Number</label><input class="form-control bg-light" type="tel" id="account-mobile" name="mobile" value="{{ old('mobile', \App\Support\BangladeshPhone::normalize($profileUser->mobile)) }}" placeholder="01712345678 or +8801712345678" required maxlength="30" autocomplete="tel"><div class="form-text">11-digit numbers are saved with +88.</div></div>
        <div class="col-12"><label class="form-label small fw-bold text-secondary" for="account-address">Address</label><textarea class="form-control bg-light" id="account-address" name="address" rows="3" maxlength="255" autocomplete="street-address">{{ old('address', $profileCustomer?->address ?? $profileUser->address) }}</textarea></div>
    </div>
    <h5 class="fw-bold text-dark mb-3 border-bottom border-light pb-2 mt-5">Password &amp; Account Security</h5>
    <p class="small text-muted">Enter your current password to change your email, mobile number or password. Leave the new password fields blank to keep your existing password.</p>
    <div class="row g-4 mb-4">
        <div class="col-12"><label class="form-label small fw-bold text-secondary" for="current_password">Current Password</label><input class="form-control bg-light" type="password" id="current_password" name="current_password" autocomplete="current-password"></div>
        <div class="col-12 col-md-6"><label class="form-label small fw-bold text-secondary" for="account-password">New Password</label><input class="form-control bg-light" type="password" id="account-password" name="password" autocomplete="new-password" minlength="8" maxlength="72"><div class="form-text">8–72 characters.</div></div>
        <div class="col-12 col-md-6"><label class="form-label small fw-bold text-secondary" for="account-password-confirmation">Confirm New Password</label><input class="form-control bg-light" type="password" id="account-password-confirmation" name="password_confirmation" autocomplete="new-password" minlength="8" maxlength="72"></div>
    </div>
    <button type="submit" class="btn btn-primary rounded-pill fw-bolder px-5 py-2 shadow-sm text-uppercase">Save Changes</button>
</form>
<script>
    (() => {
        const phone = document.getElementById('account-mobile');
        function normalizeAccountPhone() {
            const digits = '০১২৩৪৫৬৭৮৯';
            let value = phone.value.replace(/[০-৯]/g, digit => digits.indexOf(digit)).replace(/[\s().-]/g, '');
            if (/^01[0-9]{9}$/.test(value)) value = '+88' + value;
            else if (/^8801[0-9]{9}$/.test(value)) value = '+' + value;
            if (/^\+8801[0-9]{9}$/.test(value)) phone.value = value;
        }
        phone.addEventListener('input', normalizeAccountPhone);
        phone.addEventListener('blur', normalizeAccountPhone);
        const password = document.getElementById('account-password');
        const confirmation = document.getElementById('account-password-confirmation');
        password.addEventListener('input', () => { confirmation.required = Boolean(password.value); });
        document.getElementById('accountSettingsForm').addEventListener('submit', event => {
            const button = event.submitter;
            if (button) { button.disabled = true; button.textContent = 'Saving...'; }
        });
    })();
</script>
