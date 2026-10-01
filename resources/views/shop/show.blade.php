@extends('layouts.app')

@push('styles')
<style>
/* ─── PDP Mobile Optimization Styles ────────────────────────────────── */
.pdp-accordions {
    border-top: 1px solid #e5e5e5;
    margin-bottom: 24px;
    width: 100%;
}
.pdp-accordion-item {
    border-bottom: 1px solid #e5e5e5;
}
.pdp-accordion-header {
    width: 100%;
    padding: 16px 0;
    background: transparent;
    border: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    text-align: left;
    transition: opacity 0.15s ease;
}
.pdp-accordion-header:hover {
    opacity: 0.7;
}
.pdp-accordion-title {
    font-family: 'Inter', sans-serif;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #000000;
}
.accordion-icon {
    font-size: 1.15rem;
    color: #737373;
    font-weight: 300;
    transition: transform 0.2s ease;
    line-height: 1;
}
.pdp-accordion-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.28s cubic-bezier(0.4, 0, 0.2, 1);
}
.pdp-accordion-inner {
    padding-bottom: 18px;
    font-size: 0.85rem;
    color: #525252;
    line-height: 1.65;
}
.pdp-accordion-inner ul {
    margin: 0;
    padding-left: 18px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

/* Mobile Sticky Bottom Bar */
.pdp-mobile-sticky-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border-top: 1px solid #e5e5e5;
    padding: 10px 16px;
    z-index: 999;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    transform: translateY(105%);
    transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.08);
}
.pdp-mobile-sticky-bar.is-visible {
    transform: translateY(0);
}
.pdp-sticky-left {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
    flex: 1;
}
.pdp-sticky-thumb {
    width: 38px;
    height: 48px;
    object-fit: cover;
    border-radius: 2px;
    border: 1px solid #e5e5e5;
    background: #fafafa;
    flex-shrink: 0;
}
.pdp-sticky-details {
    min-width: 0;
    flex: 1;
}
.pdp-sticky-name {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.05rem;
    font-weight: 600;
    color: #000000;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
    line-height: 1.15;
}
.pdp-sticky-sub {
    font-size: 0.72rem;
    color: #737373;
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 2px;
}
.pdp-sticky-dot {
    opacity: 0.4;
}
.pdp-sticky-price {
    font-weight: 700;
    color: #000000;
    margin-left: 2px;
}
.pdp-sticky-cta {
    height: 44px;
    padding: 0 18px;
    font-family: 'Inter', sans-serif;
    font-size: 0.76rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    background: #000000;
    color: #ffffff;
    border: none;
    border-radius: 2px;
    cursor: pointer;
    flex-shrink: 0;
    white-space: nowrap;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
.pdp-sticky-cta:active {
    background: #262626;
    transform: scale(0.98);
}

@media (min-width: 769px) {
    .pdp-mobile-sticky-bar {
        display: none !important;
    }
}

/* Mobile Gallery & Viewport Optimizations */
@media (max-width: 768px) {
    .product-detail {
        grid-template-columns: 1fr !important;
        gap: 24px !important;
        padding: 12px 0 48px 0 !important;
    }
    .product-gallery-layout {
        flex-direction: column-reverse !important;
        gap: 10px !important;
        position: relative !important;
        top: 0 !important;
    }
    .main-image-stage {
        height: auto !important;
        aspect-ratio: 4/5 !important;
        max-height: 72vh !important;
        min-height: 380px !important;
        width: 100% !important;
        border-radius: 4px !important;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04) !important;
    }
    .gallery-nav-btn {
        opacity: 0.92 !important;
        width: 38px !important;
        height: 38px !important;
        background: rgba(255, 255, 255, 0.92) !important;
        border-radius: 50% !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12) !important;
        display: flex !important;
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
    }
    .gallery-nav-btn.prev-btn { left: 8px !important; }
    .gallery-nav-btn.next-btn { right: 8px !important; }

    .gallery-thumbs-rail {
        width: 100% !important;
        flex-direction: row !important;
        justify-content: flex-start !important;
        margin-top: 4px !important;
    }
    .thumb-rail-btn {
        display: none !important;
    }
    .thumbs-rail-track {
        flex-direction: row !important;
        max-height: none !important;
        max-width: 100% !important;
        overflow-x: auto !important;
        scroll-snap-type: x mandatory !important;
        -webkit-overflow-scrolling: touch !important;
        gap: 10px !important;
        padding: 2px 2px 6px 2px !important;
        scrollbar-width: none !important;
    }
    .thumbs-rail-track::-webkit-scrollbar {
        display: none !important;
    }
    .thumb-item {
        width: 64px !important;
        height: 82px !important;
        flex-shrink: 0 !important;
        scroll-snap-align: start !important;
        border-radius: 3px !important;
    }
    .gallery-counter-pill {
        bottom: 12px !important;
        right: 12px !important;
        padding: 3px 10px !important;
        border-radius: 12px !important;
        font-size: 0.68rem !important;
        background: rgba(0, 0, 0, 0.78) !important;
    }
    .gallery-zoom-hint {
        top: 12px !important;
        right: 12px !important;
        width: 36px !important;
        height: 36px !important;
        border-radius: 50% !important;
        background: rgba(255, 255, 255, 0.9) !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
    }
}

