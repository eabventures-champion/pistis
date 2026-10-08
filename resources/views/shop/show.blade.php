@extends('layouts.app')

@php
    $ogImage = $product->primary_image_url ?? (!empty($product->image_urls) ? $product->image_urls[0] : asset('images/placeholder.jpg'));
    $ogDesc = Str::limit(strip_tags($product->description ?? 'Discover this piece from the Pistis archive.'), 155);
@endphp

@section('meta')
    <meta property="og:title" content="{{ $product->name }} — {{ $store_name ?? 'PISTIS' }}">
    <meta property="og:description" content="{{ $ogDesc }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="product">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $product->name }} — {{ $store_name ?? 'PISTIS' }}">
    <meta name="twitter:description" content="{{ $ogDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
@endsection

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
        gap: 20px !important;
        padding: 8px 0 60px 0 !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .product-detail-info {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    .product-gallery-wrapper {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    .product-gallery-layout {
        flex-direction: column-reverse !important;
        gap: 10px !important;
        position: relative !important;
        top: 0 !important;
        width: 100% !important;
    }
    .main-image-stage {
        height: auto !important;
        aspect-ratio: 4/5 !important;
        max-height: 65vh !important;
        min-height: 280px !important;
        width: 100% !important;
        border-radius: 3px !important;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04) !important;
        background: #f7f7f7 !important;
        overflow: hidden !important;
    }
    .gallery-current-img {
        width: 100% !important;
        height: 100% !important;
        object-fit: contain !important;
        object-position: center !important;
        display: block !important;
    }
    .zoom-magnifier {
        display: none !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }
    .gallery-nav-btn {
        opacity: 0.92 !important;
        width: 36px !important;
        height: 36px !important;
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
        width: 60px !important;
        height: 76px !important;
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
        width: 34px !important;
        height: 34px !important;
        border-radius: 50% !important;
        background: rgba(255, 255, 255, 0.9) !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
    }
}

@media (max-width: 480px) {
    .share-btn-text-full { display: none !important; }
    .share-btn-text-short { display: inline !important; }

    .color-swatches-container {
        gap: 6px !important;
        width: 100% !important;
    }
    .color-swatch-option {
        padding: 6px 11px !important;
        gap: 6px !important;
        box-sizing: border-box !important;
    }
    .color-swatch-option .swatch-circle {
        width: 14px !important;
        height: 14px !important;
    }
    .color-swatch-option .swatch-name {
        font-size: 0.7rem !important;
        letter-spacing: 0.05em !important;
    }
    .color-swatch-option .swatch-price {
        font-size: 0.66rem !important;
    }

    .size-options-container {
        gap: 8px !important;
        width: 100% !important;
    }
    .size-pill-option {
        min-width: 42px !important;
        height: 42px !important;
        padding: 0 12px !important;
        font-size: 0.75rem !important;
    }

    .product-action-row {
        gap: 8px !important;
        width: 100% !important;
        align-items: stretch !important;
    }
    .quantity-stepper {
        height: 46px !important;
        flex-shrink: 0 !important;
    }
    .quantity-stepper button {
        width: 32px !important;
        font-size: 1rem !important;
    }
    .quantity-stepper .qty-input {
        width: 34px !important;
        font-size: 0.88rem !important;
    }
    .add-to-bag-button {
        height: 46px !important;
        padding: 0 8px !important;
        font-size: 0.74rem !important;
        letter-spacing: 0.08em !important;
        gap: 6px !important;
        min-width: 0 !important;
        flex: 1 1 auto !important;
        white-space: nowrap !important;
    }
    .add-to-bag-button span {
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
    }
    .btn-share-square-action {
        width: 46px !important;
        height: 46px !important;
        flex-shrink: 0 !important;
    }
}

@media (max-width: 350px) {
    .product-action-row {
        flex-wrap: wrap !important;
    }
    .quantity-stepper {
        width: 100% !important;
        justify-content: center !important;
    }
    .add-to-bag-button {
        flex: 1 !important;
    }
}

