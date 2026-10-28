@if ($topBrands->isNotEmpty())
    <section id="popular-brands" class="container-md px-3 my-4">
        <div class="bg-white rounded-4 border border-light shadow-sm p-3 p-md-4 overflow-hidden" style="background-color: #FFFFFF;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 fw-bolder text-dark text-uppercase mb-1">Shop By Verified Brands</h2>
                    <p class="text-muted small mb-0">Explore your favorite automotive brands</p>
                </div>
                <div class="d-none d-sm-block bg-primary rounded" style="width: 60px; height: 4px;"></div>
            </div>
            <div class="swiper brands-swiper py-2">
                <div class="swiper-wrapper">
                    @foreach ($topBrands as $brand)
                        <div class="swiper-slide h-auto">
                            <a href="{{ route('brand', ['brand' => $brand->slug]) }}" class="d-block text-decoration-none bg-light p-3 rounded-4 text-center border border-light h-100" title="{{ $brand->title ?: $brand->name }}">
                                <div class="d-flex align-items-center justify-content-center" style="height: 80px; overflow: hidden;">
                                    <img src="{{ asset($brand->logo ?: 'backend/assets/extra/preview-image.png') }}" alt="{{ $brand->name }} logo" loading="lazy" class="w-100 h-100 object-fit-contain">
                                </div>
                                <span class="small fw-semibold text-dark d-block mt-2">{{ $brand->name }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