@media (max-width: 480px) {
    .product-action-row {
        gap: 8px !important;
    }
    .quantity-stepper {
        height: 48px !important;
    }
    .add-to-bag-button {
        height: 48px !important;
        font-size: 0.78rem !important;
        letter-spacing: 0.12em !important;
    }
}
</style>
@endpush

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

            {{-- Product Purchase Form (Placed prominently before description for mobile ease) --}}
            @if($product->is_in_stock)
                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form" style="display:flex;flex-direction:column;gap:0;margin-bottom:28px;width:100%;">
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
                            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
                                <span style="font-family:'Inter',sans-serif;font-size:0.75rem;letter-spacing:0.18em;text-transform:uppercase;color:#000000;padding-top:2px;">
                                    SIZE &mdash; <strong id="selected-size-label" style="font-weight:600;letter-spacing:0.1em;">{{ $product->sizes_list[0] }}</strong>
                                </span>
                                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:5px;text-align:right;">
                                    <span style="font-size:0.68rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;line-height:1.2;">
                                        {{ $activeSizeGuide && $activeSizeGuide->fit_type ? strtoupper($activeSizeGuide->fit_type) : 'STANDARD FIT' }}
                                    </span>
                                    @if(isset($allSizeGuides) && $allSizeGuides->count() > 0)
                                        <button type="button" 
                                                onclick="openSizeGuideModal()" 
                                                class="size-guide-trigger-btn"
                                                title="View Size Guide"
                                                style="background:none;border:none;padding:0;font-size:0.75rem;color:#171717;cursor:pointer;display:inline-flex;align-items:center;gap:6px;font-family:'Inter',sans-serif;font-weight:500;text-decoration:underline;text-underline-offset:3px;transition:opacity 0.15s;" 
                                                onmouseover="this.style.opacity='0.65'" 
                                                onmouseout="this.style.opacity='1'">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="2" y="7" width="20" height="10" rx="1"/>
                                                <path d="M6 7v4M10 7v3M14 7v4M18 7v3"/>
                                            </svg>
                                            <span>Size Guide</span>
                                        </button>
                                    @endif
                                </div>
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
                <button class="btn btn-secondary btn-lg w-100" disabled style="border-radius:0;letter-spacing:0.15em;font-size:0.8rem;margin-bottom:24px;">SOLD OUT</button>
            @endif

            {{-- Product Editorial Description --}}
            @if($product->description)
                <div style="font-size:0.9rem;line-height:1.75;color:#525252;margin-bottom:28px;">
                    {!! nl2br(e($product->description)) !!}
                </div>
            @endif

            {{-- Luxury Expandable Accordions --}}
            <div class="pdp-accordions" style="border-top:1px solid #e5e5e5;margin-bottom:24px;width:100%;">
                {{-- Details & Fit --}}
                <div class="pdp-accordion-item">
                    <button type="button" class="pdp-accordion-header" onclick="togglePdpAccordion(this)">
                        <span class="pdp-accordion-title">Details & Fit</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="pdp-accordion-content">
                        <div class="pdp-accordion-inner">
                            <ul>
                                <li>Heavyweight 390 GSM premium cotton fleece fabrication</li>
                                <li>Vintage pigment dye treatment for deep, washed texture</li>
                                <li>Relaxed 90s fit with dropped shoulders and structured drape</li>
                                <li>Rib-knit collar, cuffs, and hem with reinforced needle stitching</li>
                                <li>Signature archival branding and functional kangaroo pocket</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Shipping & Complimentary Returns --}}
                <div class="pdp-accordion-item">
                    <button type="button" class="pdp-accordion-header" onclick="togglePdpAccordion(this)">
                        <span class="pdp-accordion-title">Shipping & Returns</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="pdp-accordion-content">
                        <div class="pdp-accordion-inner">
                            <p style="margin:0 0 8px 0;">All orders are dispatched from our atelier within 24–48 hours with full tracking details sent via email.</p>
                            <p style="margin:0;">Complimentary exchanges and returns are accepted within 14 days of delivery. Items must be in original unworn condition with tags attached.</p>
                        </div>
                    </div>
                </div>

                {{-- Garment Care --}}
                <div class="pdp-accordion-item">
                    <button type="button" class="pdp-accordion-header" onclick="togglePdpAccordion(this)">
                        <span class="pdp-accordion-title">Garment Care</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="pdp-accordion-content">
                        <div class="pdp-accordion-inner">
                            <p style="margin:0 0 6px 0;">Machine wash cold inside-out on gentle cycle with like colors.</p>
                            <p style="margin:0 0 6px 0;">Do not bleach. Lay flat to dry or tumble dry on lowest temperature.</p>
                            <p style="margin:0;">Cool iron on reverse if necessary; do not iron directly on graphic accents.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Buy on Shopify --}}
            @if($shopifyUrl)
                <div style="display:flex;align-items:center;gap:12px;padding:16px;border:1px solid #e5e5e5;margin-bottom:16px;">
                    <span style="font-size:0.75rem;color:#737373;letter-spacing:0.05em;text-transform:uppercase;">Also available on Shopify</span>
                    <a href="{{ $shopifyUrl }}" target="_blank" style="margin-left:auto;font-size:0.75rem;color:#000000;letter-spacing:0.1em;text-transform:uppercase;font-weight:600;">PURCHASE →</a>
                </div>
            @endif

            {{-- Synced badge --}}
            @if($product->is_synced_to_shopify)
                <div>
                    <span class="badge badge-success" style="font-size:0.65rem;letter-spacing:0.1em;">✓ SYNCED TO SHOPIFY</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Mobile Sticky Bottom Bar (Appears on scroll past Add to Bag on mobile) --}}
    @if($product->is_in_stock)
    <div class="pdp-mobile-sticky-bar" id="pdp-mobile-sticky-bar">
        <div class="pdp-sticky-left">
            @if($totalImages > 0)
                <img src="{{ $galleryImages[0] }}" alt="{{ $product->name }}" id="pdp-sticky-thumb" class="pdp-sticky-thumb">
            @endif
            <div class="pdp-sticky-details">
                <span class="pdp-sticky-name">{{ $product->name }}</span>
                <div class="pdp-sticky-sub">
                    <span id="pdp-sticky-color-val">{{ $defaultColor ?? 'Selected' }}</span>
                    @if(!empty($product->sizes_list)) <span class="pdp-sticky-dot">·</span> @endif
                    <span id="pdp-sticky-size-val">{{ $product->sizes_list[0] ?? '' }}</span>
                    <span class="pdp-sticky-dot">·</span>
                    <span class="pdp-sticky-price" id="pdp-sticky-price-val">{{ $product->formatted_price }}</span>
                </div>
            </div>
        </div>
        <button type="button" class="pdp-sticky-cta" onclick="submitMainAddToCart()">
            ADD TO BAG
        </button>
    </div>
    @endif

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

        const stickyColor = document.getElementById('pdp-sticky-color-val');
        if (stickyColor) {
            stickyColor.textContent = colorName;
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

        const stickySize = document.getElementById('pdp-sticky-size-val');
        if (stickySize) {
            stickySize.textContent = sizeVal;
        }
    };

    // ─── Quantity Stepper & Dynamic Total Calculation ──────────
    function updateAddToBagPrice(quantity) {
        const priceEl = document.getElementById('pdp-add-to-bag-price');
        const formattedTotal = (unitPrice * quantity).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        const fullPriceStr = `${currencySymbol}${formattedTotal}`;

        if (priceEl) {
            priceEl.textContent = fullPriceStr;
        }

        const stickyPrice = document.getElementById('pdp-sticky-price-val');
        if (stickyPrice) {
            stickyPrice.textContent = fullPriceStr;
        }
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
        if (e.key === 'Escape') {
            const sizeGuideModal = document.getElementById('size-guide-modal');
            if (sizeGuideModal && sizeGuideModal.style.display === 'flex') {
                closeSizeGuideModal();
            } else if (lightbox && lightbox.classList.contains('active')) {
                closeLightbox();
            }
        } else if (e.key === 'ArrowLeft') {
            navigateGallery(-1);
        } else if (e.key === 'ArrowRight') {
            navigateGallery(1);
        }
    });
})();