/* ─── Luxury Share Triggers & Modal ────────────────────────────────── */
.pdp-share-trigger-btn {
    background: transparent;
    border: none;
    padding: 4px 0;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'Inter', sans-serif;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: #171717;
    cursor: pointer;
    transition: all 0.2s ease;
    border-bottom: 1px solid transparent;
}
.pdp-share-trigger-btn:hover {
    color: #000000;
    border-bottom-color: #000000;
    opacity: 0.8;
}
.pdp-share-trigger-btn svg {
    transition: transform 0.2s ease;
}
.pdp-share-trigger-btn:hover svg {
    transform: scale(1.1);
}

.btn-share-square-action {
    width: 50px;
    height: 50px;
    background: #ffffff;
    border: 1px solid #171717;
    color: #171717;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.btn-share-square-action:hover {
    background: #000000;
    color: #ffffff;
}
@media (max-width: 480px) {
    .btn-share-square-action {
        width: 48px;
        height: 48px;
    }
}

.pdp-sticky-share-btn {
    width: 44px;
    height: 44px;
    background: #ffffff;
    border: 1px solid #e5e5e5;
    color: #171717;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: all 0.15s ease;
}
.pdp-sticky-share-btn:active {
    background: #000000;
    color: #ffffff;
    transform: scale(0.96);
}

