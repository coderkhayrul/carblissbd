@if ($popupAd && ($popupAd->title || $popupAd->description || $popupAd->image || ($popupAd->is_video && $popupAd->url)))
    @php
        $adUrl = preg_match('~^https?://~i', $popupAd->url ?? '') ? $popupAd->url : null;
        $embedUrl = null;
        if ($popupAd->is_video && $adUrl) {
            $host = strtolower(parse_url($adUrl, PHP_URL_HOST) ?? '');
            $path = parse_url($adUrl, PHP_URL_PATH) ?? '';
            parse_str(parse_url($adUrl, PHP_URL_QUERY) ?? '', $query);
            $videoId = null;
            if (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
                $videoId = $query['v'] ?? (preg_match('~^/(?:embed|shorts)/([A-Za-z0-9_-]{11})~', $path, $match) ? $match[1] : null);
            } elseif ($host === 'youtu.be') {
                $videoId = trim($path, '/');
            }
            if (is_string($videoId) && preg_match('/^[A-Za-z0-9_-]{11}$/', $videoId)) {
                $embedUrl = 'https://www.youtube-nocookie.com/embed/' . $videoId;
            }
        }
    @endphp
    <div class="modal fade" id="popup-ad-modal" tabindex="-1" aria-labelledby="popup-ad-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0">
                    <h2 class="modal-title fs-5 fw-bold" id="popup-ad-title">{{ $popupAd->title ?: 'Special Offer' }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close advertisement"></button>
                </div>
                <div class="modal-body pt-0">
                    @if ($popupAd->is_video && $adUrl)
                        @if ($embedUrl)
                            <div class="ratio ratio-16x9">
                                <iframe data-video-src="{{ $embedUrl }}" title="{{ $popupAd->title ?: 'Advertisement video' }}" allow="fullscreen; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        @else
                            <video controls playsinline preload="none" class="w-100 rounded" @if ($popupAd->image) poster="{{ asset($popupAd->image) }}" @endif>
                                <source src="{{ $adUrl }}">
                                Your browser does not support this video.
                            </video>
                            <a href="{{ $adUrl }}" target="_blank" rel="noopener noreferrer" class="d-inline-block mt-2">Open video</a>
                        @endif
                    @elseif ($popupAd->image)
                        @if ($adUrl)<a href="{{ $adUrl }}" target="_blank" rel="noopener noreferrer">@endif
                        <img src="{{ asset($popupAd->image) }}" alt="{{ $popupAd->title ?: 'Special offer' }}" class="w-100 rounded" style="max-height: 60vh; object-fit: contain;">
                        @if ($adUrl)</a>@endif
                    @endif
                    @if ($popupAd->description)
                        <p class="mt-3 mb-0" style="white-space: pre-line; overflow-wrap: anywhere;">{{ $popupAd->description }}</p>
                    @endif
                    @if (!$popupAd->is_video && $adUrl)
                        <a href="{{ $adUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary w-100 mt-3">View Offer</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('load', function () {
            const element = document.getElementById('popup-ad-modal');
            if (!window.bootstrap?.Modal || !element) return;
            const modal = bootstrap.Modal.getOrCreateInstance(element);
            const frame = element.querySelector('iframe');
            element.addEventListener('shown.bs.modal', function () {
                if (frame) frame.src = frame.dataset.videoSrc;
            });
            element.addEventListener('hidden.bs.modal', function () {
                if (frame) frame.removeAttribute('src');
                element.querySelectorAll('video').forEach(video => video.pause());
            });
            modal.show();
        });
    </script>
@endif
