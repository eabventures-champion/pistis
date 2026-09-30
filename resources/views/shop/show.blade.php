@extends('layouts.app')

@section('title', $product->name . ' — Pistis')

@section('content')
@php
    $defaultColor = !empty($product->colors_list[0]['name']) ? $product->colors_list[0]['name'] : null;
    $colorGalleries = $product->color_galleries;
    if ($defaultColor && !empty($colorGalleries[$defaultColor])) {
        $galleryImages = $colorGalleries[$defaultColor];
    } else {
        $galleryImages = $product->image_urls;
        if (empty($galleryImages) && $product->primary_image_url) {
            $galleryImages = [$product->primary_image_url];
        }
    }
    $totalImages = count($galleryImages);
@endphp

<div class="container">
    <div class="product-detail">
        {{-- Luxury Product Gallery (Side-by-Side Viewport Fitted) --}}
        <div class="product-gallery-wrapper" id="product-gallery">
            <div class="product-gallery-layout">
                {{-- Vertical Thumbnails Rail (Additional Items) --}}
                <div class="gallery-thumbs-rail" id="gallery-thumbs-rail" style="display: {{ $totalImages > 1 ? 'flex' : 'none' }};">
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

                {{-- Main Image Stage (Main Cover) --}}
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
                        <button type="button" class="gallery-nav-btn prev-btn" id="gallery-nav-prev" onclick="event.stopPropagation(); navigateGallery(-1);" aria-label="Previous Image" style="display: {{ $totalImages > 1 ? 'flex' : 'none' }};">
                            ‹
                        </button>
                        <button type="button" class="gallery-nav-btn next-btn" id="gallery-nav-next" onclick="event.stopPropagation(); navigateGallery(1);" aria-label="Next Image" style="display: {{ $totalImages > 1 ? 'flex' : 'none' }};">
                            ›
                        </button>
                        <div class="gallery-counter-pill" id="gallery-counter-pill" style="display: {{ $totalImages > 1 ? 'flex' : 'none' }};">
                            <span id="current-img-index">1</span> / <span id="total-img-count">{{ $totalImages }}</span>
                        </div>

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
                @if($product->formatted_compare_price)
                    <span style="font-size:0.9rem;color:#a3a3a3;text-decoration:line-through;">{{ $product->formatted_compare_price }}</span>
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
                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form" style="display:flex;flex-direction:column;gap:0;margin-bottom:24px;width:100%;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    {{-- Piece Colors Selector --}}
                    @if(!empty($product->colors_list) && count($product->colors_list) > 0)
                        <div class="product-color-selector" style="margin-bottom:24px;width:100%;">
                            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:12px;">
                                <span style="font-family:'Inter',sans-serif;font-size:0.75rem;letter-spacing:0.18em;text-transform:uppercase;color:#000000;">
                                    COLOR &mdash; <strong id="selected-color-label" style="font-weight:600;letter-spacing:0.1em;">{{ $product->colors_list[0]['name'] }}</strong>
                                </span>
                                <span style="font-size:0.68rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;">
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
                                            style="cursor:pointer;display:inline-flex;align-items:center;gap:8px;padding:8px 16px;border:1px solid {{ $index === 0 ? '#000000' : '#e5e5e5' }};background:{{ $index === 0 ? '#000000' : '#ffffff' }};color:{{ $index === 0 ? '#ffffff' : '#000000' }};transition:all 0.18s ease;">
                                        <span class="swatch-circle" style="width:16px;height:16px;border-radius:50%;background:{{ $color['code'] }};border:1px solid {{ strtolower($color['code']) === '#ffffff' ? '#d4d4d8' : 'rgba(0,0,0,0.15)' }};display:inline-block;flex-shrink:0;box-shadow:inset 0 1px 2px rgba(0,0,0,0.15);"></span>
                                        <span class="swatch-name" style="font-family:'Inter',sans-serif;font-size:0.75rem;letter-spacing:0.08em;text-transform:uppercase;font-weight:600;">{{ $color['name'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                            <input type="hidden" name="color" id="product-selected-color-input" value="{{ $product->colors_list[0]['name'] }}">
                        </div>
                    @endif

                    {{-- Piece Sizes Selector --}}
                    @if(!empty($product->sizes_list) && count($product->sizes_list) > 0)
                        <div class="product-size-selector" style="margin-bottom:28px;width:100%;">
                            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:12px;">
                                <span style="font-family:'Inter',sans-serif;font-size:0.75rem;letter-spacing:0.18em;text-transform:uppercase;color:#000000;">
                                    SIZE &mdash; <strong id="selected-size-label" style="font-weight:600;letter-spacing:0.1em;">{{ $product->sizes_list[0] }}</strong>
                                </span>
                                <span style="font-size:0.68rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;">
                                    STANDARD FIT
                                </span>
                            </div>

                            <div class="size-options-container" style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;">
                                @foreach($product->sizes_list as $sIdx => $sizeVal)
                                    <button type="button" 
                                            class="size-pill-option {{ $sIdx === 0 ? 'active' : '' }}"
                                            data-size="{{ $sizeVal }}"
                                            onclick="selectProductSize(this, '{{ addslashes($sizeVal) }}')"
                                            style="min-width:48px;height:44px;padding:0 16px;border:1px solid {{ $sIdx === 0 ? '#000000' : '#e5e5e5' }};background:{{ $sIdx === 0 ? '#000000' : '#ffffff' }};color:{{ $sIdx === 0 ? '#ffffff' : '#000000' }};font-family:'Inter',sans-serif;font-size:0.8rem;font-weight:600;letter-spacing:0.06em;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;transition:all 0.15s ease;">
                                        {{ $sizeVal }}
                                    </button>
                                @endforeach
                            </div>
                            <input type="hidden" name="size" id="product-selected-size-input" value="{{ $product->sizes_list[0] }}">
                        </div>
                    @endif

                    {{-- Add to Bag Action Row --}}
                    <div class="product-action-row" style="display:flex;gap:12px;align-items:center;width:100%;margin-top:6px;">
                        <div class="quantity-stepper" style="display:inline-flex;align-items:center;border:1px solid #171717;height:50px;background:#ffffff;flex-shrink:0;">
                            <button type="button" onclick="decrementQty()" style="width:38px;height:100%;background:none;border:none;cursor:pointer;font-size:1.1rem;color:#000000;display:flex;align-items:center;justify-content:center;transition:background 0.15s;" onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='none'" title="Decrease quantity">−</button>
                            <input type="number" id="pdp-quantity-input" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="qty-input" style="width:44px;height:100%;border:none;text-align:center;font-size:0.95rem;font-weight:600;font-family:'Inter',sans-serif;background:transparent;-moz-appearance:textfield;" readonly>
                            <button type="button" onclick="incrementQty({{ $product->stock_quantity }})" style="width:38px;height:100%;background:none;border:none;cursor:pointer;font-size:1.1rem;color:#000000;display:flex;align-items:center;justify-content:center;transition:background 0.15s;" onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='none'" title="Increase quantity">+</button>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg flex-1 add-to-bag-button" style="height:50px;border-radius:0;letter-spacing:0.18em;font-size:0.82rem;font-weight:600;background:#000000;color:#ffffff;display:inline-flex;align-items:center;justify-content:center;gap:10px;transition:all 0.2s ease;">
                            <span>ADD TO BAG</span>
                            <span style="opacity:0.35;">·</span>
                            <span id="pdp-add-to-bag-price">{{ $product->formatted_price }}</span>
                        </button>
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
    const unitPrice = {{ (float) $product->price }};
    const currencySymbol = @json(\App\Models\Setting::get('currency_symbol', '$'));
    let images = @json($galleryImages);
    const colorGalleries = @json($product->color_galleries);
    let currentIndex = 0;

    const mainImg = document.getElementById('main-gallery-img');
    const zoomMagnifier = document.getElementById('zoom-magnifier');
    const mainStage = document.getElementById('main-image-stage');
    const counterPill = document.getElementById('current-img-index');
    const totalCountPill = document.getElementById('total-img-count');
    const thumbnailStrip = document.getElementById('thumbnail-strip');
    const thumbsRail = document.getElementById('gallery-thumbs-rail');
    const navPrev = document.getElementById('gallery-nav-prev');
    const navNext = document.getElementById('gallery-nav-next');
    const pillBox = document.getElementById('gallery-counter-pill');

    const lightbox = document.getElementById('gallery-lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxCounter = document.getElementById('lightbox-counter');
    const lightboxThumbContainer = document.querySelector('.lightbox-thumbnails');

    // Bulletproof, Instant & Smooth Image Switch
    window.selectGalleryImage = function(index) {
        if (!images || index < 0 || index >= images.length) return;
        currentIndex = index;
        const newSrc = images[currentIndex];

        // Update Thumbnails Active State
        const thumbs = document.querySelectorAll('.thumb-item');
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
            mainImg.style.opacity = '0.35';
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
        if (!images || images.length === 0) return;
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

    // ─── Rebuild Gallery UI when a Color is Picked ─────────────
    function rebuildGalleryUI(targetIndex = 0) {
        if (!images || images.length === 0) return;
        currentIndex = Math.max(0, Math.min(targetIndex, images.length - 1));
        const initialSrc = images[currentIndex];

        // 1. Swap Main Cover
        if (mainImg) {
            mainImg.style.opacity = '0.35';
            const imgLoader = new Image();
            imgLoader.onload = function() {
                mainImg.src = initialSrc;
                mainImg.style.opacity = '1';
                if (zoomMagnifier) {
                    zoomMagnifier.style.backgroundImage = `url('${initialSrc}')`;
                }
            };
            imgLoader.onerror = function() {
                mainImg.src = initialSrc;
                mainImg.style.opacity = '1';
            };
            imgLoader.src = initialSrc;
        }

        // 2. Rebuild Vertical Thumbnail Strip (Additional Items)
        if (thumbnailStrip) {
            thumbnailStrip.innerHTML = images.map((url, i) => `
                <button type="button" 
                        class="thumb-item ${i === currentIndex ? 'active' : ''}" 
                        data-index="${i}"
                        data-src="${url}"
                        onclick="selectGalleryImage(${i})"
                        aria-label="View product view ${i + 1}">
                    <img src="${url}" alt="Piece view ${i + 1}" loading="lazy">
                    <span class="thumb-active-ring"></span>
                </button>
            `).join('');
        }

        // 3. Update Rail and Nav Visibility
        const hasMultiple = images.length > 1;
        if (thumbsRail) thumbsRail.style.display = hasMultiple ? 'flex' : 'none';
        if (navPrev) navPrev.style.display = hasMultiple ? 'flex' : 'none';
        if (navNext) navNext.style.display = hasMultiple ? 'flex' : 'none';
        if (pillBox) pillBox.style.display = hasMultiple ? 'flex' : 'none';
        if (counterPill) counterPill.textContent = (currentIndex + 1);
        if (totalCountPill) totalCountPill.textContent = images.length;

        // 4. Update Lightbox Thumbs Container
        if (lightboxThumbContainer) {
            lightboxThumbContainer.innerHTML = images.map((url, i) => `
                <button type="button" 
                        class="lightbox-thumb ${i === currentIndex ? 'active' : ''}" 
                        data-index="${i}"
                        onclick="selectLightboxImage(${i})">
                    <img src="${url}" alt="Thumbnail ${i + 1}">
                </button>
            `).join('');
        }
        if (lightboxImg) lightboxImg.src = initialSrc;
        if (lightboxCounter) lightboxCounter.textContent = `${currentIndex + 1} / ${images.length}`;
    }

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
    if (mainStage) {
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
            if (!images || images.length <= 1) return;
            const swipeDistance = touchEndX - touchStartX;
            if (Math.abs(swipeDistance) > 40) {
                if (swipeDistance < 0) {
                    navigateGallery(1);
                } else {
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
        if (!lightboxImg || !images || images.length === 0) return;
        lightboxImg.src = images[currentIndex];
        if (lightboxCounter) {
            lightboxCounter.textContent = `${currentIndex + 1} / ${images.length}`;
        }
        const lbThumbs = document.querySelectorAll('.lightbox-thumb');
        lbThumbs.forEach((t, i) => {
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

        // Instantly switch gallery images to this color's specific photos!
        if (colorGalleries && colorGalleries[colorName] && colorGalleries[colorName].length > 0) {
            images = colorGalleries[colorName];
            rebuildGalleryUI(0);
        } else if (images && images.length > 1) {
            // Fallback: jump to matched photo in global images if available
            const lowerColor = colorName.toLowerCase();
            let targetIndex = -1;
            for (let i = 0; i < images.length; i++) {
                if (images[i].toLowerCase().includes(lowerColor)) {
                    targetIndex = i;
                    break;
                }
            }
            if (targetIndex >= 0) {
                selectGalleryImage(targetIndex);
            }
        }
    };

    // ─── Size Option Selection ──────────────────────────────────
    window.selectProductSize = function(btn, sizeVal) {
        document.querySelectorAll('.size-pill-option').forEach(el => {
            el.classList.remove('active');
            el.style.background = '#ffffff';
            el.style.color = '#000000';
            el.style.borderColor = '#e5e5e5';
        });

        btn.classList.add('active');
        btn.style.background = '#000000';
        btn.style.color = '#ffffff';
        btn.style.borderColor = '#000000';

        const label = document.getElementById('selected-size-label');
        if (label) {
            label.textContent = sizeVal;
        }

        const input = document.getElementById('product-selected-size-input');
        if (input) {
            input.value = sizeVal;
        }
    };

    // ─── Quantity Stepper & Dynamic Total Calculation ──────────
    function updateAddToBagPrice(quantity) {
        const priceEl = document.getElementById('pdp-add-to-bag-price');
        if (!priceEl) return;
        const total = (unitPrice * quantity).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        priceEl.textContent = `${currencySymbol}${total}`;
    }

    window.incrementQty = function(max) {
        const input = document.getElementById('pdp-quantity-input');
        if (!input) return;
        let val = parseInt(input.value, 10) || 1;
        if (!max || val < max) {
            val = val + 1;
            input.value = val;
            updateAddToBagPrice(val);
        }
    };

    window.decrementQty = function() {
        const input = document.getElementById('pdp-quantity-input');
        if (!input) return;
        let val = parseInt(input.value, 10) || 1;
        if (val > 1) {
            val = val - 1;
            input.value = val;
            updateAddToBagPrice(val);
        }
    };

    const qtyInput = document.getElementById('pdp-quantity-input');
    if (qtyInput) {
        qtyInput.addEventListener('input', function() {
            let val = parseInt(this.value, 10);
            if (isNaN(val) || val < 1) val = 1;
            updateAddToBagPrice(val);
        });
        qtyInput.addEventListener('change', function() {
            let val = parseInt(this.value, 10);
            if (isNaN(val) || val < 1) val = 1;
            this.value = val;
            updateAddToBagPrice(val);
        });
    }

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