/* Modal Overlay & Dialog */
.product-share-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.68);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    padding: 16px;
    opacity: 0;
    transition: opacity 0.25s ease;
}
.product-share-modal-overlay.is-open {
    opacity: 1;
}
.product-share-modal-dialog {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    max-height: 90dvh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    box-shadow: 0 30px 70px -15px rgba(0, 0, 0, 0.45);
    position: relative;
    transform: translateY(16px) scale(0.98);
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.product-share-modal-overlay.is-open .product-share-modal-dialog {
    transform: translateY(0) scale(1);
}
.share-modal-header {
    flex-shrink: 0;
    padding: 18px 22px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fafafa;
}
.share-modal-body {
    flex: 1;
    min-height: 0;
    padding: 20px 24px;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
}
.share-modal-footer {
    flex-shrink: 0;
    padding: 14px 24px;
    border-top: 1px solid #ebebeb;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fafafa;
}
.share-preview-card {
    display: flex;
    gap: 14px;
    padding: 14px;
    background: #fafafa;
    border: 1px solid #ebebeb;
    margin-bottom: 20px;
    align-items: center;
}
.share-preview-thumb {
    width: 60px;
    height: 75px;
    object-fit: cover;
    background: #f5f5f5;
    flex-shrink: 0;
    border: 1px solid #e5e5e5;
}
.share-channels-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 22px;
}
.share-channel-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 14px 10px;
    background: #ffffff;
    border: 1px solid #e5e5e5;
    color: #171717;
    text-decoration: none;
    font-family: 'Inter', sans-serif;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    transition: all 0.18s ease;
    cursor: pointer;
}
.share-channel-btn:hover {
    border-color: #000000;
    background: #000000;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}
.share-channel-btn svg {
    transition: transform 0.18s ease;
}
.share-channel-btn:hover svg {
    transform: scale(1.1);
}
.share-copy-bar {
    display: flex;
    align-items: stretch;
    border: 1px solid #171717;
    position: relative;
    background: #ffffff;
}
.share-copy-input {
    flex: 1;
    min-width: 0;
    border: none;
    padding: 12px 14px;
    font-size: 0.8rem;
    font-family: 'Inter', monospace;
    color: #525252;
    background: transparent;
    outline: none;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.share-copy-btn {
    background: #000000;
    color: #ffffff;
    border: none;
    width: 46px;
    min-width: 46px;
    padding: 0;
    font-family: 'Inter', sans-serif;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s ease, color 0.15s ease;
    flex-shrink: 0;
}
.share-copy-btn:hover {
    background: #262626;
}
.share-copy-btn.copied {
    background: #15803d;
    color: #ffffff;
}
.native-share-trigger {
    width: 100%;
    margin-bottom: 14px;
    padding: 11px 16px;
    background: #000000;
    color: #ffffff;
    border: 1px solid #000000;
    font-family: 'Inter', sans-serif;
    font-size: 0.76rem;
    font-weight: 600;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: background 0.2s ease;
}
.native-share-trigger:hover {
    background: #262626;
}

@media (max-width: 480px) {
    .product-share-modal-overlay {
        padding: 12px;
    }
    .product-share-modal-dialog {
        max-height: 92vh;
        max-height: 92dvh;
    }
    .share-modal-header {
        padding: 12px 16px;
    }
    .share-modal-header h3 {
        font-size: 1.25rem !important;
    }
    .share-modal-body {
        padding: 14px 16px;
    }
    .share-preview-card {
        padding: 8px 10px;
        margin-bottom: 12px;
        gap: 10px;
    }
    .share-preview-thumb {
        width: 46px;
        height: 58px;
    }
    .share-channels-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 6px;
        margin-bottom: 14px;
    }
    .share-channel-btn {
        padding: 8px 4px;
        font-size: 0.62rem;
        letter-spacing: 0.04em;
        gap: 4px;
    }
    .share-channel-btn svg {
        width: 16px;
        height: 16px;
    }
    .native-share-trigger {
        padding: 9px 12px;
        font-size: 0.7rem;
        margin-bottom: 12px;
    }
    .share-copy-input {
        padding: 9px 10px;
        font-size: 0.75rem;
    }
    .share-copy-btn {
        width: 40px;
        min-width: 40px;
    }
    .share-modal-footer {
        padding: 10px 16px;
    }
}
</style>
@endpush

@section('content')
@php
    $defaultColor = !empty($product->colors_list[0]['name']) ? $product->colors_list[0]['name'] : null;
    $initialPrice = $defaultColor ? $product->getPriceForColor($defaultColor) : (float) $product->price;
    $initialComparePrice = $defaultColor ? $product->getComparePriceForColor($defaultColor) : ($product->compare_price ? (float) $product->compare_price : null);
    $initialFormattedPrice = $product->getFormattedPriceForColor($defaultColor);
    $initialFormattedComparePrice = $product->getFormattedComparePriceForColor($defaultColor);

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
                                <img src="{{ $imageUrl }}" alt="{{ $product->name }} thumb {{ $index + 1 }}" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
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
                             loading="eager"
                             onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                        
                        {{-- Zoom Lens Stage --}}
                        <div class="zoom-magnifier" id="zoom-magnifier" style="background-image: url('{{ $galleryImages[0] }}');"></div>

                        {{-- Floating Glass Controls --}}
                        <button type="button" class="gallery-nav-btn prev-btn" id="gallery-nav-prev" onclick="event.stopPropagation(); if(typeof zoomMagnifier !== 'undefined' && zoomMagnifier) zoomMagnifier.style.opacity = '0'; navigateGallery(-1);" aria-label="Previous Image" style="display: {{ $totalImages > 1 ? 'flex' : 'none' }};">
                            ‹
                        </button>
                        <button type="button" class="gallery-nav-btn next-btn" id="gallery-nav-next" onclick="event.stopPropagation(); if(typeof zoomMagnifier !== 'undefined' && zoomMagnifier) zoomMagnifier.style.opacity = '0'; navigateGallery(1);" aria-label="Next Image" style="display: {{ $totalImages > 1 ? 'flex' : 'none' }};">
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
            <div class="pdp-sku-share-row" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;gap:10px;">
                <div class="pdp-sku-badge" style="font-size:0.7rem;color:#a3a3a3;letter-spacing:0.12em;text-transform:uppercase;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;min-width:0;">REF: {{ $product->sku }}</div>
                <button type="button" 
                        class="pdp-share-trigger-btn" 
                        onclick="openProductShareModal()" 
                        title="Share this piece" 
                        aria-label="Share this piece"
                        style="flex-shrink:0;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle>
                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                    </svg>
                    <span class="share-btn-text-full">SHARE PIECE</span>
                    <span class="share-btn-text-short" style="display:none;">SHARE</span>
                </button>
            </div>

            <div style="display:flex;align-items:baseline;gap:12px;margin-bottom:20px;">
                <span id="pdp-display-price" style="font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:300;color:#000000;">{{ $initialFormattedPrice }}</span>
                <span id="pdp-display-compare-price" style="font-size:0.9rem;color:#a3a3a3;text-decoration:line-through;{{ ($initialFormattedComparePrice) ? '' : 'display:none;' }}">{{ $initialFormattedComparePrice ?? '' }}</span>
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
                                            data-price="{{ $color['price'] }}"
                                            data-compare-price="{{ $color['compare_price'] ?? '' }}"
                                            data-formatted-price="{{ $color['formatted_price'] }}"
                                            data-formatted-compare-price="{{ $color['formatted_compare_price'] ?? '' }}"
                                            onclick="selectProductColor(this, '{{ addslashes($color['name']) }}', {{ $index }})"
                                            title="{{ $color['name'] }}"
                                            aria-label="Select {{ $color['name'] }}"
                                            style="cursor:pointer;display:inline-flex;align-items:center;gap:8px;padding:8px 16px;border:1px solid {{ $index === 0 ? '#000000' : '#e5e5e5' }};background:{{ $index === 0 ? '#000000' : '#ffffff' }};color:{{ $index === 0 ? '#ffffff' : '#000000' }};transition:all 0.18s ease;">
                                        <span class="swatch-circle" style="width:16px;height:16px;border-radius:50%;background:{{ $color['code'] }};border:1px solid {{ strtolower($color['code']) === '#ffffff' ? '#d4d4d8' : 'rgba(0,0,0,0.15)' }};display:inline-block;flex-shrink:0;box-shadow:inset 0 1px 2px rgba(0,0,0,0.15);"></span>
                                        <span class="swatch-name" style="font-family:'Inter',sans-serif;font-size:0.75rem;letter-spacing:0.08em;text-transform:uppercase;font-weight:600;">{{ $color['name'] }}</span>
                                        @if($product->has_varying_color_prices)
                                            <span class="swatch-price" style="font-size:0.7rem;opacity:0.8;margin-left:2px;font-weight:500;">
                                                ({{ $color['formatted_price'] }})
                                            </span>
                                        @endif
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
                                        {{ (isset($activeSizeGuide) && $activeSizeGuide && $activeSizeGuide->fit_type) ? strtoupper($activeSizeGuide->fit_type) : 'STANDARD FIT' }}
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
                            <span id="pdp-add-to-bag-price">{{ $initialFormattedPrice }}</span>
                        </button>

                        <button type="button" 
                                class="btn-share-square-action" 
                                onclick="openProductShareModal()" 
                                title="Share this piece" 
                                aria-label="Share this piece">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle>
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                            </svg>
                        </button>
                    </div>
                </form>
            @else
                <div style="display:flex;gap:12px;align-items:center;margin-bottom:24px;width:100%;">
                    <button class="btn btn-secondary btn-lg flex-1" disabled style="border-radius:0;letter-spacing:0.15em;font-size:0.8rem;height:50px;margin-bottom:0;">SOLD OUT</button>
                    <button type="button" 
                            class="btn-share-square-action" 
                            onclick="openProductShareModal()" 
                            title="Share this piece" 
                            aria-label="Share this piece">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle>
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                        </svg>
                    </button>
                </div>
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
                @php
                    $detailsText = trim((string)$product->resolved_details_and_fit);
                    $detailsLines = array_filter(array_map('trim', explode("\n", $detailsText)));
                @endphp
                @if(!empty($detailsText))
                <div class="pdp-accordion-item">
                    <button type="button" class="pdp-accordion-header" onclick="togglePdpAccordion(this)">
                        <span class="pdp-accordion-title">Details & Fit</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="pdp-accordion-content">
                        <div class="pdp-accordion-inner">
                            @if(count($detailsLines) > 1)
                                <ul>
                                    @foreach($detailsLines as $line)
                                        <li>{{ ltrim($line, "-*•\t ") }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p style="margin:0;">{!! nl2br(e($detailsText)) !!}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                {{-- Shipping & Complimentary Returns --}}
                @php
                    $shippingText = trim((string)$product->resolved_shipping_and_returns);
                @endphp
                @if(!empty($shippingText))
                <div class="pdp-accordion-item">
                    <button type="button" class="pdp-accordion-header" onclick="togglePdpAccordion(this)">
                        <span class="pdp-accordion-title">Shipping & Returns</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="pdp-accordion-content">
                        <div class="pdp-accordion-inner" style="line-height:1.7;">
                            {!! nl2br(e($shippingText)) !!}
                        </div>
                    </div>
                </div>
                @endif

                {{-- Garment Care --}}
                @php
                    $careText = trim((string)$product->resolved_garment_care);
                @endphp
                @if(!empty($careText))
                <div class="pdp-accordion-item">
                    <button type="button" class="pdp-accordion-header" onclick="togglePdpAccordion(this)">
                        <span class="pdp-accordion-title">Garment Care</span>
                        <span class="accordion-icon">+</span>
                    </button>
                    <div class="pdp-accordion-content">
                        <div class="pdp-accordion-inner" style="line-height:1.7;">
                            {!! nl2br(e($careText)) !!}
                        </div>
                    </div>
                </div>
                @endif
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
                <img src="{{ $galleryImages[0] }}" alt="{{ $product->name }}" id="pdp-sticky-thumb" class="pdp-sticky-thumb" onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
            @endif
            <div class="pdp-sticky-details">
                <span class="pdp-sticky-name">{{ $product->name }}</span>
                <div class="pdp-sticky-sub">
                    <span id="pdp-sticky-color-val">{{ $defaultColor ?? 'Selected' }}</span>
                    @if(!empty($product->sizes_list)) <span class="pdp-sticky-dot">·</span> @endif
                    <span id="pdp-sticky-size-val">{{ $product->sizes_list[0] ?? '' }}</span>
                    <span class="pdp-sticky-dot">·</span>
                    <span class="pdp-sticky-price" id="pdp-sticky-price-val">{{ $initialFormattedPrice }}</span>
                </div>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
            <button type="button" class="pdp-sticky-cta" onclick="submitMainAddToCart()">
                ADD TO BAG
            </button>
            <button type="button" class="pdp-sticky-share-btn" onclick="openProductShareModal()" title="Share this piece" aria-label="Share this piece">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle>
                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                </svg>
            </button>
        </div>
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
            <img src="{{ $galleryImages[0] }}" alt="{{ $product->name }}" id="lightbox-img" class="lightbox-img" onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
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
                        <img src="{{ $imageUrl }}" alt="Thumbnail {{ $index + 1 }}" onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
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
    let unitPrice = {{ (float) $initialPrice }};
    const currencySymbol = @json(\App\Models\Setting::get('currency_symbol', '$'));
    let images = @json($galleryImages);
    const colorGalleries = @json($product->color_galleries);
    const colorPrices = @json($product->color_prices);
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

        // Reset magnifier immediately
        if (zoomMagnifier) {
            zoomMagnifier.style.opacity = '0';
        }

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
                    zoomMagnifier.style.opacity = '0';
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
        if (zoomMagnifier) {
            zoomMagnifier.style.opacity = '0';
        }
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

    // ─── Luxury Zoom Lens Magnifier (Desktop Only) ──────────────────────
    if (mainStage && zoomMagnifier) {
        mainStage.addEventListener('mousemove', function(e) {
            // Never activate on mobile devices or touch viewports
            if (window.innerWidth <= 768 || window.matchMedia('(max-width: 768px)').matches || ('ontouchstart' in window && !window.matchMedia('(hover: hover)').matches)) {
                zoomMagnifier.style.opacity = '0';
                return;
            }

            // Never activate when hovering over navigation or control buttons
            if (e.target && e.target.closest && e.target.closest('.gallery-nav-btn, .gallery-zoom-hint, .gallery-counter-pill, .thumb-item')) {
                zoomMagnifier.style.opacity = '0';
                return;
            }

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

        mainStage.addEventListener('pointerdown', function(e) {
            if (e.target && e.target.closest && e.target.closest('.gallery-nav-btn, .gallery-zoom-hint, .gallery-counter-pill')) {
                zoomMagnifier.style.opacity = '0';
            }
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

        const shareColor = document.getElementById('share-modal-color-tag');
        if (shareColor) {
            shareColor.textContent = colorName;
        }

        // Color-dependent price update
        const cPriceData = colorPrices && colorPrices[colorName] ? colorPrices[colorName] : null;
        if (cPriceData) {
            unitPrice = parseFloat(cPriceData.price) || unitPrice;
            const mainPriceEl = document.getElementById('pdp-display-price');
            if (mainPriceEl) {
                mainPriceEl.textContent = cPriceData.formatted_price;
            }
            const sharePrice = document.getElementById('share-modal-price-tag');
            if (sharePrice) {
                sharePrice.textContent = cPriceData.formatted_price;
            }
            const compPriceEl = document.getElementById('pdp-display-compare-price');
            if (compPriceEl) {
                if (cPriceData.formatted_compare_price) {
                    compPriceEl.textContent = cPriceData.formatted_compare_price;
                    compPriceEl.style.display = 'inline';
                } else {
                    compPriceEl.textContent = '';
                    compPriceEl.style.display = 'none';
                }
            }
        }

        // Recalculate add to bag button and sticky bar with current quantity & new color unit price
        const qtyInput = document.getElementById('pdp-quantity-input');
        const currentQty = qtyInput ? (parseInt(qtyInput.value, 10) || 1) : 1;
        updateAddToBagPrice(currentQty);

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

// ─── Luxury Product Share Logic ────────────────────────────────────
function openProductShareModal() {
    const modal = document.getElementById('product-share-modal');
    if (!modal) return;

    // Sync current selected color and price dynamically
    const selectedColorInput = document.getElementById('product-selected-color-input');
    const displayPrice = document.getElementById('pdp-display-price');
    const colorTag = document.getElementById('share-modal-color-tag');
    const priceTag = document.getElementById('share-modal-price-tag');
    const thumbImg = document.getElementById('share-modal-preview-thumb');
    const mainImg = document.getElementById('main-product-image');

    if (colorTag && selectedColorInput && selectedColorInput.value) {
        colorTag.textContent = selectedColorInput.value;
    }
    if (priceTag && displayPrice) {
        priceTag.textContent = displayPrice.textContent.trim();
    }
    if (thumbImg && mainImg) {
        thumbImg.src = mainImg.src;
    }

    const currentUrl = window.location.href.split('#')[0];
    const urlInput = document.getElementById('share-product-url-input');
    if (urlInput) {
        urlInput.value = currentUrl;
    }

    // Refresh dynamic channel links with selected color
    updateShareLinks(currentUrl);

    // Toggle native share button display based on browser capability
    const nativeBtn = document.getElementById('native-share-btn-wrap');
    if (nativeBtn) {
        nativeBtn.style.display = (navigator.share) ? 'flex' : 'none';
    }

    modal.style.display = 'flex';
    requestAnimationFrame(() => {
        modal.classList.add('is-open');
    });
    document.body.style.overflow = 'hidden';
}

function closeProductShareModal(e) {
    if (e && e.target && e.target !== e.currentTarget && !e.target.closest('.share-modal-close-trigger')) {
        return;
    }
    const modal = document.getElementById('product-share-modal');
    if (!modal) return;
    modal.classList.remove('is-open');
    setTimeout(() => {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }, 220);
}

function updateShareLinks(url) {
    const productName = @json($product->name);
    const selectedColorInput = document.getElementById('product-selected-color-input');
    const colorPart = (selectedColorInput && selectedColorInput.value) ? ' (' + selectedColorInput.value + ')' : '';
    const shareText = 'Discover ' + productName + colorPart + ' on PISTIS:';
    const encodedUrl = encodeURIComponent(url);
    const encodedText = encodeURIComponent(shareText);

    // WhatsApp
    const wa = document.getElementById('share-btn-whatsapp');
    if (wa) wa.href = 'https://api.whatsapp.com/send?text=' + encodedText + '%20' + encodedUrl;

    // X (Twitter)
    const x = document.getElementById('share-btn-x');
    if (x) x.href = 'https://twitter.com/intent/tweet?text=' + encodedText + '&url=' + encodedUrl;

    // Facebook
    const fb = document.getElementById('share-btn-facebook');
    if (fb) fb.href = 'https://www.facebook.com/sharer/sharer.php?u=' + encodedUrl;

    // Pinterest
    const pin = document.getElementById('share-btn-pinterest');
    const mainImg = document.getElementById('main-product-image');
    const imgSrc = mainImg ? mainImg.src : '';
    if (pin) pin.href = 'https://pinterest.com/pin/create/button/?url=' + encodedUrl + '&media=' + encodeURIComponent(imgSrc) + '&description=' + encodedText;

    // Telegram
    const tg = document.getElementById('share-btn-telegram');
    if (tg) tg.href = 'https://t.me/share/url?url=' + encodedUrl + '&text=' + encodedText;

    // Email
    const mail = document.getElementById('share-btn-email');
    if (mail) mail.href = 'mailto:?subject=' + encodeURIComponent(productName + ' | PISTIS') + '&body=' + encodeURIComponent(shareText + '\n\n' + url);
}

function copyProductShareUrl() {
    const input = document.getElementById('share-product-url-input');
    const btn = document.getElementById('share-copy-btn-action');
    if (!input) return;

    const url = input.value || window.location.href;

    const handleSuccess = function() {
        if (btn) {
            const originalContent = btn.innerHTML;
            btn.classList.add('copied');
            btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
            setTimeout(function() {
                btn.classList.remove('copied');
                btn.innerHTML = originalContent;
            }, 2200);
        }
        if (typeof showToast === 'function') {
            showToast('Product link copied to clipboard ✓');
        }
    };

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(handleSuccess).catch(function() {
            fallbackClipboardCopy(input, handleSuccess);
        });
    } else {
        fallbackClipboardCopy(input, handleSuccess);
    }
}

function fallbackClipboardCopy(input, callback) {
    try {
        input.select();
        input.setSelectionRange(0, 99999);
        document.execCommand('copy');
        callback();
    } catch (err) {
        prompt('Copy this link:', input.value);
    }
}

function triggerNativeShare() {
    const productName = @json($product->name);
    const selectedColorInput = document.getElementById('product-selected-color-input');
    const colorPart = (selectedColorInput && selectedColorInput.value) ? ' (' + selectedColorInput.value + ')' : '';
    const url = window.location.href;

    if (navigator.share) {
        navigator.share({
            title: productName + ' — PISTIS',
            text: 'Discover ' + productName + colorPart + ' on PISTIS',
            url: url
        }).catch(function() {});
    } else {
        copyProductShareUrl();
    }
}

// Global exposure for onclick handlers
window.openProductShareModal = openProductShareModal;
window.closeProductShareModal = closeProductShareModal;
window.copyProductShareUrl = copyProductShareUrl;
window.triggerNativeShare = triggerNativeShare;

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const shareModal = document.getElementById('product-share-modal');
        if (shareModal && shareModal.classList.contains('is-open')) {
            closeProductShareModal();
        }
    }
});
</script>
@endpush

{{-- Luxury Product Share Modal --}}
<div id="product-share-modal" 
     class="product-share-modal-overlay" 
     onclick="closeProductShareModal(event)"
     aria-modal="true" 
     role="dialog"
     aria-labelledby="share-modal-title">
    
    <div class="product-share-modal-dialog" onclick="event.stopPropagation()">
        {{-- Modal Header --}}
        <div class="share-modal-header">
            <div>
                <h3 id="share-modal-title" style="margin:0;font-family:'Cormorant Garamond',serif;font-size:1.45rem;font-weight:700;color:#171717;letter-spacing:0.02em;line-height:1.2;">Share Piece</h3>
                <span style="font-size:0.75rem;color:#737373;letter-spacing:0.03em;">Share bespoke elegance with friends or save for later</span>
            </div>
            <button type="button" 
                    class="share-modal-close-trigger"
                    onclick="closeProductShareModal()" 
                    style="background:none;border:none;font-size:1.4rem;line-height:1;cursor:pointer;color:#737373;padding:4px 8px;border-radius:4px;transition:all 0.15s;" 
                    onmouseover="this.style.color='#000';this.style.background='#ebebeb'" 
                    onmouseout="this.style.color='#737373';this.style.background='none'" 
                    title="Close share dialog"
                    aria-label="Close share dialog">✕</button>
        </div>

        {{-- Modal Body --}}
        <div class="share-modal-body">
            {{-- Product Preview Card --}}
            <div class="share-preview-card">
                <img src="{{ $totalImages > 0 ? $galleryImages[0] : asset('images/placeholder.jpg') }}" 
                     alt="{{ $product->name }}" 
                     id="share-modal-preview-thumb" 
                     class="share-preview-thumb">
                <div style="flex:1;min-width:0;">
                    <div style="font-family:'Inter',sans-serif;font-size:0.65rem;letter-spacing:0.18em;text-transform:uppercase;color:#737373;margin-bottom:2px;">PISTIS ATELIER</div>
                    <div style="font-family:'Cormorant Garamond',serif;font-size:1.15rem;font-weight:600;color:#171717;line-height:1.25;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $product->name }}
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;margin-top:6px;flex-wrap:wrap;">
                        <span id="share-modal-color-tag" style="display:inline-block;padding:2px 8px;background:#171717;color:#ffffff;font-size:0.68rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;">
                            {{ $defaultColor ?? 'Standard' }}
                        </span>
                        <span id="share-modal-price-tag" style="font-family:'Cormorant Garamond',serif;font-size:1.1rem;font-weight:600;color:#000000;">
                            {{ $initialFormattedPrice }}
                        </span>
                        <span style="font-size:0.68rem;color:#a3a3a3;letter-spacing:0.1em;text-transform:uppercase;">REF: {{ $product->sku }}</span>
                    </div>
                </div>
            </div>

            {{-- Native Device Share Trigger (Shown prominently on mobile/supported browsers) --}}
            <button type="button" 
                    id="native-share-btn-wrap"
                    class="native-share-trigger" 
                    onclick="triggerNativeShare()">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle>
                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                </svg>
                <span>Share via Device (AirDrop / Apps)</span>
            </button>

            {{-- Quick Channels Heading --}}
            <div style="font-size:0.7rem;font-weight:600;letter-spacing:0.14em;text-transform:uppercase;color:#737373;margin-bottom:10px;">
                Direct Channels
            </div>

            {{-- Share Grid: WhatsApp, X, Facebook, Pinterest, Telegram, Email --}}
            <div class="share-channels-grid">
                {{-- WhatsApp --}}
                <a href="#" id="share-btn-whatsapp" target="_blank" rel="noopener noreferrer" class="share-channel-btn" title="Share via WhatsApp">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    <span>WhatsApp</span>
                </a>

                {{-- X (Twitter) --}}
                <a href="#" id="share-btn-x" target="_blank" rel="noopener noreferrer" class="share-channel-btn" title="Share on X">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                    <span>X</span>
                </a>

                {{-- Facebook --}}
                <a href="#" id="share-btn-facebook" target="_blank" rel="noopener noreferrer" class="share-channel-btn" title="Share on Facebook">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span>Facebook</span>
                </a>

                {{-- Pinterest --}}
                <a href="#" id="share-btn-pinterest" target="_blank" rel="noopener noreferrer" class="share-channel-btn" title="Pin on Pinterest">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0C5.373 0 0 5.372 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345-.09.375-.291 1.199-.334 1.366-.053.225-.177.271-.409.165-1.527-.71-2.48-2.937-2.48-4.728 0-3.852 2.798-7.389 8.069-7.389 4.236 0 7.528 3.018 7.528 7.052 0 4.209-2.654 7.596-6.337 7.596-1.237 0-2.401-.643-2.801-1.401l-.762 2.907c-.276 1.053-1.022 2.373-1.523 3.178C9.538 23.824 10.749 24 12 24c6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/>
                    </svg>
                    <span>Pinterest</span>
                </a>

                {{-- Telegram --}}
                <a href="#" id="share-btn-telegram" target="_blank" rel="noopener noreferrer" class="share-channel-btn" title="Share on Telegram">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.121l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.458c.538-.196 1.006.128.832.939z"/>
                    </svg>
                    <span>Telegram</span>
                </a>

                {{-- Email --}}
                <a href="#" id="share-btn-email" class="share-channel-btn" title="Share via Email">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <span>Email</span>
                </a>
            </div>

            {{-- Copy Link Bar --}}
            <div style="font-size:0.7rem;font-weight:600;letter-spacing:0.14em;text-transform:uppercase;color:#737373;margin-bottom:8px;">
                Direct Product Link
            </div>
            <div class="share-copy-bar">
                <input type="text" 
                       id="share-product-url-input" 
                       value="{{ url()->current() }}" 
                       readonly 
                       class="share-copy-input"
                       onclick="this.select()">
                <button type="button" 
                        id="share-copy-btn-action" 
                        class="share-copy-btn" 
                        onclick="copyProductShareUrl()" 
                        title="Copy link to clipboard"
                        aria-label="Copy link to clipboard">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div class="share-modal-footer">
            <span style="font-size:0.72rem;color:#a3a3a3;letter-spacing:0.04em;">Pistis Garment Archive · Direct Share</span>
            <button type="button" onclick="closeProductShareModal()" class="btn btn-sm btn-secondary" style="font-size:0.75rem;padding:6px 18px;border-radius:0;letter-spacing:0.08em;">DONE</button>
        </div>
    </div>
</div>

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



