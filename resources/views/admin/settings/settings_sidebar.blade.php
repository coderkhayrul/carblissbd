<div class="col-12 col-lg-2">
    <div class="d-flex justify-content-between flex-column mb-4 mb-md-0">
        <ul class="list-group">
            <li
                class="list-group-item list-group-item-action {{ request()->routeIs('admin.company.settings') ? 'active' : '' }}">
                <a href="{{ route('admin.company.settings') }}" class="text-decoration-none text-body">
                    <i class="icon-base bx bx-cog me-2"></i>
                    Company Setting
                </a>
            </li>
            <li
                class="list-group-item list-group-item-action {{ request()->routeIs('admin.payment.settings') ? 'active' : '' }}">
                <a href="{{ route('admin.payment.settings') }}" class="text-decoration-none text-body">
                    <i class="icon-base bx bx-cog me-2"></i>
                    Payment Setting
                </a>
            </li>
            <li
                class="list-group-item list-group-item-action {{ request()->routeIs('admin.email.settings') ? 'active' : '' }}">
                <a href="{{ route('admin.email.settings') }}" class="text-decoration-none text-body">
                    <i class="icon-base bx bx-cog me-2"></i>
                    Email Setting
                </a>
            </li>
            <li
                class="list-group-item list-group-item-action {{ request()->routeIs('admin.sms.api.settings') ? 'active' : '' }}">
                <a href="{{ route('admin.sms.api.settings') }}" class="text-decoration-none text-body">
                    <i class="icon-base bx bx-cog me-2"></i>
                    SMS API Setting
                </a>
            </li>
        </ul>
    </div>
</div>
