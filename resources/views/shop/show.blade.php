@extends('layouts.app')

@section('title', $product->name . ' — Pistis')

@section('content')
@php
    $galleryImages = $product->image_urls;
    if (empty($galleryImages) && $product->primary_image_url) {
        $galleryImages = [$product->primary_image_url];
    }
    $totalImages = count($galleryImages);
@endphp

<div class="container">
    <div class="product-detail">
        {{-- Luxury Product Gallery (Side-by-Side Viewport Fitted) --}}
        <div class="product-gallery-wrapper" id="product-gallery">
            <div class="product-gallery-layout">
                {{-- Vertical Thumbnails Rail --}}
                @if($totalImages > 1)
                    <div class="gallery-thumbs-rail" id="gallery-thumbs-rail">
                        <button type="button" class="thumb-rail-btn prev-btn" onclick="scrollThumbsRail(-1)" aria-label="Scroll thumbnails up">▲</button>
                        <div class="thumbs-rail-track" id="thumbnail-strip">
                            @foreach($galleryImages as $index => $imageUrl)
                                <button type="button" 
                                        class="thumb-item {{ $index === 0 ? 'active' : '' }}" 
                                        data-index="{{ $index }}"
                                        data-src="{{ $imageUrl }}"
                                        onclick="selectGalleryImage({{ $index }})"
                                        aria-label="View product image {{ $index + 1 }}">
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }} thumb {{ $index + 1 }}" loading="lazy">
                                    <span class="thumb-active-ring"></span>
                                </button>
                            @endforeach
                        </div>
                        <button type="button" class="thumb-rail-btn next-btn" onclick="scrollThumbsRail(1)" aria-label="Scroll thumbnails down">▼</button>
                    </div>
                @endif

                {{-- Main Image Stage --}}
                <div class="main-image-stage" id="main-image-stage" onclick="openLightbox()">
                    @if($totalImages > 0)
                        <img src="{{ $galleryImages[0] }}" 
                             alt="{{ $product->name }}" 
                             id="main-gallery-img" 
                             class="gallery-current-img" 
                             loading="eager">
                        
                        {{-- Zoom Lens Stage --}}
                        <div class="zoom-magnifier" id="zoom-magnifier" style="background-image: url('{{ $galleryImages[0] }}');"></div>

                        {{-- Floating Glass Controls --}}
                        @if($totalImages > 1)
                            <button type="button" class="gallery-nav-btn prev-btn" onclick="event.stopPropagation(); navigateGallery(-1);" aria-label="Previous Image">
                                ‹
                            </button>
                            <button type="button" class="gallery-nav-btn next-btn" onclick="event.stopPropagation(); navigateGallery(1);" aria-label="Next Image">
                                ›
                            </button>
                            <div class="gallery-counter-pill" id="gallery-counter-pill">
                                <span id="current-img-index">1</span> / {{ $totalImages }}
                            </div>
                        @endif

                        <div class="gallery-zoom-hint" title="Click to open fullscreen view">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/>
                            </svg>
                        </div>
                    @else
                        <div class="no-image" style="aspect-ratio:1;">📦</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Product Info --}}
        <div class="product-detail-info">
            @if($product->category)
                <div style="font-family:'Inter',sans-serif;font-size:0.7rem;color:#737373;text-transform:uppercase;letter-spacing:0.2em;margin-bottom:12px;">
                    {{ $product->category->name }}
                </div>
            @endif

            <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(1.8rem,4vw,2.8rem);font-weight:300;color:#000000;margin:0 0 8px;letter-spacing:0.02em;">{{ $product->name }}</h1>
            <div style="font-size:0.7rem;color:#a3a3a3;letter-spacing:0.15em;text-transform:uppercase;margin-bottom:24px;">REF: {{ $product->sku }}</div>

            <div style="display:flex;align-items:baseline;gap:12px;margin-bottom:20px;">
                <span style="font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:300;color:#000000;">{{ $product->formatted_price }}</span>
                @if($product->compare_price && $product->compare_price > $product->price)
                    <span style="font-size:0.9rem;color:#a3a3a3;text-decoration:line-through;">{{ $currency_symbol }}{{ number_format($product->compare_price, 2) }}</span>
                @endif
            </div>

            <div style="font-size:0.75rem;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:24px;{{ $product->is_in_stock ? 'color:#000000;' : 'color:#dc2626;' }}">
                {{ $product->is_in_stock ? '● IN STOCK — ' . $product->stock_quantity . ' AVAILABLE' : '● CURRENTLY UNAVAILABLE' }}
            </div>

            <div style="width:100%;height:1px;background:#e5e5e5;margin-bottom:24px;"></div>

            @if($product->description)
                <div style="font-size:0.9rem;line-height:1.7;color:#525252;margin-bottom:32px;">
                    {!! nl2br(e($product->description)) !!}
                </div>
            @endif

            @if($product->is_in_stock)
                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form" style="margin-bottom:20px;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    {{-- Piece Colors Selector --}}
                    @if(!empty($product->colors_list) && count($product->colors_list) > 0)
                        <div class="product-color-selector" style="margin-bottom:24px;">
                            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:12px;">
                                <span style="font-family:'Inter',sans-serif;font-size:0.75rem;letter-spacing:0.18em;text-transform:uppercase;color:#000000;">
                                    COLOR &mdash; <strong id="selected-color-label" style="font-weight:600;letter-spacing:0.1em;">{{ $product->colors_list[0]['name'] }}</strong>
                                </span>
                                <span style="font-size:0.68rem;letter-spacing:0.1em;text-transform:uppercase;color:#a3a3a3;">
                                    {{ count($product->colors_list) }} {{ count($product->colors_list) === 1 ? 'COLOR' : 'COLORS' }}
                                </span>
                            </div>

                            <div class="color-swatches-container" style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;">
                                @foreach($product->colors_list as $index => $color)
                                    <button type="button" 
                                            class="color-swatch-option {{ $index === 0 ? 'active' : '' }}"
                                            data-color-name="{{ $color['name'] }}"
                                            data-color-code="{{ $color['code'] }}"
                                            data-color-index="{{ $index }}"
                                            onclick="selectProductColor(this, '{{ addslashes($color['name']) }}', {{ $index }})"
                                            title="{{ $color['name'] }}"
                                            aria-label="Select {{ $color['name'] }}"
                                            style="all:unset;cursor:pointer;display:inline-flex;align-items:center;gap:8px;padding:6px 14px 6px 7px;border:1px solid {{ $index === 0 ? '#000000' : '#e5e5e5' }};background:{{ $index === 0 ? '#000000' : '#ffffff' }};color:{{ $index === 0 ? '#ffffff' : '#000000' }};transition:all 0.2s ease;">
                                        <span class="swatch-circle" style="width:18px;height:18px;border-radius:50%;background:{{ $color['code'] }};border:1px solid {{ strtolower($color['code']) === '#ffffff' ? '#d4d4d8' : 'rgba(0,0,0,0.15)' }};display:inline-block;flex-shrink:0;box-shadow:inset 0 1px 2px rgba(0,0,0,0.15);"></span>
                                        <span class="swatch-name" style="font-family:'Inter',sans-serif;font-size:0.75rem;letter-spacing:0.08em;text-transform:uppercase;font-weight:500;">{{ $color['name'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                            <input type="hidden" name="color" id="product-selected-color-input" value="{{ $product->colors_list[0]['name'] }}">
                        </div>
                    @endif

                    <div style="display:flex;gap:12px;align-items:stretch;">
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="form-control qty-input" style="width:72px;text-align:center;border:1px solid #e5e5e5;border-radius:0;">
                        <button type="submit" class="btn btn-primary btn-lg flex-1" style="border-radius:0;letter-spacing:0.15em;font-size:0.8rem;">ADD TO BAG</button>
                    </div>
                </form>
            @else
                <button class="btn btn-secondary btn-lg w-100" disabled style="border-radius:0;letter-spacing:0.15em;font-size:0.8rem;">SOLD OUT</button>
            @endif

            {{-- Buy on Shopify --}}
            @if($shopifyUrl)
                <div style="display:flex;align-items:center;gap:12px;padding:16px;border:1px solid #e5e5e5;margin-top:12px;margin-bottom:12px;">
                    <span style="font-size:0.75rem;color:#737373;letter-spacing:0.05em;text-transform:uppercase;">Also available on Shopify</span>
                    <a href="{{ $shopifyUrl }}" target="_blank" style="margin-left:auto;font-size:0.75rem;color:#000000;letter-spacing:0.1em;text-transform:uppercase;font-weight:600;">PURCHASE →</a>
                </div>
            @endif

            {{-- Synced badge --}}
            @if($product->is_synced_to_shopify)
                <div style="margin-top:12px;">
                    <span class="badge badge-success" style="font-size:0.65rem;letter-spacing:0.1em;">✓ SYNCED TO SHOPIFY</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
        <section class="section" style="margin-top:48px;">
            <div class="section-header">
                <div>
                    <h2 class="section-title">You May Also Like</h2>
                    <p class="section-subtitle">Complementary pieces from the archive</p>
                </div>
            </div>
            <div class="product-grid">
                @foreach($relatedProducts as $related)
                    @include('components.product-card', ['product' => $related])
                @endforeach
            </div>
        </section>
    @endif
</div>

{{-- Fullscreen Luxury Lightbox Modal --}}
@if($totalImages > 0)
<div class="gallery-lightbox" id="gallery-lightbox" onclick="closeLightbox(event)">
    <div class="lightbox-dialog" onclick="event.stopPropagation()">
        <button type="button" class="lightbox-close-btn" onclick="closeLightbox()" aria-label="Close fullscreen view">✕</button>
        <div class="lightbox-stage">
            <img src="{{ $galleryImages[0] }}" alt="{{ $product->name }}" id="lightbox-img" class="lightbox-img">
            @if($totalImages > 1)
                <button type="button" class="lightbox-nav-btn prev-btn" onclick="navigateLightbox(-1)" aria-label="Previous image">‹</button>
                <button type="button" class="lightbox-nav-btn next-btn" onclick="navigateLightbox(1)" aria-label="Next image">›</button>
                <div class="lightbox-counter" id="lightbox-counter">1 / {{ $totalImages }}</div>
            @endif
        </div>
        @if($totalImages > 1)
            <div class="lightbox-thumbnails">
                @foreach($galleryImages as $index => $imageUrl)
                    <button type="button" 
                            class="lightbox-thumb {{ $index === 0 ? 'active' : '' }}" 
                            data-index="{{ $index }}"
                            onclick="selectLightboxImage({{ $index }})">
                        <img src="{{ $imageUrl }}" alt="Thumbnail {{ $index + 1 }}">
                    </button>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endif

@push('scripts')
<script>
(function() {
    const images = @json($galleryImages);
    if (!images || images.length === 0) return;

    let currentIndex = 0;
    const mainImg = document.getElementById('main-gallery-img');
    const zoomMagnifier = document.getElementById('zoom-magnifier');
    const mainStage = document.getElementById('main-image-stage');
    const counterPill = document.getElementById('current-img-index');
    const thumbnailStrip = document.getElementById('thumbnail-strip');
    const thumbs = document.querySelectorAll('.thumb-item');
    const lightbox = document.getElementById('gallery-lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxCounter = document.getElementById('lightbox-counter');
    const lightboxThumbs = document.querySelectorAll('.lightbox-thumb');

    // Bulletproof, Instant & Smooth Image Switch
    window.selectGalleryImage = function(index) {
        if (index < 0 || index >= images.length) return;
        currentIndex = index;
        const newSrc = images[currentIndex];

        // Update Thumbnails Active State
        thumbs.forEach((t, i) => {
            const isActive = (i === currentIndex);
            t.classList.toggle('active', isActive);
            if (isActive) {
                t.scrollIntoView({ behavior: 'smooth', inline: 'nearest', block: 'nearest' });
            }
        });

        // Update Counter
        if (counterPill) {
            counterPill.textContent = currentIndex + 1;
        }

        // Fast cross-fade image swap
        if (mainImg) {
            mainImg.style.opacity = '0.4';
            const tempImg = new Image();
            tempImg.onload = function() {
                mainImg.src = newSrc;
                mainImg.style.opacity = '1';
                if (zoomMagnifier) {
                    zoomMagnifier.style.backgroundImage = `url('${newSrc}')`;
                }
            };
            tempImg.onerror = function() {
                mainImg.src = newSrc;
                mainImg.style.opacity = '1';
            };
            tempImg.src = newSrc;
        }

        // Sync Lightbox if open
        if (lightbox && lightbox.classList.contains('active')) {
            updateLightbox();
        }
    };

    window.navigateGallery = function(direction) {
        let newIndex = currentIndex + direction;
        if (newIndex < 0) newIndex = images.length - 1;
        if (newIndex >= images.length) newIndex = 0;
        selectGalleryImage(newIndex);
    };

    window.scrollThumbsRail = function(direction) {
        if (!thumbnailStrip) return;
        const isVertical = window.innerWidth > 768;
        if (isVertical) {
            thumbnailStrip.scrollBy({ top: 110 * direction, behavior: 'smooth' });
        } else {
            thumbnailStrip.scrollBy({ left: 110 * direction, behavior: 'smooth' });
        }
    };

    // ─── Luxury Zoom Lens Magnifier ─────────────────────────────
    if (mainStage && zoomMagnifier) {
        mainStage.addEventListener('mousemove', function(e) {
            const rect = mainStage.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const xPercent = Math.max(0, Math.min(100, (x / rect.width) * 100));
            const yPercent = Math.max(0, Math.min(100, (y / rect.height) * 100));
            
            zoomMagnifier.style.backgroundPosition = `${xPercent}% ${yPercent}%`;
            zoomMagnifier.style.opacity = '1';
        });

        mainStage.addEventListener('mouseleave', function() {
            zoomMagnifier.style.opacity = '0';
        });
    }

    // ─── Mobile Touch Swipe Support ────────────────────────────
    if (mainStage && images.length > 1) {
        let touchStartX = 0;
        let touchEndX = 0;

        mainStage.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        mainStage.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });

        function handleSwipe() {
            const swipeDistance = touchEndX - touchStartX;
            if (Math.abs(swipeDistance) > 40) {
                if (swipeDistance < 0) {
                    // Swiped Left -> Next Image
                    navigateGallery(1);
                } else {
                    // Swiped Right -> Prev Image
                    navigateGallery(-1);
                }
            }
        }
    }

    // ─── Fullscreen Lightbox ───────────────────────────────────
    window.openLightbox = function() {
        if (!lightbox) return;
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
        updateLightbox();
    };

    window.closeLightbox = function(e) {
        if (e && e.target && e.target.closest('.lightbox-stage, .lightbox-thumbnails') && !e.target.classList.contains('lightbox-close-btn')) {
            return;
        }
        if (lightbox) {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        }
    };

    function updateLightbox() {
        if (!lightboxImg) return;
        lightboxImg.src = images[currentIndex];
        if (lightboxCounter) {
            lightboxCounter.textContent = `${currentIndex + 1} / ${images.length}`;
        }
        lightboxThumbs.forEach((t, i) => {
            t.classList.toggle('active', i === currentIndex);
            if (i === currentIndex) {
                t.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            }
        });
    }

    window.selectLightboxImage = function(index) {
        currentIndex = index;
        selectGalleryImage(index);
        updateLightbox();
    };

    window.navigateLightbox = function(direction) {
        navigateGallery(direction);
        updateLightbox();
    };

    // ─── Color Variant Selection ────────────────────────────────
    window.selectProductColor = function(btn, colorName, colorIndex) {
        document.querySelectorAll('.color-swatch-option').forEach(el => {
            el.classList.remove('active');
            el.style.background = '#ffffff';
            el.style.color = '#000000';
            el.style.borderColor = '#e5e5e5';
        });

        btn.classList.add('active');
        btn.style.background = '#000000';
        btn.style.color = '#ffffff';
        btn.style.borderColor = '#000000';

        const label = document.getElementById('selected-color-label');
        if (label) {
            label.textContent = colorName;
        }

        const input = document.getElementById('product-selected-color-input');
        if (input) {
            input.value = colorName;
        }

        // If gallery images exist, attempt to jump to matching photo
        if (typeof window.selectGalleryImage === 'function' && typeof images !== 'undefined' && images.length > 1) {
            const lowerColor = colorName.toLowerCase();
            let targetIndex = -1;
            for (let i = 0; i < images.length; i++) {
                if (images[i].toLowerCase().includes(lowerColor)) {
                    targetIndex = i;
                    break;
                }
            }
            if (targetIndex === -1 && typeof colorIndex !== 'undefined') {
                const swatches = document.querySelectorAll('.color-swatch-option');
                const totalSwatches = swatches.length;
                if (totalSwatches > 1 && images.length >= totalSwatches) {
                    const step = Math.floor(images.length / totalSwatches);
                    targetIndex = Math.min(colorIndex * step, images.length - 1);
                }
            }
            if (targetIndex >= 0 && targetIndex < images.length) {
                window.selectGalleryImage(targetIndex);
            }
        }
    };

    // Keyboard Navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && lightbox && lightbox.classList.contains('active')) {
            closeLightbox();
        } else if (e.key === 'ArrowLeft') {
            navigateGallery(-1);
        } else if (e.key === 'ArrowRight') {
            navigateGallery(1);
        }
    });
})();
</script>
@endpush
@endsection



