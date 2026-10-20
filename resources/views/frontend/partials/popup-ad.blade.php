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
    <style>
        #popup-ad-modal { z-index: 1080; }
        .popup-ad-backdrop { z-index: 1070; --bs-backdrop-opacity: .65; }
        #popup-ad-modal .modal-dialog { max-width: 400px; margin: 16px auto; min-height: calc(100% - 32px); width: calc(100% - 32px); }
        #popup-ad-modal .modal-content { position: relative; border: 0; border-radius: 0; background: transparent; }
        #popup-ad-modal .modal-body { padding: 0; }
        #popup-ad-modal .popup-ad-image { display: block; width: 100%; height: auto; max-height: calc(100dvh - 32px); object-fit: contain; }
        #popup-ad-modal .popup-ad-close { position: absolute; top: 6px; left: 6px; z-index: 2; display: grid; place-items: center; width: 26px; height: 26px; padding: 0; border: 0; border-radius: 50%; background: rgba(255, 255, 255, .9); color: #111; font: 26px/1 Arial, sans-serif; cursor: pointer; }
        #popup-ad-modal .popup-ad-close:focus-visible { outline: 3px solid #f15a29; outline-offset: 2px; }
        #popup-ad-modal .popup-ad-text { padding: 40px 24px 24px; background: #fff; max-height: calc(100dvh - 32px); overflow-y: auto; }
        #popup-ad-modal video { display: block; max-height: calc(100dvh - 64px); background: #000; }
    </style>
    <div class="modal fade" id="popup-ad-modal" tabindex="-1" aria-label="{{ $popupAd->title ?: 'Special Offer' }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="popup-ad-close" data-bs-dismiss="modal" aria-label="Close advertisement"><span aria-hidden="true">&times;</span></button>
                <div class="modal-body">
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
                        <img src="{{ asset($popupAd->image) }}" alt="{{ $popupAd->title ?: 'Special offer' }}" class="popup-ad-image">
                        @if ($adUrl)</a>@endif
                    @endif
                    @if (!$popupAd->image && !$popupAd->is_video)
                        <div class="popup-ad-text">
                            <h2 class="fs-5 fw-bold">{{ $popupAd->title ?: 'Special Offer' }}</h2>
                            @if ($popupAd->description)
                                <p class="mb-0" style="white-space: pre-line; overflow-wrap: anywhere;">{{ $popupAd->description }}</p>
                            @endif
                            @if ($adUrl)
                                <a href="{{ $adUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary w-100 mt-3">View Offer</a>
                            @endif
                        </div>
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
            document.querySelector('.modal-backdrop:last-of-type')?.classList.add('popup-ad-backdrop');
        });
    </script>
@endif
