@extends('layouts.admin')

@section('title', 'Hero Slider Management')

@section('content')
<div class="admin-header">
    <div>
        <h1>Hero Slider Management</h1>
        <p class="text-muted" style="font-size:0.875rem;margin-top:4px;">Manage and reorder high-fashion editorial hero slides displayed on your homepage.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary">👁️ View Live Hero</a>
        <a href="{{ route('admin.hero-slides.create') }}" class="btn btn-primary">+ Add New Slide</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-between align-center">
        <div>
            <h3 style="font-size:1rem;margin:0;">Active & Scheduled Slides</h3>
            <span class="text-muted" style="font-size:0.8rem;">Drag and drop slides to reorder their sequence on the homepage</span>
        </div>
        <span class="badge badge-secondary" id="slide-count-badge">{{ $slides->count() }} total slides</span>
    </div>
    <div class="card-body" style="padding: 16px;">
        @if($slides->count() > 0)
            <div class="hero-slides-drag-list" id="hero-slides-container">
                @foreach($slides as $index => $slide)
                    <div class="hero-slide-admin-card {{ !$slide->is_active ? 'is-disabled' : '' }}" 
                         data-id="{{ $slide->id }}" 
                         draggable="true">
                        
                        <div class="drag-handle" title="Drag to reorder">⋮⋮</div>
                        
                        {{-- Slide Image Thumbnail --}}
                        <div class="admin-slide-thumb">
                            <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}">
                            @if($slide->mobile_image)
                                <span class="mobile-thumb-badge" title="Has dedicated mobile image">📱</span>
                            @endif
                        </div>

                        {{-- Slide Content Details --}}
                        <div class="admin-slide-details">
                            <div class="admin-slide-collection">
                                {{ $slide->collection_name ?: 'UNTAGGED COLLECTION' }}
                                @if($slide->subtitle)
                                    <span class="text-muted">· {{ $slide->subtitle }}</span>
                                @endif
                            </div>
                            
                            <h4 class="admin-slide-title">{{ str_replace("\n", " / ", $slide->title) }}</h4>
                            
                            @if($slide->description)
                                <p class="admin-slide-desc">{{ Str::limit($slide->description, 110) }}</p>
                            @endif

                            <div class="admin-slide-meta">
                                <span class="meta-item">⏱️ {{ $slide->display_duration }}s</span>
                                <span class="meta-item">🔗 {{ $slide->primary_button_text ?: 'SHOP' }}</span>
                                @if($slide->starts_at || $slide->ends_at)
                                    @if($slide->ends_at && $slide->ends_at->isPast())
                                        <span class="meta-item" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;" title="Campaign ended on {{ $slide->ends_at->format('M d, Y h:i A') }}">
                                            ⏰ Campaign Ended · Hidden from Homepage
                                        </span>
                                    @elseif($slide->starts_at && $slide->starts_at->isFuture())
                                        <span class="meta-item" style="background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;" title="Scheduled to start on {{ $slide->starts_at->format('M d, Y h:i A') }}">
                                            ⏳ Scheduled (Starts {{ $slide->starts_at->format('M d') }})
                                        </span>
                                    @else
                                        <span class="meta-item schedule-tag" style="background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;">
                                            🟢 Live Campaign (Ends {{ $slide->ends_at ? $slide->ends_at->format('M d') : 'Forever' }})
                                        </span>
                                    @endif
                                @else
                                    <span class="meta-item text-muted" style="font-size:0.75rem;">
                                        ✨ Standard Slide
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Status & Order --}}
                        <div class="admin-slide-status-col">
                            <div class="order-badge">
                                Order: <strong>#<span class="order-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span></strong>
                            </div>
                            
                            <form action="{{ route('admin.hero-slides.toggle-status', $slide) }}" method="POST" class="status-toggle-form">
                                @csrf
                                <button type="button" 
                                        class="status-pill {{ $slide->is_active ? 'active' : 'inactive' }}" 
                                        onclick="toggleSlideStatus(this, '{{ route('admin.hero-slides.toggle-status', $slide) }}')">
                                    <span class="status-dot"></span>
                                    <span class="status-text">{{ $slide->is_active ? 'ACTIVE' : 'DISABLED' }}</span>
                                </button>
                            </form>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="admin-slide-actions">
                            <button type="button" 
                                    class="btn btn-sm btn-secondary" 
                                    onclick="previewSlide({{ json_encode([
                                        'collection' => $slide->collection_name,
                                        'subtitle' => $slide->subtitle,
                                        'title' => $slide->title,
                                        'description' => $slide->description,
                                        'image' => $slide->image_url,
                                        'primary_text' => $slide->primary_button_text,
                                        'primary_url' => $slide->primary_button_url,
                                        'secondary_text' => $slide->secondary_button_text,
                                        'secondary_url' => $slide->secondary_button_url,
                                    ]) }})"
                                    title="Quick Preview">
                                👁️ Preview
                            </button>

                            <a href="{{ route('admin.hero-slides.edit', $slide) }}" class="btn btn-sm btn-secondary">
                                ✏️ Edit
                            </a>

                            <form action="{{ route('admin.hero-slides.duplicate', $slide) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-secondary" title="Duplicate Slide">
                                    📋 Duplicate
                                </button>
                            </form>

                            <form action="{{ route('admin.hero-slides.destroy', $slide) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this hero slide?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete Slide">
                                    ✕
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">🖼️</div>
                <h3>No hero slides created yet</h3>
                <p>Create your first high-fashion editorial slide to transform your homepage.</p>
                <a href="{{ route('admin.hero-slides.create') }}" class="btn btn-primary">+ Create First Slide</a>
            </div>
        @endif
    </div>
