@extends('layouts.admin')

@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="admin-header" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div class="d-flex align-center gap-3">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">←</a>
        <div style="display:flex;align-items:center;gap:10px;">
            <h1 style="margin:0;">Order {{ $order->order_number }}</h1>
            @if($order->is_archived)
                <span class="badge" style="background:#e4e4e7;color:#52525b;font-size:0.7rem;letter-spacing:0.05em;padding:3px 8px;">ARCHIVED</span>
            @endif
        </div>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
        @if(!$order->is_archived)
            <form action="{{ route('admin.orders.archive', $order) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm" title="Move order to archive">
                    📦 Archive
                </button>
            </form>
        @else
            <form action="{{ route('admin.orders.unarchive', $order) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm" title="Restore order from archive">
                    ↺ Restore
                </button>
            </form>
        @endif

        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" style="margin:0;" onsubmit="return confirm('Are you sure you want to permanently delete order {{ $order->order_number }}? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border:1px solid #fecaca;padding:6px 14px;cursor:pointer;font-weight:600;">
                🗑 Delete
            </button>
        </form>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
    <div>
        {{-- Order Items --}}
        <div class="card mb-4">
            <div class="card-header"><h3 style="font-size:1rem;">Order Items</h3></div>
            <div class="card-body" style="padding:0;">
                @foreach($order->items as $item)
                    <div class="d-flex align-center gap-3" style="padding:14px 20px;border-bottom:1px solid var(--border-color);">
                        {{-- Product Image Thumbnail (clickable for modal preview) --}}
                        <div onclick="openItemImageModal('{{ $item->image_url }}', '{{ addslashes($item->product_name) }}', '{{ $item->product_sku }}', '{{ $item->color }}', '{{ $item->size }}')" style="width:56px;height:70px;border-radius:4px;overflow:hidden;background:#f5f5f5;border:1px solid #e5e5e5;flex-shrink:0;display:flex;align-items:center;justify-content:center;cursor:pointer;" title="Click to preview image">
                            @if($item->image_url)
                                <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}" style="width:100%;height:100%;object-fit:cover;display:block;transition:transform 0.15s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                            @else
                                <span style="font-size:1.4rem;color:#a3a3a3;">📦</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div style="font-weight:600;color:var(--text-primary);font-size:0.95rem;">
                                {{-- Click name to preview image in modal --}}
                                <button type="button" onclick="openItemImageModal('{{ $item->image_url }}', '{{ addslashes($item->product_name) }}', '{{ $item->product_sku }}', '{{ $item->color }}', '{{ $item->size }}')" style="background:none;border:none;padding:0;font-size:inherit;font-weight:600;color:var(--text-primary);cursor:pointer;text-align:left;display:inline-flex;align-items:center;gap:6px;transition:opacity 0.15s;" onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'" title="Click to preview image">
                                    <span>{{ $item->product_name }}</span>
                                    <span style="font-size:0.75rem;opacity:0.5;">🔍</span>
                                </button>
                            </div>
                            <div class="text-muted" style="font-size:0.8rem;margin-top:3px;">
                                SKU: {{ $item->product_sku }} • Qty: {{ $item->quantity }} × {{ $currency_symbol }}{{ number_format($item->price, 2) }}
                                @if($item->color)
                                    • Color: <span class="badge badge-secondary" style="font-size:0.75rem;padding:2px 8px;text-transform:uppercase;">{{ $item->color }}</span>
                                @endif
                                @if($item->size)
                                    • Size: <span class="badge badge-secondary" style="font-size:0.75rem;padding:2px 8px;text-transform:uppercase;">{{ $item->size }}</span>
                                @endif
                            </div>
                        </div>
                        <div style="font-weight:700;color:var(--text-primary);font-size:0.95rem;">{{ $currency_symbol }}{{ number_format($item->total, 2) }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="card">
            <div class="card-header"><h3 style="font-size:1rem;">Customer</h3></div>
            <div class="card-body">
                <div class="d-flex justify-between mb-2"><span class="text-muted">Name</span><span>{{ $order->customer_name }}</span></div>
                <div class="d-flex justify-between mb-2"><span class="text-muted">Email</span><span>{{ $order->customer_email }}</span></div>
                @if($order->shipping_address)
                    <div class="d-flex justify-between mb-2"><span class="text-muted">Phone</span><span>{{ $order->shipping_address['phone'] ?? 'N/A' }}</span></div>
                    <hr style="border-color:var(--border-color);margin:12px 0;">
                    <div class="text-muted" style="font-size:0.875rem;">
                        <strong>Shipping Address:</strong><br>
                        {{ $order->shipping_address['address'] ?? '' }}<br>
                        {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }}<br>
                        {{ $order->shipping_address['country'] ?? '' }}
                    </div>
                @endif
                <hr style="border-color:var(--border-color);margin:12px 0;">
                <div style="font-size:0.75rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:var(--text-muted);margin-bottom:8px;">Email Dispatches</div>
                <div class="d-flex justify-between mb-2">
                    <span class="text-muted" style="font-size:0.85rem;">Customer Confirmation</span>
                    @if($order->customer_notified_at)
                        <span class="badge badge-success" style="font-size:0.75rem;" title="Dispatched at {{ $order->customer_notified_at->format('M d, Y h:i A') }}">✓ Sent</span>
                    @else
                        <span class="badge badge-secondary" style="font-size:0.75rem;">Pending</span>
                    @endif
                </div>
                <div class="d-flex justify-between mb-2">
                    <span class="text-muted" style="font-size:0.85rem;">Admin Notification</span>
                    @if($order->admin_notified_at)
                        <span class="badge badge-success" style="font-size:0.75rem;" title="Received at {{ $order->admin_notified_at->format('M d, Y h:i A') }}">✓ Received</span>
                    @else
                        <span class="badge badge-secondary" style="font-size:0.75rem;">Pending</span>
                    @endif
                </div>
                <div class="d-flex justify-between">
                    <span class="text-muted" style="font-size:0.85rem;">Dispatch Notification</span>
                    @if($order->shipped_at)
                        <span class="badge badge-success" style="font-size:0.75rem;" title="Dispatched at {{ $order->shipped_at->format('M d, Y h:i A') }}">✓ Sent</span>
                    @else
                        <span class="badge badge-secondary" style="font-size:0.75rem;">Pending</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div>
        {{-- Order Summary --}}
        <div class="card mb-4">
            <div class="card-header"><h3 style="font-size:1rem;">Summary</h3></div>
            <div class="card-body">
                <div class="d-flex justify-between mb-2"><span class="text-muted">Subtotal</span><span>{{ $currency_symbol }}{{ number_format($order->subtotal, 2) }}</span></div>
                <div class="d-flex justify-between mb-2"><span class="text-muted">Shipping</span><span>{{ $currency_symbol }}{{ number_format($order->shipping_cost, 2) }}</span></div>
                <div class="d-flex justify-between mb-2"><span class="text-muted">Tax</span><span>{{ $currency_symbol }}{{ number_format($order->tax, 2) }}</span></div>
                <hr style="border-color:var(--border-color);margin:12px 0;">
                <div class="d-flex justify-between" style="font-weight:700;font-size:1.2rem;color:var(--text-primary);">
                    <span>Total</span><span>{{ $currency_symbol }}{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Status & Shipment Dispatch --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-between align-center">
                <h3 style="font-size:1rem;margin:0;">Fulfillment & Tracking</h3>
                <span class="badge badge-{{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="card-body">
                <div class="d-flex justify-between mb-2">
                    <span class="text-muted">Payment</span>
                    <span class="badge badge-{{ $order->payment_status_badge }}">{{ ucfirst($order->payment_status) }}</span>
                </div>
                <div class="d-flex justify-between mb-3">
                    <span class="text-muted">Method</span>
                    <span>{{ ucfirst($order->payment_method ?? 'N/A') }}</span>
                </div>

                @if($order->tracking_number)
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:12px;margin-bottom:16px;">
                        <div style="font-size:0.72rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#64748b;margin-bottom:4px;">
                            Active Consignment
                        </div>
                        <div class="d-flex justify-between align-center mb-1">
                            <span style="font-size:0.85rem;color:#334155;">Carrier: <strong>{{ $order->tracking_carrier ?? 'Australia Post' }}</strong></span>
                            @if($order->shipped_at)
                                <span style="font-size:0.75rem;color:#64748b;">{{ $order->shipped_at->format('d M, h:i A') }}</span>
                            @endif
                        </div>
                        <div style="font-family:monospace;font-size:0.95rem;font-weight:700;color:#0f172a;letter-spacing:0.05em;word-break:break-all;background:#ffffff;padding:6px 10px;border-radius:4px;border:1px solid #cbd5e1;margin:6px 0;">
                            {{ $order->tracking_number }}
                        </div>
                        @if($order->tracking_url)
                            <div style="margin-top:8px;">
                                <a href="{{ $order->tracking_url }}" target="_blank" class="btn btn-sm" style="font-size:0.75rem;padding:5px 12px;background:#dc2626;color:#ffffff;text-decoration:none;border-radius:4px;display:inline-flex;align-items:center;gap:6px;font-weight:600;">
                                    Track on Australia Post ↗
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;">Order Status</label>
                        <select name="status" id="order-status-select" class="form-control" onchange="toggleTrackingFields(this.value)">
                            @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $s)
                                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="tracking-fields-container" style="{{ in_array($order->status, ['shipped', 'delivered']) || !empty($order->tracking_number) ? 'display:block;' : 'display:none;' }}">
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:600;font-size:0.85rem;">Courier Carrier</label>
                            <input type="text" name="tracking_carrier" class="form-control" value="{{ old('tracking_carrier', $order->tracking_carrier ?? 'Australia Post') }}" placeholder="Australia Post">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:600;font-size:0.85rem;">
                                Australia Post Tracking / Consignment #
                            </label>
                            <input type="text" name="tracking_number" class="form-control" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="e.g. 9970123456780199 or AP-98214">
                            <small class="text-muted" style="font-size:0.72rem;display:block;margin-top:4px;line-height:1.4;">
                                Setting status to <strong>Shipped</strong> automatically triggers an email to <strong>{{ $order->customer_email }}</strong> with their direct Australia Post tracking link.
                            </small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update Order</button>
                </form>
            </div>
        </div>

        @if($order->notes)
            <div class="card">
                <div class="card-header"><h3 style="font-size:1rem;">Notes</h3></div>
                <div class="card-body text-muted" style="font-size:0.875rem;">{{ $order->notes }}</div>
            </div>
        @endif
    </div>
