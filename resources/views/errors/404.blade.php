@extends('layouts.app')

@section('title', '404 — Page Not Found · ' . ($store_name ?? 'PISTIS'))

@push('styles')
<style>
/* ─── Luxury Haute-Couture Error Experience ────────────────────────── */
.pdp-error-stage {
    min-height: calc(82vh - var(--nav-height, 76px));
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 20px 80px;
    background: radial-gradient(circle at 50% 30%, #ffffff 0%, #fafafa 60%, #f4f4f5 100%);
    position: relative;
    overflow: hidden;
}

/* Subtle architectural watermark */
.pdp-error-stage::before {
    content: "PISTIS";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(8rem, 28vw, 24rem);
    font-weight: 300;
    color: rgba(0, 0, 0, 0.018);
    letter-spacing: 0.15em;
    pointer-events: none;
    user-select: none;
    z-index: 1;
    white-space: nowrap;
}

.error-card-inner {
    max-width: 680px;
    width: 100%;
    margin: 0 auto;
    text-align: center;
    position: relative;
    z-index: 2;
}

/* Giant Monochromatic 404 Numeral */
.error-giant-numeral {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: clamp(6.5rem, 16vw, 11rem);
    font-weight: 300;
    line-height: 0.88;
    letter-spacing: 0.04em;
    color: #000000;
    margin: 0 0 16px;
    position: relative;
    display: inline-block;
    background: linear-gradient(180deg, #000000 20%, #52525b 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.error-badge-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: 'Inter', sans-serif;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.24em;
    text-transform: uppercase;
    color: #71717a;
    padding: 6px 16px;
    border: 1px solid rgba(0, 0, 0, 0.08);
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    margin-bottom: 20px;
}
.error-badge-tag::before {
    content: "";
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #000000;
}

.error-headline {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: clamp(1.9rem, 4.2vw, 2.9rem);
    font-weight: 300;
    color: #09090b;
    letter-spacing: 0.02em;
    line-height: 1.2;
    margin: 0 0 16px;
}

.error-description {
    font-family: 'Inter', sans-serif;
    font-size: 0.92rem;
    line-height: 1.8;
    color: #52525b;
    max-width: 540px;
    margin: 0 auto 32px;
}

/* Luxury Interactive Search Box */
.error-search-box {
    max-width: 480px;
    margin: 0 auto 36px;
    display: flex;
    border: 1px solid #18181b;
    background: #ffffff;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    transition: all 0.2s ease;
}
.error-search-box:focus-within {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    border-color: #000000;
}
.error-search-input {
    flex: 1;
    border: none;
    padding: 14px 18px;
    font-family: 'Inter', sans-serif;
    font-size: 0.85rem;
    color: #18181b;
    background: transparent;
    outline: none;
}
.error-search-input::placeholder {
    color: #a1a1aa;
    font-weight: 400;
}
.error-search-btn {
    background: #000000;
    color: #ffffff;
    border: none;
    padding: 0 22px;
    font-family: 'Inter', sans-serif;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background 0.18s ease;
    flex-shrink: 0;
}
.error-search-btn:hover {
    background: #27272a;
}

/* High Fashion CTA Action Group */
.error-actions-group {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 36px;
}

.btn-luxury-primary {
    height: 48px;
    padding: 0 28px;
    background: #000000;
    color: #ffffff;
    font-family: 'Inter', sans-serif;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    text-decoration: none;
    border: 1px solid #000000;
    transition: all 0.2s ease;
}
.btn-luxury-primary:hover {
    background: #27272a;
    border-color: #27272a;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
}

.btn-luxury-secondary {
    height: 48px;
    padding: 0 26px;
    background: transparent;
    color: #18181b;
    font-family: 'Inter', sans-serif;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
    border: 1px solid #d4d4d8;
    transition: all 0.2s ease;
}
.btn-luxury-secondary:hover {
    border-color: #000000;
    background: #ffffff;
    color: #000000;
    transform: translateY(-1px);
}

/* Quick Archive Curation Tags */
.error-curation-tags {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 36px;
}
.curation-tag-link {
    font-family: 'Inter', sans-serif;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #71717a;
    text-decoration: none;
    padding: 4px 10px;
    border-bottom: 1px solid transparent;
    transition: all 0.18s ease;
}
.curation-tag-link:hover {
    color: #000000;
    border-bottom-color: #000000;
}
.curation-tag-dot {
    color: #d4d4d8;
    font-size: 0.65rem;
}

/* Discreet Portal Footnote */
.error-admin-footnote {
    font-family: 'Inter', sans-serif;
    font-size: 0.72rem;
    color: #a1a1aa;
    letter-spacing: 0.05em;
    padding-top: 24px;
    border-top: 1px solid #e4e4e7;
    max-width: 440px;
    margin: 0 auto;
}
.error-admin-footnote a {
    color: #18181b;
    text-decoration: underline;
    text-underline-offset: 3px;
    font-weight: 600;
    transition: color 0.15s;
}
.error-admin-footnote a:hover {
    color: #000000;
}
</style>
@endpush

@section('content')
<div class="pdp-error-stage">
    <div class="error-card-inner">
        
        {{-- Atelier Brand Badge --}}
        <div>
            <span class="error-badge-tag">
                PISTIS ARCHIVE · NOTICE
            </span>
        </div>

        {{-- Architectural 404 Numeral --}}
        <div class="error-giant-numeral">
            404
        </div>

        {{-- Editorial Title --}}
        <h1 class="error-headline">
            This Silhouette Is Beyond the Archive
        </h1>

        {{-- Refined Description --}}
        <p class="error-description">
            The page, garment, or editorial piece you are seeking is either no longer part of our current release, has been archived, or the link may have been entered incorrectly.
        </p>

        {{-- Search The Archive Bar --}}
        <form action="{{ route('shop.index') }}" method="GET" class="error-search-box">
            <input type="text" 
                   name="q" 
                   class="error-search-input" 
                   placeholder="Search our collection, tailored pieces, outerwear..." 
                   aria-label="Search the archive"
                   autocomplete="off">
            <button type="submit" class="error-search-btn">
                <span>SEARCH</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
        </form>

        {{-- Primary Action Buttons --}}
        <div class="error-actions-group">
            <a href="{{ route('shop.index') }}" class="btn-luxury-primary">
                <span>EXPLORE COLLECTION</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
            <a href="{{ route('home') }}" class="btn-luxury-secondary">
                <span>RETURN TO ATELIER</span>
            </a>
        </div>

        {{-- Curated Archive Navigation Tags --}}
        <div class="error-curation-tags">
            <a href="{{ route('shop.index') }}" class="curation-tag-link">All Pieces</a>
            <span class="curation-tag-dot">·</span>
            <a href="{{ route('home') }}" class="curation-tag-link">Runway Editorial</a>
            <span class="curation-tag-dot">·</span>
            <a href="{{ route('cart.index') }}" class="curation-tag-link">Shopping Bag</a>
            <span class="curation-tag-dot">·</span>
            <a href="{{ route('customer.login') }}" class="curation-tag-link">Client Account</a>
        </div>

        {{-- Helpful Atelier Portal Sign-in (addresses users looking for admin login) --}}
        <div class="error-admin-footnote">
            Looking for administration or atelier dispatch? 
            <a href="{{ route('admin.login') }}">Access Atelier Portal →</a>
        </div>

    </div>
</div>
@endsection
