@extends('layouts.app')

@section('title', '500 — Atelier Advisory · ' . ($store_name ?? 'PISTIS'))

@push('styles')
<style>
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
.error-giant-numeral {
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: clamp(6.5rem, 16vw, 11rem);
    font-weight: 300;
    line-height: 0.88;
    letter-spacing: 0.04em;
    color: #000000;
    margin: 0 0 16px;
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
.error-actions-group {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
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
</style>
@endpush

@section('content')
<div class="pdp-error-stage">
    <div class="error-card-inner">
        <div>
            <span class="error-badge-tag">
                PISTIS ATELIER · ADVISORY
            </span>
        </div>

        <div class="error-giant-numeral">
            500
        </div>

        <h1 class="error-headline">
            An Unexpected Atelier Interruption
        </h1>

        <p class="error-description">
            Our atelier systems encountered an unexpected irregularity while rendering this request. Our technical liaisons have been alerted and are resolving the matter.
        </p>

        <div class="error-actions-group">
            <a href="{{ route('home') }}" class="btn-luxury-primary">
                <span>RETURN TO ATELIER</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
            <a href="{{ route('shop.index') }}" class="btn-luxury-secondary">
                <span>EXPLORE COLLECTION</span>
            </a>
        </div>
    </div>
</div>
@endsection