// ─── Accordion Toggle ───────────────────────────────────────
window.togglePdpAccordion = function(button) {
    const item = button.closest('.pdp-accordion-item');
    if (!item) return;
    const content = item.querySelector('.pdp-accordion-content');
    const icon = button.querySelector('.accordion-icon');
    const isOpen = item.classList.contains('active');

    document.querySelectorAll('.pdp-accordion-item').forEach(other => {
        if (other !== item) {
            other.classList.remove('active');
            const otherContent = other.querySelector('.pdp-accordion-content');
            const otherIcon = other.querySelector('.accordion-icon');
            if (otherContent) otherContent.style.maxHeight = '0px';
            if (otherIcon) otherIcon.textContent = '+';
        }
    });

    if (isOpen) {
        item.classList.remove('active');
        if (content) content.style.maxHeight = '0px';
        if (icon) icon.textContent = '+';
    } else {
        item.classList.add('active');
        if (content) content.style.maxHeight = content.scrollHeight + 'px';
        if (icon) icon.textContent = '−';
    }
};

// ─── Mobile Sticky Bar Action & Scroll Trigger ──────────────
window.submitMainAddToCart = function() {
    const form = document.querySelector('.add-to-cart-form');
    if (form) {
        form.requestSubmit ? form.requestSubmit() : form.submit();
    }
};

