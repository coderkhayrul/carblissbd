<li class="nav-item navbar-dropdown dropdown me-3 me-xl-2" id="admin-order-notifications"
    data-feed-url="{{ route('admin.order-notifications.index') }}" data-read-all-url="{{ route('admin.order-notifications.read-all') }}"
    data-read-url="{{ url('/admin/order-notifications') }}" data-user-id="{{ auth()->id() }}"
    data-created-order="{{ session('new_order_sound', '') }}">
    <button class="nav-link dropdown-toggle hide-arrow border-0 bg-transparent" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" aria-label="Order notifications">
        <span class="position-relative"><i class="icon-base bx bx-bell icon-md" aria-hidden="true"></i><span data-order-notice-badge class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem" @if (!$orderNoticeFeed['unread_count']) hidden @endif>{{ $orderNoticeFeed['unread_count'] > 99 ? '99+' : $orderNoticeFeed['unread_count'] }}</span></span>
    </button>
    <div class="dropdown-menu dropdown-menu-end p-0" style="width:380px;max-width:calc(100vw - 24px)">
        <div class="border-bottom p-3"><div class="d-flex align-items-center justify-content-between gap-2"><h6 class="mb-0">Order Notifications</h6><span class="badge bg-label-primary" data-order-notice-count>{{ $orderNoticeFeed['unread_count'] }} unread</span></div>
            <div class="d-flex flex-wrap align-items-center gap-2 mt-2"><button class="btn btn-sm btn-label-secondary" type="button" data-order-notice-sound aria-pressed="false">Enable Sound</button><button class="btn btn-sm btn-link" type="button" data-order-notice-read-all>Mark all read</button></div>
            <small class="d-block text-muted mt-2" data-order-notice-sound-status>Enable sound once to hear new order alerts.</small>
        </div>
        <div data-order-notice-list style="max-height:380px;overflow-y:auto;overscroll-behavior:contain">
            @forelse ($orderNoticeFeed['items'] as $notice)
                <div class="d-flex align-items-start gap-2 p-3 border-bottom {{ !$notice['read'] ? 'bg-label-primary' : '' }}" data-order-notice-id="{{ $notice['id'] }}">
                    <i class="bx bx-cart fs-4" aria-hidden="true"></i><a href="{{ $notice['url'] }}" class="flex-grow-1 text-body" style="min-width:0;overflow-wrap:anywhere" data-order-notice-open="{{ $notice['id'] }}"><strong class="d-block">New Order: {{ $notice['order_no'] }}</strong><small class="d-block">{{ $notice['customer'] }} · BDT {{ $notice['amount'] }}</small><small class="d-block text-muted">{{ $notice['source'] }} · {{ $notice['time'] }}</small></a>
                    @if (!$notice['read'])<button class="btn btn-sm btn-link p-1" type="button" data-order-notice-read="{{ $notice['id'] }}" aria-label="Mark {{ $notice['order_no'] }} as read"><i class="bx bx-check" aria-hidden="true"></i></button>@endif
                </div>
            @empty<div class="p-4 text-center text-muted">No new orders yet.</div>@endforelse
        </div>
        <div class="p-3 border-top"><a class="btn btn-primary btn-sm w-100" href="{{ route('admin.orders.index') }}">View All Orders</a></div>
    </div>
    <script type="application/json" id="admin-order-notice-initial">{!! json_encode($orderNoticeFeed, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    <form hidden data-order-notice-csrf>@csrf</form>
</li>
