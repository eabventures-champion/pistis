@extends('layouts.app')

@section('title', 'Pistis — Premium Ecommerce Store')

@section('content')
{{-- Luxury Monochrome Fashion Editorial Hero Slider --}}
@if(isset($heroSlides) && $heroSlides->count() > 0)
    <section class="editorial-hero-slider" id="editorial-hero-slider" data-slide-count="{{ $heroSlides->count() }}">
        <div class="hero-slider-track">
            @foreach($heroSlides as $index => $slide)
                <div class="hero-slide-item {{ $index === 0 ? 'active is-visible' : '' }}" 
                     data-index="{{ $index }}"
                     data-duration="{{ $slide->display_duration }}">
                    
                    <div class="hero-editorial-container">
                        {{-- Left Column: Editorial Typography & CTAs --}}
                        <div class="hero-content-col">
                            <div class="hero-content-inner">
                                <div class="hero-collection-tag-wrap">
                                    <span class="hero-collection-tag">{{ $slide->collection_name ?: 'COLLECTION' }}</span>
                                    @if($slide->subtitle)
                                        <span class="hero-subtitle-separator">·</span>
                                        <span class="hero-subtitle-tag">{{ $slide->subtitle }}</span>
                                    @endif
                                </div>

                                <h1 class="hero-editorial-title">
                                    @foreach($slide->title_lines as $line)
                                        <span class="title-line-mask">
                                            <span class="title-line-text">{{ $line }}</span>
                                        </span>
                                    @endforeach
                                </h1>

                                @if($slide->description)
                                    <div class="hero-desc-wrap">
                                        <p class="hero-editorial-desc">{{ $slide->description }}</p>
                                    </div>
                                @endif

                                <div class="hero-cta-group">
                                    @if($slide->primary_button_text)
                                        <a href="{{ $slide->primary_button_url ?: route('shop.index') }}" class="hero-btn-primary">
                                            <span>{{ $slide->primary_button_text }}</span>
                                            <span class="btn-arrow">→</span>
                                        </a>
                                    @endif

                                    @if($slide->secondary_button_text)
                                        <a href="{{ $slide->secondary_button_url ?: route('shop.index') }}" class="hero-btn-secondary">
                                            <span>{{ $slide->secondary_button_text }}</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Right Column: Cinematic Fashion Photography --}}
                        <div class="hero-image-col">
                            <div class="hero-image-frame">
                                <picture>
                                    @if($slide->mobile_image)
                                        <source media="(max-width: 768px)" srcset="{{ $slide->mobile_image_url }}">
                                    @endif
                                    <img src="{{ $slide->image_url }}" 
                                         alt="{{ $slide->title }}" 
                                         class="hero-editorial-img"
                                         @if($index === 0) fetchpriority="high" loading="eager" @else loading="lazy" @endif>
                                </picture>
                                <div class="hero-image-vignette"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Minimalist Luxury Controls --}}
        @if($heroSlides->count() > 1)
            <div class="hero-slider-controls">
                <div class="hero-controls-inner">
                    {{-- Slide Counter --}}
                    <div class="hero-slide-counter">
                        <span class="counter-current" id="hero-current-num">01</span>
                        <span class="counter-divider">/</span>
                        <span class="counter-total">{{ str_pad($heroSlides->count(), 2, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    {{-- Linear Progress Bar Timer --}}
                    <div class="hero-progress-track">
                        <div class="hero-progress-fill" id="hero-progress-fill"></div>
                    </div>

                    {{-- Next / Prev Navigation Arrows --}}
                    <div class="hero-nav-arrows">
                        <button type="button" class="hero-nav-btn prev" onclick="heroSlider.prev()" aria-label="Previous slide">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 12H5M12 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button type="button" class="hero-nav-btn next" onclick="heroSlider.next()" aria-label="Next slide">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Direct Slide Selection Dots/Dashes --}}
                    <div class="hero-slide-dashes">
                        @foreach($heroSlides as $idx => $s)
                            <button type="button" 
                                    class="slide-dash-btn {{ $idx === 0 ? 'active' : '' }}" 
                                    onclick="heroSlider.goTo({{ $idx }})" 
                                    aria-label="Go to slide {{ $idx + 1 }}">
                                <span class="dash-line"></span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </section>
@else
    {{-- High-Fashion Monochrome Fallback Hero --}}
    <section class="editorial-hero-slider fallback-hero">
        <div class="hero-editorial-container">
            <div class="hero-content-col">
                <div class="hero-content-inner">
                    <div class="hero-collection-tag-wrap">
                        <span class="hero-collection-tag">NEW COLLECTION</span>
                    </div>
                    <h1 class="hero-editorial-title">
                        <span class="title-line-mask"><span class="title-line-text">THE ART OF</span></span>
                        <span class="title-line-mask"><span class="title-line-text">SIMPLICITY</span></span>
                    </h1>
                    <div class="hero-desc-wrap">
                        <p class="hero-editorial-desc">Timeless silhouettes designed for modern expression. Discover curated essentials crafted from premium fabrics.</p>
                    </div>
                    <div class="hero-cta-group">
                        <a href="{{ route('shop.index') }}" class="hero-btn-primary">
                            <span>SHOP COLLECTION</span>
                            <span class="btn-arrow">→</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="hero-image-col">
                <div class="hero-image-frame">
                    <img src="https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=1600&q=85" 
                         alt="Pistis Luxury Collection" 
                         class="hero-editorial-img" 
                         fetchpriority="high">
                </div>
            </div>
        </div>
    </section>
@endif

{{-- Categories --}}
@if(($homepage_show_categories ?? true) && $categories->count() > 0)
<section class="section">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Shop by Category</h2>
                <p class="section-subtitle">Explore the collection</p>
            </div>
        </div>
        <div class="category-grid">
            @foreach($categories as $category)
                <a href="{{ route('shop.index', ['category' => $category->id]) }}" class="category-card">
                    <div class="category-name">{{ $category->name }}</div>
                    <div class="category-count">{{ $category->total_products_count }} pieces</div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Featured Products --}}