</div>

{{-- Slide Quick Preview Modal --}}
<div class="preview-modal-overlay" id="preview-modal" onclick="closePreviewModal(event)">
    <div class="preview-modal-box" onclick="event.stopPropagation()">
        <button type="button" class="preview-modal-close" onclick="closePreviewModal()">✕</button>
        <div class="editorial-preview-hero" id="preview-hero-container">
            {{-- Rendered dynamically by JS --}}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ─── Drag and Drop Reordering ────────────────────────────────────
const container = document.getElementById('hero-slides-container');
let draggedItem = null;

if (container) {
    const cards = container.querySelectorAll('.hero-slide-admin-card');

    cards.forEach(card => {
        card.addEventListener('dragstart', function(e) {
            draggedItem = this;
            this.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
        });

        card.addEventListener('dragend', function() {
            this.classList.remove('dragging');
            draggedItem = null;
            updateCardOrders();
            saveNewOrder();
        });

        card.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            const afterElement = getDragAfterElement(container, e.clientY);
            if (afterElement == null) {
                container.appendChild(draggedItem);
            } else {
                container.insertBefore(draggedItem, afterElement);
            }
        });
    });

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.hero-slide-admin-card:not(.dragging)')];
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    function updateCardOrders() {
        const currentCards = container.querySelectorAll('.hero-slide-admin-card');
        currentCards.forEach((card, idx) => {
            const numSpan = card.querySelector('.order-number');
            if (numSpan) {
                numSpan.textContent = String(idx + 1).padStart(2, '0');
            }
        });
    }

    function saveNewOrder() {
        const currentCards = container.querySelectorAll('.hero-slide-admin-card');
        const order = Array.from(currentCards).map(card => card.getAttribute('data-id'));

        fetch("{{ route('admin.hero-slides.reorder') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ order: order })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('Order saved successfully!');
            }
        })
        .catch(err => console.error('Order save error:', err));
    }
}

// ─── Status Toggle via AJAX ──────────────────────────────────────
function toggleSlideStatus(button, url) {
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const card = button.closest('.hero-slide-admin-card');
            if (data.is_active) {
                button.className = 'status-pill active';
                button.querySelector('.status-text').textContent = 'ACTIVE';
                card.classList.remove('is-disabled');
            } else {
                button.className = 'status-pill inactive';
                button.querySelector('.status-text').textContent = 'DISABLED';
                card.classList.add('is-disabled');
            }
            showToast(data.message);
        }
    })
    .catch(err => console.error('Status toggle error:', err));
}

// ─── Slide Preview Modal ─────────────────────────────────────────
function previewSlide(slide) {
    const modal = document.getElementById('preview-modal');
    const container = document.getElementById('preview-hero-container');
    
    const titleFormatted = (slide.title || '')
        .split('\n')
        .map(line => `<span class="preview-title-line">${line}</span>`)
        .join('');

    container.innerHTML = `
        <div class="modal-preview-editorial-grid">
            <div class="modal-preview-text">
                <span class="preview-collection-tag">${slide.collection || 'NEW COLLECTION'}</span>
                <h2 class="preview-headline">${titleFormatted || 'HERO TITLE'}</h2>
                <p class="preview-description">${slide.description || ''}</p>
                <div class="preview-cta-row">
                    <span class="preview-btn-primary">${slide.primary_text || 'SHOP COLLECTION'}</span>
                    ${slide.secondary_text ? `<span class="preview-btn-secondary">${slide.secondary_text}</span>` : ''}
                </div>
                <div class="preview-counter-tag">01 / 01</div>
            </div>
            <div class="modal-preview-image">
                <img src="${slide.image}" alt="Preview">
            </div>
        </div>
    `;
    
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closePreviewModal(e) {
    if (e && e.target && e.target.closest('.preview-modal-box') && !e.target.classList.contains('preview-modal-close')) {
        return;
    }
    const modal = document.getElementById('preview-modal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

function showToast(msg) {
    let toast = document.getElementById('admin-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'admin-toast';
        toast.className = 'admin-floating-toast';
        document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2500);
}
</script>
@endpush
