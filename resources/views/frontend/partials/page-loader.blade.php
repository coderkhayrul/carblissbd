@if ($footerCompany?->page_loader_enabled ?? true)
    <div id="store-page-loader" role="status" aria-live="polite" aria-label="Loading page">
        <div class="store-loader-panel">@include('frontend.partials.loader-icon')<span>Loading...</span></div>
    </div>
@endif