@if($featuredProducts->count() > 0)
<section class="section">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Editorial Selection</h2>
                <p class="section-subtitle">Curated pieces from the archive</p>
            </div>
            <a href="{{ route('shop.index') }}" class="btn btn-secondary">View All →</a>
        </div>
        <div class="product-grid">
            @foreach($featuredProducts as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- New Arrivals --}}
@if($newArrivals->count() > 0)
<section class="section">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">New Arrivals</h2>
                <p class="section-subtitle">Latest additions to the collection</p>
            </div>
            <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="btn btn-secondary">Discover →</a>
        </div>
        <div class="product-grid">
            @foreach($newArrivals as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Brand Manifesto (The Pistis Philosophy) --}}
<section class="brand-manifesto-section">
    <div class="brand-manifesto-inner">
        <span class="manifesto-tag">The Pistis Philosophy</span>
        <h2 class="manifesto-quote">Where Architecture Meets The Human Form</h2>
        <p class="manifesto-body">Every piece in the Pistis archive is an exercise in restraint — constructed from premium textiles, refined through precision tailoring, and designed to outlast the ephemeral noise of seasonal trends.</p>
        <a href="{{ route('shop.index') }}" class="btn btn-outline" style="background:transparent;color:#ffffff;border-color:#ffffff;">EXPLORE ARCHIVE →</a>
    </div>
</section>
@endsection

