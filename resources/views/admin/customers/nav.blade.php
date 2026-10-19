<div class="nav-align-top">
    <ul class="nav nav-pills flex-column flex-md-row mb-6 flex-wrap row-gap-2">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.customers.show', $customer) ? 'active' : '' }}"
                href="{{ route('admin.customers.show', $customer) }}"><i
                    class="icon-base bx bx-user icon-sm me-1_5"></i>Account</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.customers.security.edit', $customer) ? 'active' : '' }}"
                href="{{ route('admin.customers.security.edit', $customer) }}"><i
                    class="icon-base bx bx-lock-alt icon-sm me-1_5"></i>Security</a>
        </li>
    </ul>
</div>
