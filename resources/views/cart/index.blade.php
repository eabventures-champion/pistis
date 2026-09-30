@extends('layouts.app')

@section('title', 'Shopping Bag — Pistis')

@section('content')
{{-- Bag Banner --}}
<div class="page-dark-banner cart-hero-banner" style="background:#000000;color:#ffffff;text-align:center;padding:48px 24px 40px;">
    <span class="banner-subtitle" style="font-family:'Inter',sans-serif;font-size:0.7rem;letter-spacing:0.25em;text-transform:uppercase;color:#a3a3a3;display:block;margin-bottom:12px;">YOUR SELECTION</span>
    <h1 style="color:#ffffff !important;font-family:'Cormorant Garamond',serif;font-size:clamp(1.8rem,4vw,2.8rem);font-weight:300;margin:0;letter-spacing:0.02em;">Shopping Bag</h1>
    <div class="banner-divider" style="width:40px;height:1px;background:rgba(255,255,255,0.3);margin:20px auto 0;"></div>
</div>

<div class="container" style="padding-top:48px;padding-bottom:60px;">
    @if($cart->items->count() > 0)
        <div class="two-col">
            <div>
                @foreach($cart->items as $item)
                    <div class="cart-item cart-item-row" style="border:none;border-bottom:1px solid #e5e5e5;padding:24px 0;display:flex;align-items:center;gap:20px;">
                        <div class="cart-item-image" style="width:100px;height:130px;flex-shrink:0;background:#fafafa;">
                            @if($item->product->primary_image)
                                <img src="{{ asset('storage/' . $item->product->primary_image) }}" alt="{{ $item->product->name }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#a3a3a3;font-size:0.7rem;letter-spacing:0.1em;">NO IMAGE</div>
                            @endif
                        </div>
                        <div class="cart-item-info" style="flex:1;">
                            <a href="{{ route('shop.show', $item->product->slug) }}" style="text-decoration:none;">
                                <div style="font-family:'Cormorant Garamond',serif;font-size:1.1rem;color:#000000;margin-bottom:4px;">{{ $item->product->name }}</div>
                            </a>
                            @if($item->color || $item->size)
                                <div style="font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;color:#737373;margin-bottom:4px;">
                                    @if($item->color)
                                        COLOR: <strong style="color:#000000;font-weight:600;">{{ $item->color }}</strong>
                                    @endif
                                    @if($item->color && $item->size)
                                        &nbsp;·&nbsp;
                                    @endif
                                    @if($item->size)
                                        SIZE: <strong style="color:#000000;font-weight:600;">{{ $item->size }}</strong>
                                    @endif
                                </div>
                            @endif
                            <div style="font-size:0.75rem;color:#737373;letter-spacing:0.05em;">{{ $currency_symbol }}{{ number_format($item->price, 2) }} each</div>
                        </div>
                        <div class="cart-item-quantity">
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex gap-2 align-center">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock_quantity }}" class="qty-input" style="width:60px;text-align:center;border:1px solid #e5e5e5;border-radius:0;font-size:0.8rem;" onchange="this.form.submit()">
                            </form>
                        </div>
                        <div class="cart-item-subtotal" style="font-family:'Cormorant Garamond',serif;font-size:1.1rem;color:#000000;min-width:90px;text-align:right;">
                            {{ $currency_symbol }}{{ number_format($item->subtotal, 2) }}
                        </div>
                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="cart-item-remove">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-icon btn-secondary" title="Remove" style="color:#000000;background:transparent;border:none;font-size:0.8rem;padding:8px;cursor:pointer;">✕</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div>
                <div class="cart-summary cart-summary-box" style="background:#fafafa;border:1px solid #e5e5e5;border-radius:0;padding:32px;">
                    <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:400;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid #e5e5e5;">Order Summary</h3>
                    <div class="cart-summary-row" style="display:flex;justify-content:space-between;margin-bottom:12px;font-size:0.85rem;color:#525252;">
                        <span>Subtotal ({{ $totals['item_count'] }} {{ $totals['item_count'] === 1 ? 'piece' : 'pieces' }})</span>
                        <span>{{ $currency_symbol }}{{ number_format($totals['subtotal'], 2) }}</span>
                    </div>
                    <div class="cart-summary-row" style="display:flex;justify-content:space-between;margin-bottom:12px;font-size:0.85rem;color:#525252;">
                        <span>Shipping</span>
                        <span>{{ $totals['shipping'] > 0 ? $currency_symbol . number_format($totals['shipping'], 2) : 'Complimentary' }}</span>
                    </div>
                    <div class="cart-summary-row" style="display:flex;justify-content:space-between;margin-bottom:20px;font-size:0.85rem;color:#525252;">
                        <span>Tax</span>
                        <span>{{ $currency_symbol }}{{ number_format($totals['tax'], 2) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding-top:20px;border-top:1px solid #e5e5e5;font-family:'Cormorant Garamond',serif;font-size:1.3rem;color:#000000;">
                        <span>Total</span>
                        <span>{{ $currency_symbol }}{{ number_format($totals['total'], 2) }}</span>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg w-100" style="margin-top:24px;border-radius:0;letter-spacing:0.15em;font-size:0.8rem;">PROCEED TO CHECKOUT</a>
                    <a href="{{ route('shop.index') }}" class="btn btn-secondary w-100" style="margin-top:8px;border-radius:0;letter-spacing:0.1em;font-size:0.75rem;background:transparent;color:#000000;border:1px solid #e5e5e5;">CONTINUE SHOPPING</a>
                </div>
            </div>
        </div>
    @else
        <div style="text-align:center;padding:80px 24px;">
            <div style="font-size:0.7rem;letter-spacing:0.2em;text-transform:uppercase;color:#a3a3a3;margin-bottom:16px;">YOUR BAG IS EMPTY</div>
            <h3 style="font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:300;margin:0 0 12px;">Nothing Here Yet</h3>
            <p style="color:#737373;font-size:0.85rem;margin-bottom:32px;">Explore the collection and add your favourite pieces.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg" style="border-radius:0;letter-spacing:0.15em;font-size:0.8rem;">EXPLORE THE ARCHIVE</a>
        </div>
    @endif
</div>
@endsection