</div>

{{-- Item Image Preview Modal --}}
<div id="item-image-modal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.72);backdrop-filter:blur(5px);-webkit-backdrop-filter:blur(5px);z-index:9999;align-items:center;justify-content:center;padding:20px;" onclick="closeItemImageModal(event)">
    <div style="background:#ffffff;border-radius:10px;max-width:540px;width:100%;overflow:hidden;box-shadow:0 25px 50px -12px rgba(0,0,0,0.35);position:relative;" onclick="event.stopPropagation()">
        {{-- Modal Header --}}
        <div style="padding:16px 20px;border-bottom:1px solid #e5e5e5;display:flex;align-items:center;justify-content:space-between;background:#fafafa;">
            <div>
                <h3 id="modal-item-title" style="margin:0;font-size:1.05rem;font-weight:700;color:#171717;">Product Preview</h3>
                <span id="modal-item-sku" style="font-size:0.75rem;color:#737373;"></span>
            </div>
            <button type="button" onclick="closeItemImageModal()" style="background:none;border:none;font-size:1.4rem;line-height:1;cursor:pointer;color:#737373;padding:4px 8px;border-radius:4px;transition:background 0.15s;" onmouseover="this.style.background='#f0f0f0'" onmouseout="this.style.background='none'" title="Close">✕</button>
        </div>

        {{-- Modal Image Container --}}
        <div style="padding:24px;background:#f8f9fa;display:flex;align-items:center;justify-content:center;min-height:320px;max-height:65vh;overflow:hidden;">
            <img id="modal-item-image" src="" alt="Product Preview" style="max-width:100%;max-height:60vh;object-fit:contain;border-radius:6px;display:none;box-shadow:0 4px 12px rgba(0,0,0,0.08);">
            <div id="modal-no-image" style="display:none;text-align:center;padding:40px 20px;color:#737373;">
                <div style="font-size:3rem;margin-bottom:8px;">📦</div>
                <div style="font-size:0.95rem;font-weight:600;color:#171717;">No image available</div>
                <div style="font-size:0.8rem;color:#a3a3a3;margin-top:4px;">No product photography was uploaded for this item.</div>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div style="padding:14px 20px;background:#ffffff;border-top:1px solid #e5e5e5;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
            <div id="modal-item-badges" style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;"></div>
            <button type="button" onclick="closeItemImageModal()" class="btn btn-sm btn-secondary" style="margin-left:auto;font-size:0.8rem;padding:6px 16px;">Close</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openItemImageModal(imageUrl, title, sku, color, size) {
        const modal = document.getElementById('item-image-modal');
        const img = document.getElementById('modal-item-image');
        const noImg = document.getElementById('modal-no-image');
        const titleEl = document.getElementById('modal-item-title');
        const skuEl = document.getElementById('modal-item-sku');
        const badgesEl = document.getElementById('modal-item-badges');

        if (!modal) return;

        titleEl.textContent = title || 'Product Preview';
        skuEl.textContent = sku ? 'SKU: ' + sku : '';

        // Badges
        badgesEl.innerHTML = '';
        if (color) {
            const colorBadge = document.createElement('span');
            colorBadge.className = 'badge badge-secondary';
            colorBadge.style.cssText = 'font-size:0.75rem;padding:3px 8px;text-transform:uppercase;';
            colorBadge.textContent = 'Color: ' + color;
            badgesEl.appendChild(colorBadge);
        }
        if (size) {
            const sizeBadge = document.createElement('span');
            sizeBadge.className = 'badge badge-secondary';
            sizeBadge.style.cssText = 'font-size:0.75rem;padding:3px 8px;text-transform:uppercase;';
            sizeBadge.textContent = 'Size: ' + size;
            badgesEl.appendChild(sizeBadge);
        }

        // Image Handling
        if (imageUrl && imageUrl.trim() !== '') {
            img.src = imageUrl;
            img.alt = title;
            img.style.display = 'block';
            noImg.style.display = 'none';
        } else {
            img.style.display = 'none';
            img.src = '';
            noImg.style.display = 'block';
        }

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeItemImageModal(event) {
        if (event && event.target && event.target.id !== 'item-image-modal' && event.target.tagName !== 'BUTTON') {
            return;
        }
        const modal = document.getElementById('item-image-modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    function toggleTrackingFields(status) {
        const container = document.getElementById('tracking-fields-container');
        if (!container) return;
        if (status === 'shipped' || status === 'delivered') {
            container.style.display = 'block';
        } else {
            // Keep visible if there's already a tracking number entered, otherwise hide
            const trackingInput = container.querySelector('input[name="tracking_number"]');
            if (!trackingInput || !trackingInput.value.trim()) {
                container.style.display = 'none';
            }
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('item-image-modal');
            if (modal && modal.style.display === 'flex') {
                closeItemImageModal();
            }
        }
    });
</script>
@endpush