(function() {
    const stickyBar = document.getElementById('pdp-mobile-sticky-bar');
    const mainBtn = document.querySelector('.add-to-bag-button');
    if (stickyBar && mainBtn) {
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    if (window.innerWidth <= 768) {
                        const rect = mainBtn.getBoundingClientRect();
                        if (rect.bottom < 40) {
                            stickyBar.classList.add('is-visible');
                        } else {
                            stickyBar.classList.remove('is-visible');
                        }
                    } else {
                        stickyBar.classList.remove('is-visible');
                    }
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }
})();

// Size Guide Modal Logic
const sizeGuidesList = {!! json_encode($sizeGuidesData ?? []) !!};
let currentGuideId = {{ $initialGuideId ?? 0 }};
let currentUnit = 'cm'; // 'cm' or 'in'

window.openSizeGuideModal = function() {
    const modal = document.getElementById('size-guide-modal');
    if (!modal) return;
    renderActiveSizeGuide();
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
};

window.closeSizeGuideModal = function(event) {
    if (event && event.target && event.target.id !== 'size-guide-modal' && event.target.tagName !== 'BUTTON') {
        return;
    }
    const modal = document.getElementById('size-guide-modal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
};

window.switchSizeGuideTab = function(guideId) {
    currentGuideId = guideId;
    document.querySelectorAll('.size-guide-tab-btn').forEach(btn => {
        if (parseInt(btn.getAttribute('data-guide-id'), 10) === guideId) {
            btn.style.background = '#171717';
            btn.style.color = '#ffffff';
            btn.style.borderColor = '#171717';
        } else {
            btn.style.background = '#ffffff';
            btn.style.color = '#404040';
            btn.style.borderColor = '#e5e5e5';
        }
    });
    renderActiveSizeGuide();
};

window.toggleSizeGuideUnit = function() {
    setSizeGuideUnit(currentUnit === 'cm' ? 'in' : 'cm');
};

window.setSizeGuideUnit = function(unit) {
    currentUnit = unit;
    const knob = document.getElementById('unit-toggle-knob');
    const labelInch = document.getElementById('unit-label-inch');
    const labelCm = document.getElementById('unit-label-cm');

    if (unit === 'in') {
        if (knob) knob.style.left = '3px';
        if (labelInch) { labelInch.style.fontWeight = '700'; labelInch.style.color = '#000000'; }
        if (labelCm) { labelCm.style.fontWeight = '500'; labelCm.style.color = '#737373'; }
    } else {
        if (knob) knob.style.left = '19px';
        if (labelInch) { labelInch.style.fontWeight = '500'; labelInch.style.color = '#737373'; }
        if (labelCm) { labelCm.style.fontWeight = '700'; labelCm.style.color = '#000000'; }
    }
    renderActiveSizeGuide();
};

function renderActiveSizeGuide() {
    if (!sizeGuidesList || sizeGuidesList.length === 0) return;
    const guide = sizeGuidesList.find(g => g.id === currentGuideId) || sizeGuidesList[0];
    if (!guide) return;

    const titleEl = document.getElementById('modal-active-guide-title');
    const descEl = document.getElementById('modal-active-guide-desc');
    if (titleEl) titleEl.textContent = guide.name + (guide.fit_type ? ' (' + guide.fit_type + ')' : '');
    if (descEl) descEl.textContent = guide.description || ('Garment measurements in ' + (currentUnit === 'cm' ? 'centimeters' : 'inches') + '.');

    const thead = document.getElementById('size-guide-modal-thead');
    const tbody = document.getElementById('size-guide-modal-tbody');
    if (!thead || !tbody) return;

    const sizes = guide.sizes || [];
    let headHtml = `<tr style="background:#fdfbf7;border-bottom:1px solid #e5e5e5;">
        <th style="padding:12px 16px;text-align:left;font-family:'Inter',sans-serif;font-size:0.75rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#27272a;">Measurement (${currentUnit.toUpperCase()})</th>`;
    sizes.forEach(sz => {
        headHtml += `<th style="padding:12px 14px;font-family:'Inter',sans-serif;font-size:0.75rem;font-weight:700;letter-spacing:0.06em;color:#18181b;">${sz}</th>`;
    });
    headHtml += `</tr>`;
    thead.innerHTML = headHtml;

    const rows = guide.measurements || [];
    let bodyHtml = '';
    rows.forEach((row, idx) => {
        const bg = idx % 2 === 0 ? '#ffffff' : '#fafafa';
        bodyHtml += `<tr style="background:${bg};border-bottom:1px solid #f0f0f0;">
            <td style="padding:12px 18px;text-align:left;font-weight:600;color:#18181b;font-size:0.83rem;">${row.name}</td>`;
        sizes.forEach(sz => {
            let val = (currentUnit === 'in' ? (row.inch ? row.inch[sz] : null) : (row.cm ? row.cm[sz] : null));
            if (val === undefined || val === null) {
                val = (row.values && row.values[sz] !== undefined) ? row.values[sz] : '-';
            }
            bodyHtml += `<td style="padding:12px 14px;color:#27272a;font-family:'Inter',monospace;font-size:0.85rem;text-align:center;">${val}</td>`;
        });
        bodyHtml += `</tr>`;
    });
    tbody.innerHTML = bodyHtml;
}

// Pre-render table on load
renderActiveSizeGuide();
</script>
@endpush

{{-- Size Guide Modal --}}
@if(isset($allSizeGuides) && $allSizeGuides->count() > 0)
<div id="size-guide-modal" 
     class="size-guide-modal-overlay" 
     style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.68);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);z-index:9999;align-items:center;justify-content:center;padding:16px;" 
     onclick="closeSizeGuideModal(event)">
    
    <div class="size-guide-modal-dialog" 
         style="background:#ffffff;border-radius:12px;max-width:720px;width:100%;max-height:90vh;display:flex;flex-direction:column;overflow:hidden;box-shadow:0 25px 60px -15px rgba(0,0,0,0.4);position:relative;" 
         onclick="event.stopPropagation()">
        
        {{-- Modal Header --}}
        <div style="padding:18px 24px;border-bottom:1px solid #ebebeb;display:flex;align-items:center;justify-content:space-between;background:#fafafa;">
            <div>
                <h3 style="margin:0;font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:700;color:#171717;letter-spacing:0.02em;">Size Guide</h3>
                <span style="font-size:0.75rem;color:#737373;letter-spacing:0.04em;">Official Pistis sizing & dimensions</span>
            </div>
            <button type="button" 
                    onclick="closeSizeGuideModal()" 
                    style="background:none;border:none;font-size:1.5rem;line-height:1;cursor:pointer;color:#737373;padding:4px 8px;border-radius:4px;transition:all 0.15s;" 
                    onmouseover="this.style.color='#000';this.style.background='#ebebeb'" 
                    onmouseout="this.style.color='#737373';this.style.background='none'" 
                    title="Close">✕</button>
        </div>

        {{-- Modal Body --}}
        <div style="padding:22px 24px;overflow-y:auto;flex:1;">
            {{-- Category / Range Tabs (like Image 1) --}}
            @if(count($sizeGuidesData) > 1)
                <div id="size-guide-tabs" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px;">
                    @foreach($sizeGuidesData as $gItem)
                        <button type="button" 
                                class="size-guide-tab-btn {{ $gItem['id'] == $initialGuideId ? 'active' : '' }}" 
                                data-guide-id="{{ $gItem['id'] }}" 
                                onclick="switchSizeGuideTab({{ $gItem['id'] }})"
                                style="cursor:pointer;padding:8px 18px;border-radius:4px;font-family:'Inter',sans-serif;font-size:0.78rem;font-weight:600;letter-spacing:0.04em;transition:all 0.18s ease;{{ $gItem['id'] == $initialGuideId ? 'background:#171717;color:#ffffff;border:1px solid #171717;' : 'background:#ffffff;color:#404040;border:1px solid #e5e5e5;' }}">
                            {{ $gItem['name'] }}
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- Sub-header with Active Guide details and INCH / CM Toggle (like Image 1) --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:12px;">
                <div>
                    <h4 id="modal-active-guide-title" style="margin:0;font-size:0.95rem;font-weight:700;color:#171717;"></h4>
                    <p id="modal-active-guide-desc" style="margin:3px 0 0;font-size:0.75rem;color:#737373;max-width:440px;"></p>
                </div>

                {{-- Interactive INCH / CM Switch (Exact replica of Image 1) --}}
                <div style="display:inline-flex;align-items:center;gap:10px;background:#f5f5f5;padding:5px 14px;border-radius:30px;user-select:none;border:1px solid #e5e5e5;">
                    <span id="unit-label-inch" style="font-size:0.75rem;font-weight:600;color:#737373;cursor:pointer;transition:color 0.15s;" onclick="setSizeGuideUnit('in')">INCH</span>
                    <div id="unit-toggle-switch" onclick="toggleSizeGuideUnit()" style="width:38px;height:22px;background:#171717;border-radius:12px;position:relative;cursor:pointer;transition:background 0.2s;" title="Switch INCH / CM">
                        <div id="unit-toggle-knob" style="width:16px;height:16px;background:#ffffff;border-radius:50%;position:absolute;top:3px;left:19px;transition:left 0.2s ease;box-shadow:0 1px 3px rgba(0,0,0,0.3);"></div>
                    </div>
                    <span id="unit-label-cm" style="font-size:0.75rem;font-weight:700;color:#000000;cursor:pointer;transition:color 0.15s;" onclick="setSizeGuideUnit('cm')">CM</span>
                </div>
            </div>

            {{-- Dynamic Sizing Table --}}
            <div style="border:1px solid #e5e5e5;border-radius:6px;overflow-x:auto;-webkit-overflow-scrolling:touch;background:#ffffff;">
                <table id="size-guide-modal-table" style="width:100%;border-collapse:collapse;font-size:0.83rem;text-align:center;">
                    <thead id="size-guide-modal-thead">
                        {{-- Injected dynamically --}}
                    </thead>
                    <tbody id="size-guide-modal-tbody">
                        {{-- Injected dynamically --}}
                    </tbody>
                </table>
            </div>

            {{-- Measuring Advice Note --}}
            <div style="margin-top:18px;padding:12px 16px;background:#fafafa;border-radius:6px;border:1px solid #ebebeb;display:flex;align-items:flex-start;gap:12px;">
                <span style="font-size:1.2rem;line-height:1;">📐</span>
                <div style="font-size:0.75rem;color:#525252;line-height:1.5;">
                    <strong>Measuring Tips:</strong> Measurements refer to garment dimensions laid flat. For standard fit, select your usual size. For an oversized drape, we suggest choosing one size up.
                </div>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div style="padding:14px 24px;border-top:1px solid #ebebeb;display:flex;align-items:center;justify-content:space-between;background:#ffffff;">
            <span style="font-size:0.75rem;color:#a3a3a3;">Pistis Garment Archive · Crafted to Exact Proportions</span>
            <button type="button" onclick="closeSizeGuideModal()" class="btn btn-sm btn-secondary" style="font-size:0.8rem;padding:6px 18px;">Close</button>
        </div>
    </div>
</div>
@endif
@endsection



