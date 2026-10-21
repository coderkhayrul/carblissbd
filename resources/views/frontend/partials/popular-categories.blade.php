@if ($popularCategories->isNotEmpty())
    <section class="container-md px-3 my-4">
        <div class="bg-white rounded-4 border border-light shadow-sm p-3 p-md-4 overflow-hidden" style="background-color: #FFFFFF;">
            <div class="text-center mb-4 pb-2">
                <h2 class="h3 fw-bolder text-dark text-uppercase mb-1" style="letter-spacing: .5px;">Shop By Category</h2>
                <p class="text-muted small mb-0">Choose your favorite products from our popular categories</p>
            </div>
            <div class="position-relative">
                <div class="swiper new-category-swiper pb-5">
                    <div class="swiper-wrapper">
                        @foreach ($popularCategories as $category)
                            <div class="swiper-slide" style="width: 210px;">
                                <a href="{{ route('category', ['category' => $category->slug]) }}" class="text-decoration-none d-flex flex-column bg-white rounded-3 border category-card overflow-hidden h-100">
                                    <div class="p-3 d-flex align-items-center justify-content-center bg-white" style="height: 170px;">
                                        <img src="{{ asset($category->image ?: 'backend/assets/extra/preview-image.png') }}" alt="{{ $category->name }}" loading="lazy" class="w-100 h-100 object-fit-contain category-img-zoom">
                                    </div>
                                    <div class="border-top p-3 bg-white mt-auto" style="border-color: rgba(241,90,41,.1) !important;">
                                        <span class="fw-bolder text-dark text-center d-block" style="font-size: .85rem;">{{ $category->name }}</span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination category-pagination w-100 d-flex justify-content-center mt-3"></div>
                </div>
            </div>
        </div>
    </section>
@endif
