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
    <div id="cart-content-wrapper" class="two-col" style="{{ $cart->items->count() > 0 ? '' : 'display:none;' }}">
        <div id="cart-items-container">
            @foreach($cart->items as $item)
                <div class="cart-item cart-item-row" id="cart-item-row-{{ $item->id }}" style="border:none;border-bottom:1px solid #e5e5e5;padding:24px 0;display:flex;align-items:center;gap:20px;transition:opacity 0.25s ease, transform 0.25s ease;">
                    <div class="cart-item-image" style="width:100px;height:130px;flex-shrink:0;background:#fafafa;">
                        @if($item->product && $item->product->primary_image)
                            <img src="{{ asset('storage/' . $item->product->primary_image) }}" alt="{{ $item->product->name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#a3a3a3;font-size:0.7rem;letter-spacing:0.1em;">NO IMAGE</div>
                        @endif
                    </div>
                    <div class="cart-item-info" style="flex:1;">
                        @if($item->product)
                            <a href="{{ route('shop.show', $item->product->slug) }}" style="text-decoration:none;">
                                <div style="font-family:'Cormorant Garamond',serif;font-size:1.1rem;color:#000000;margin-bottom:4px;">{{ $item->product->name }}</div>
                            </a>
                        @else
                            <div style="font-family:'Cormorant Garamond',serif;font-size:1.1rem;color:#000000;margin-bottom:4px;">Item</div>
                        @endif
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
                        <div class="cart-qty-stepper" style="display:inline-flex;align-items:center;border:1px solid #171717;height:38px;background:#ffffff;">
                            <button type="button" onclick="changeCartItemQty({{ $item->id }}, -1)" style="width:30px;height:100%;background:none;border:none;cursor:pointer;font-size:1.05rem;color:#000000;display:flex;align-items:center;justify-content:center;transition:background 0.15s;" onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='none'" title="Decrease quantity">−</button>
                            <input type="number" 
                                   id="cart-qty-input-{{ $item->id }}" 
                                   data-item-id="{{ $item->id }}" 
                                   data-unit-price="{{ (float) $item->price }}" 
                                   data-max="{{ $item->product?->stock_quantity ?? 999 }}"
                                   value="{{ $item->quantity }}" 
                                   min="1" 
                                   max="{{ $item->product?->stock_quantity ?? 999 }}" 
                                   class="qty-input" 
                                   style="width:42px;height:100%;border:none;text-align:center;font-size:0.85rem;font-weight:600;font-family:'Inter',sans-serif;background:transparent;" 
                                   oninput="handleCartQtyInput({{ $item->id }})"
                                   onchange="handleCartQtyChange({{ $item->id }})">
                            <button type="button" onclick="changeCartItemQty({{ $item->id }}, 1)" style="width:30px;height:100%;background:none;border:none;cursor:pointer;font-size:1.05rem;color:#000000;display:flex;align-items:center;justify-content:center;transition:background 0.15s;" onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='none'" title="Increase quantity">+</button>
                        </div>
                    </div>
                    <div class="cart-item-subtotal" id="cart-item-subtotal-{{ $item->id }}" style="font-family:'Cormorant Garamond',serif;font-size:1.1rem;color:#000000;min-width:90px;text-align:right;">
                        {{ $currency_symbol }}{{ number_format($item->subtotal, 2) }}
                    </div>
                    <div class="cart-item-remove">
                        <button type="button" onclick="removeCartItem({{ $item->id }})" class="btn btn-icon btn-secondary" title="Remove" style="color:#000000;background:transparent;border:none;font-size:0.85rem;padding:8px;cursor:pointer;line-height:1;transition:color 0.15s;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#000000'">✕</button>
                    </div>
                </div>
            @endforeach
        </div>

        <div>
            <div class="cart-summary cart-summary-box" style="background:#fafafa;border:1px solid #e5e5e5;border-radius:0;padding:32px;">
                <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:400;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid #e5e5e5;">Order Summary</h3>
                <div class="cart-summary-row" style="display:flex;justify-content:space-between;margin-bottom:12px;font-size:0.85rem;color:#525252;">
                    <span id="summary-pieces-count">Subtotal ({{ $totals['item_count'] }} {{ $totals['item_count'] === 1 ? 'piece' : 'pieces' }})</span>
                    <span id="summary-subtotal">{{ $currency_symbol }}{{ number_format($totals['subtotal'], 2) }}</span>
                </div>
                <div class="cart-summary-row" style="display:flex;justify-content:space-between;margin-bottom:12px;font-size:0.85rem;color:#525252;">
                    <span>Shipping</span>
                    <span id="summary-shipping">{{ $totals['shipping'] > 0 ? $currency_symbol . number_format($totals['shipping'], 2) : 'Complimentary' }}</span>
                </div>
                <div class="cart-summary-row" style="display:flex;justify-content:space-between;margin-bottom:20px;font-size:0.85rem;color:#525252;">
                    <span>Tax</span>
                    <span id="summary-tax">{{ $currency_symbol }}{{ number_format($totals['tax'], 2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-top:20px;border-top:1px solid #e5e5e5;font-family:'Cormorant Garamond',serif;font-size:1.3rem;color:#000000;">
                    <span>Total</span>
                    <span id="summary-total">{{ $currency_symbol }}{{ number_format($totals['total'], 2) }}</span>
                </div>
                <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg w-100" style="margin-top:24px;border-radius:0;letter-spacing:0.15em;font-size:0.8rem;">PROCEED TO CHECKOUT</a>
                <a href="{{ route('shop.index') }}" class="btn btn-secondary w-100" style="margin-top:8px;border-radius:0;letter-spacing:0.1em;font-size:0.75rem;background:transparent;color:#000000;border:1px solid #e5e5e5;">CONTINUE SHOPPING</a>
            </div>
        </div>
    </div>

    {{-- Empty Bag State --}}
    <div id="cart-empty-state" style="{{ $cart->items->count() === 0 ? '' : 'display:none;' }}text-align:center;padding:80px 24px;">
        <span style="font-family:'Inter',sans-serif;font-size:0.7rem;letter-spacing:0.2em;text-transform:uppercase;color:#a3a3a3;display:block;margin-bottom:16px;">YOUR BAG IS EMPTY</span>
        <h3 style="font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:300;margin:0 0 12px;color:#000000;">Nothing Here Yet</h3>
        <p style="color:#737373;font-size:0.85rem;margin-bottom:32px;">Explore the collection and add your favourite pieces.</p>
        <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg" style="border-radius:0;letter-spacing:0.15em;font-size:0.8rem;">EXPLORE THE ARCHIVE</a>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const currencySymbol = @json($currency_symbol);
    const updateUrlTemplate = "{{ route('cart.update', ':id') }}";
    const destroyUrlTemplate = "{{ route('cart.destroy', ':id') }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const pendingRequests = {};

    window.changeCartItemQty = function(itemId, delta) {
        const input = document.getElementById(`cart-qty-input-${itemId}`);
        if (!input) return;

        let currentVal = parseInt(input.value, 10) || 1;
        let max = parseInt(input.dataset.max, 10) || 9999;
        let min = 1;

        let newVal = currentVal + delta;
        if (newVal < min) newVal = min;
        if (newVal > max) newVal = max;

        if (newVal === currentVal) return;

        input.value = newVal;
        syncCartItemQuantity(itemId, newVal);
    };

    window.handleCartQtyInput = function(itemId) {
        const input = document.getElementById(`cart-qty-input-${itemId}`);
        if (!input) return;
        let val = parseInt(input.value, 10);
        if (isNaN(val) || val < 1) return;
        syncCartItemQuantity(itemId, val);
    };

    window.handleCartQtyChange = function(itemId) {
        const input = document.getElementById(`cart-qty-input-${itemId}`);
        if (!input) return;

        let val = parseInt(input.value, 10);
        let max = parseInt(input.dataset.max, 10) || 9999;
        let min = 1;

        if (isNaN(val) || val < min) val = min;
        if (val > max) val = max;

        input.value = val;
        syncCartItemQuantity(itemId, val);
    };

    function syncCartItemQuantity(itemId, quantity) {
        const input = document.getElementById(`cart-qty-input-${itemId}`);
        const unitPrice = input ? parseFloat(input.dataset.unitPrice) || 0 : 0;

        // 1. Instant Optimistic Calculation in UI
        const itemSubtotalEl = document.getElementById(`cart-item-subtotal-${itemId}`);
        if (itemSubtotalEl) {
            const itemTotal = unitPrice * quantity;
            itemSubtotalEl.textContent = `${currencySymbol}${itemTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        }

        recalculateClientTotals();

        // 2. Debounced AJAX Sync to Server
        if (pendingRequests[itemId]) {
            clearTimeout(pendingRequests[itemId]);
        }

        pendingRequests[itemId] = setTimeout(async () => {
            try {
                const url = updateUrlTemplate.replace(':id', itemId);
                const response = await fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ quantity: quantity })
                });

                if (!response.ok) {
                    throw new Error('Failed to update cart');
                }

                const data = await response.json();
                if (data.success) {
                    applyServerTotals(data);
                }
            } catch (error) {
                console.error('Cart sync error:', error);
            }
        }, 220);
    }

    window.removeCartItem = async function(itemId) {
        const row = document.getElementById(`cart-item-row-${itemId}`);
        if (row) {
            row.style.opacity = '0.3';
            row.style.pointerEvents = 'none';
        }

        try {
            const url = destroyUrlTemplate.replace(':id', itemId);
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to remove item');
            }

            const data = await response.json();
            if (data.success) {
                if (row) {
                    row.remove();
                }

                // Check remaining rows
                const remaining = document.querySelectorAll('.cart-item-row');
                if (remaining.length === 0) {
                    const cartContent = document.getElementById('cart-content-wrapper');
                    const emptyState = document.getElementById('cart-empty-state');
                    if (cartContent) cartContent.style.display = 'none';
                    if (emptyState) emptyState.style.display = 'block';
                }

                applyServerTotals(data);
            }
        } catch (error) {
            console.error('Cart removal error:', error);
            if (row) {
                row.style.opacity = '1';
                row.style.pointerEvents = 'auto';
            }
        }
    };

    function recalculateClientTotals() {
        let totalPieces = 0;
        let subtotal = 0;

        document.querySelectorAll('[id^="cart-qty-input-"]').forEach(input => {
            const qty = parseInt(input.value, 10) || 0;
            const price = parseFloat(input.dataset.unitPrice) || 0;
            totalPieces += qty;
            subtotal += (qty * price);
        });

        const piecesEl = document.getElementById('summary-pieces-count');
        if (piecesEl) {
            piecesEl.textContent = `Subtotal (${totalPieces} ${totalPieces === 1 ? 'piece' : 'pieces'})`;
        }

        const subtotalEl = document.getElementById('summary-subtotal');
        if (subtotalEl) {
            subtotalEl.textContent = `${currencySymbol}${subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        }

        const totalEl = document.getElementById('summary-total');
        if (totalEl) {
            totalEl.textContent = `${currencySymbol}${subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        }

        document.querySelectorAll('.cart-count').forEach(el => {
            el.textContent = totalPieces;
            el.style.display = totalPieces > 0 ? '' : 'none';
        });
    }

    function applyServerTotals(data) {
        if (!data.totals) return;

        const piecesEl = document.getElementById('summary-pieces-count');
        if (piecesEl) {
            piecesEl.textContent = `Subtotal (${data.totals.item_count} ${data.totals.item_count === 1 ? 'piece' : 'pieces'})`;
        }

        const subtotalEl = document.getElementById('summary-subtotal');
        if (subtotalEl && data.totals.formatted_subtotal) {
            subtotalEl.textContent = data.totals.formatted_subtotal;
        }

        const totalEl = document.getElementById('summary-total');
        if (totalEl && data.totals.formatted_total) {
            totalEl.textContent = data.totals.formatted_total;
        }

        if (data.item && data.item.formatted_subtotal) {
            const itemSubtotalEl = document.getElementById(`cart-item-subtotal-${data.item.id}`);
            if (itemSubtotalEl) {
                itemSubtotalEl.textContent = data.item.formatted_subtotal;
            }
        }

        if (data.cart_badge_count !== undefined) {
            document.querySelectorAll('.cart-count').forEach(el => {
                el.textContent = data.cart_badge_count;
                el.style.display = data.cart_badge_count > 0 ? '' : 'none';
            });
        }
    }
})();
</script>
@endpush
@endsection
