<span class="store-loader-icon" aria-hidden="true">
    @if ($footerCompany?->loader_image)
        <img class="loader-custom-image" src="{{ asset($footerCompany->loader_image) }}" width="64" height="64" alt="" decoding="async" onerror="this.hidden=true">
    @endif
    <span class="loader-spinner" @if (!$footerCompany?->loader_image) data-default-spinner @endif></span>
</span>