@push('scripts')
<script>
window.heroSlider = (function() {
    const sliderEl = document.getElementById('editorial-hero-slider');
    if (!sliderEl) return {};

    const slides = Array.from(sliderEl.querySelectorAll('.hero-slide-item'));
    const totalSlides = slides.length;
    if (totalSlides <= 1) return {};

    let currentIndex = 0;
    let isAnimating = false;
    let autoTimer = null;
    let progressStartTime = null;
    let progressAnimFrame = null;
    let slideDurationMs = 7000;
    let isPaused = false;

    const currentNumEl = document.getElementById('hero-current-num');
    const progressFill = document.getElementById('hero-progress-fill');
    const dashes = sliderEl.querySelectorAll('.slide-dash-btn');

    function init() {
        startSlideTimer();
        bindEvents();
    }

    function goTo(nextIndex) {
        if (isAnimating || nextIndex === currentIndex || nextIndex < 0 || nextIndex >= totalSlides) return;
        isAnimating = true;

        const currentSlide = slides[currentIndex];
        const nextSlide = slides[nextIndex];

        // Determine transition direction
        const isNext = nextIndex > currentIndex || (currentIndex === totalSlides - 1 && nextIndex === 0);

        // Cancel previous timer/progress
        cancelSlideTimer();

        // Animate out current slide
        currentSlide.classList.remove('is-visible', 'active');
        currentSlide.classList.add('is-exiting');

        // Prepare next slide
        nextSlide.classList.add('active');
        // Force reflow
        void nextSlide.offsetWidth;
        nextSlide.classList.add('is-visible');

        // Update Counter
        if (currentNumEl) {
            currentNumEl.style.opacity = '0';
            currentNumEl.style.transform = 'translateY(-6px)';
            setTimeout(() => {
                currentNumEl.textContent = String(nextIndex + 1).padStart(2, '0');
                currentNumEl.style.opacity = '1';
                currentNumEl.style.transform = 'translateY(0)';
            }, 200);
        }

        // Update Dashes
        dashes.forEach((d, i) => d.classList.toggle('active', i === nextIndex));

        setTimeout(() => {
            currentSlide.classList.remove('is-exiting');
            currentIndex = nextIndex;
            isAnimating = false;
            if (!isPaused) {
                startSlideTimer();
            }
        }, 800);
    }

    function next() {
        const nextIndex = (currentIndex + 1) % totalSlides;
        goTo(nextIndex);
    }

    function prev() {
        const prevIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        goTo(prevIndex);
    }

    function startSlideTimer() {
        cancelSlideTimer();
        const durationSec = parseInt(slides[currentIndex].getAttribute('data-duration') || '7', 10);
        slideDurationMs = durationSec * 1000;
        progressStartTime = performance.now();

        function step(now) {
            if (isPaused) {
                progressStartTime = now - (progressFill.offsetWidth / progressFill.parentElement.offsetWidth) * slideDurationMs;
            }
            const elapsed = now - progressStartTime;
            const pct = Math.min(100, (elapsed / slideDurationMs) * 100);
            
            if (progressFill) {
                progressFill.style.width = pct + '%';
            }

            if (elapsed < slideDurationMs) {
                progressAnimFrame = requestAnimationFrame(step);
            } else {
                next();
            }
        }

        progressAnimFrame = requestAnimationFrame(step);
    }

    function cancelSlideTimer() {
        if (progressAnimFrame) {
            cancelAnimationFrame(progressAnimFrame);
            progressAnimFrame = null;
        }
        if (progressFill) {
            progressFill.style.width = '0%';
        }
    }

    function bindEvents() {
        // Pause on Hover
        sliderEl.addEventListener('mouseenter', () => {
            isPaused = true;
        });

        sliderEl.addEventListener('mouseleave', () => {
            isPaused = false;
        });

        // Touch Swipe Gestures
        let touchStartX = 0;
        let touchEndX = 0;

        sliderEl.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        sliderEl.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchEndX - touchStartX;
            if (Math.abs(diff) > 40) {
                if (diff < 0) next();
                else prev();
            }
        }, { passive: true });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
            if (e.key === 'ArrowRight') next();
            if (e.key === 'ArrowLeft') prev();
        });
    }

    // Start on DOM loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    return { next, prev, goTo };
})();
</script>
@endpush



