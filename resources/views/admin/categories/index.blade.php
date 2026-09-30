@extends('layouts.admin')

@section('title', 'Categories')

@push('styles')
<style>
    .action-icon-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
        background: #ffffff;
        color: var(--text-primary);
        cursor: pointer;
        transition: all 0.18s ease;
        flex-shrink: 0;
        user-select: none;
    }
    .action-icon-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }
    .action-icon-btn:active {
        transform: translateY(0);
    }
    .status-icon-btn.is-active {
        border-color: #18181b;
        background: #ffffff;
    }
    .status-icon-btn.is-inactive {
        border-color: #e4e4e7;
        background: #fafafa;
        opacity: 0.75;
    }
    .status-icon-btn:hover {
        border-color: #000000;
        opacity: 1;
    }
    .status-icon-btn.loading {
        opacity: 0.45;
        pointer-events: none;
    }
    .edit-icon-btn:hover {
        background: #18181b;
        border-color: #18181b;
        color: #ffffff;
    }
    .edit-icon-btn:hover svg {
        stroke: #ffffff;
    }
    .delete-icon-btn:not(.is-disabled):hover {
        background: #fef2f2;
        border-color: #fca5a5;
        color: #dc2626;
    }
    .delete-icon-btn:not(.is-disabled):hover svg {
        stroke: #dc2626;
    }
    .delete-icon-btn.is-disabled {
        opacity: 0.4;
        cursor: not-allowed;
        background: #f4f4f5;
        border-color: #e4e4e7;
    }
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.97) translateY(-6px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    .category-toast {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 100000;
        background: #18181b;
        color: #ffffff;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 0.86rem;
        font-weight: 500;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        display: flex;
        align-items: center;
        gap: 10px;
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity 0.25s ease, transform 0.25s ease;
        pointer-events: none;
    }
    .category-toast.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endpush

@section('content')
<div class="admin-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
    <h1>Categories</h1>

    <div style="display:flex;align-items:center;gap:12px;">
        <form action="{{ route('admin.categories.toggle-homepage-section') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="btn btn-sm" style="display:inline-flex;align-items:center;gap:8px;font-size:0.8rem;padding:7px 14px;border:1px solid {{ $showHomepageCategories ? '#18181b' : '#d4d4d8' }};background:{{ $showHomepageCategories ? '#ffffff' : '#f4f4f5' }};color:{{ $showHomepageCategories ? '#18181b' : '#71717a' }};cursor:pointer;border-radius:6px;transition:all 0.15s ease;" title="Toggle whether 'Shop by Category' appears on the storefront homepage">
                <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:{{ $showHomepageCategories ? '#22c55e' : '#a1a1aa' }};"></span>
                <span>Homepage Section: <strong>{{ $showHomepageCategories ? 'Visible' : 'Hidden' }}</strong></span>
                <span style="font-size:0.75rem;color:var(--text-muted);margin-left:4px;">(Click to {{ $showHomepageCategories ? 'Hide' : 'Show' }})</span>
            </button>
        </form>
    </div>
</div>

{{-- Notification Toast --}}
<div id="categoryToast" class="category-toast">
    <span style="color:#22c55e; font-weight:bold;">✓</span>
    <span id="categoryToastMsg">Status updated successfully</span>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">
    {{-- Add Category Form --}}
    <div class="card" style="position:sticky;top:24px;align-self:start;">
        <div class="card-header"><h3 style="font-size:1rem;">Add Category</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600; font-size:0.85rem;">Name *</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Outerwear">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600; font-size:0.85rem;">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Optional category description..."></textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600; font-size:0.85rem;">Parent Category</label>
                    <select name="parent_id" class="form-control">
                        <option value="">None (Top Level)</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->full_path }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-4">
                    <label class="form-check" style="cursor:pointer; display:flex; align-items:center; gap:8px;">
                        <input type="checkbox" name="is_active" value="1" checked style="width:16px; height:16px; accent-color:#000;">
                        <span class="form-label" style="margin:0; cursor:pointer;">Active</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary w-100" style="padding:10px 16px; font-weight:700;">Add Category</button>
            </form>
        </div>
    </div>

    {{-- Category List --}}
    <div class="card">
        <div class="card-header d-flex justify-between align-center" style="padding:14px 20px;">
            <div style="display:flex; align-items:center; gap:10px;">
                <input type="checkbox" id="selectAllCategories" style="cursor:pointer; accent-color:#000000; width:16px; height:16px;" onchange="toggleSelectAllCategories(this)" title="Select/Deselect All Categories">
                <h3 style="font-size:1rem; margin:0;">All Categories</h3>
            </div>
            <span class="text-muted" style="font-size:0.8rem;">{{ $categories->count() }} total</span>
        </div>

        {{-- Bulk Actions Toolbar --}}
        <div id="categoryBulkToolbar" style="display:none; background:#18181b; color:#ffffff; padding:10px 20px; border-bottom:1px solid #27272a; align-items:center; justify-content:space-between; animation:modalFadeIn 0.2s ease;">
            <div style="font-size:0.84rem; display:flex; align-items:center; gap:10px;">
                <span><strong id="categorySelectedCount">0</strong> category(ies) selected</span>
                <span style="color:#52525b;">•</span>
                <button type="button" onclick="clearSelectedCategories()" style="background:transparent; border:none; color:#a1a1aa; font-size:0.78rem; cursor:pointer; text-decoration:underline; padding:0;">Deselect All</button>
            </div>
            <div>
                <button type="button" onclick="executeBulkDeleteCategories()" class="btn btn-sm" style="background:#dc2626; color:#ffffff; border:none; cursor:pointer; font-size:0.75rem; font-weight:700; padding:6px 14px; border-radius:4px; display:inline-flex; align-items:center; gap:6px;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    <span>Delete Selected (<span id="btnDeleteSelectedCount">0</span>)</span>
                </button>
            </div>
        </div>

        {{-- Hidden form for bulk delete submission --}}
        <form id="bulkDeleteCategoriesForm" action="{{ route('admin.categories.bulk-destroy') }}" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
            <div id="bulkCategoryInputsContainer"></div>
        </form>

        <div class="card-body" style="padding:0;">
            @forelse($categories as $category)
                <div class="d-flex justify-between align-center" style="padding:12px 20px;border-bottom:1px solid var(--border-color); transition:background 0.15s ease;" id="category-row-{{ $category->id }}">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <input type="checkbox" 
                            class="category-select-checkbox" 
                            value="{{ $category->id }}"
                            data-name="{{ $category->name }}"
                            data-children-count="{{ $category->children_count }}"
                            style="cursor:pointer; accent-color:#000000; width:16px; height:16px; flex-shrink:0;" 
                            onchange="updateCategoryBulkToolbar()">
                        <div>
                            <div style="font-weight:600;color:var(--text-primary);" id="category-name-display-{{ $category->id }}">
                                @if(!empty($category->parent_path))
                                    <span class="text-muted" id="category-parent-text-{{ $category->id }}">{{ $category->parent_path }}</span>
                                @else
                                    <span class="text-muted" id="category-parent-text-{{ $category->id }}" style="display:none;"></span>
                                @endif
                                <span class="cat-label">{{ $category->name }}</span>
                            </div>
                            <div class="text-muted" style="font-size:0.75rem; display:flex; align-items:center; gap:6px; margin-top:2px;">
                                <span>{{ $category->products_count }} products</span>
                                @if($category->children_count > 0)
                                    <span style="font-size:0.68rem; font-weight:600; padding:2px 7px; border-radius:10px; background:#f4f4f5; color:#52525b; border:1px solid #e4e4e7;">{{ $category->children_count }} {{ Str::plural('sub-category', $category->children_count) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 align-center" style="flex-shrink:0;">
                        {{-- Quick Interactive Status Toggle Icon --}}
                        <button type="button" 
                            class="action-icon-btn status-icon-btn {{ $category->is_active ? 'is-active' : 'is-inactive' }}"
                            id="status-btn-{{ $category->id }}"
                            data-id="{{ $category->id }}"
                            data-url="{{ route('admin.categories.toggle-status', $category) }}"
                            data-active="{{ $category->is_active ? '1' : '0' }}"
                            onclick="toggleCategoryStatus(this, '{{ route('admin.categories.toggle-status', $category) }}')"
                            title="{{ $category->is_active ? 'Active — Click to deactivate' : 'Inactive — Click to activate' }}"
                            aria-label="{{ $category->is_active ? 'Active' : 'Inactive' }}">
                            <svg class="status-svg-active" style="{{ $category->is_active ? '' : 'display:none;' }}" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="5" width="22" height="14" rx="7" ry="7" fill="#18181b"></rect>
                                <circle cx="16" cy="12" r="3.5" fill="#22c55e" stroke="#22c55e"></circle>
                            </svg>
                            <svg class="status-svg-inactive" style="{{ $category->is_active ? 'display:none;' : '' }}" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#a1a1aa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="5" width="22" height="14" rx="7" ry="7" fill="#f4f4f5"></rect>
                                <circle cx="8" cy="12" r="3.5" fill="#a1a1aa" stroke="#a1a1aa"></circle>
                            </svg>
                        </button>

                        {{-- Edit Icon Button --}}
                        <button type="button" 
                            class="action-icon-btn edit-icon-btn" 
                            title="Edit Category"
                            aria-label="Edit Category"
                            onclick="openEditCategoryModal({
                                id: {{ $category->id }},
                                name: @js($category->name),
                                description: @js($category->description ?? ''),
                                parent_id: @js($category->parent_id),
                                forbidden_ids: @js($category->getRecursiveIds()),
                                is_active: {{ $category->is_active ? 'true' : 'false' }},
                                update_url: '{{ route('admin.categories.update', $category) }}'
                            })">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>

                        {{-- Delete Icon Button (Protected Parent or Normal Trash) --}}
                        @if($category->children_count > 0)
                            <button type="button" 
                                class="action-icon-btn delete-icon-btn is-disabled" 
                                onclick="alert('Cannot delete \'{{ addslashes($category->name) }}\' because it has {{ $category->children_count }} sub-category(ies) under it.\n\nPlease reassign or delete the sub-categories first.')" 
                                title="Cannot delete: contains {{ $category->children_count }} sub-category(ies)"
                                aria-label="Cannot delete category">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#a1a1aa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                            </button>
                        @else
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline; margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    class="action-icon-btn delete-icon-btn" 
                                    onclick="return confirm('Are you sure you want to delete this category?')" 
                                    title="Delete Category"
                                    aria-label="Delete Category">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state" style="padding:24px;"><p class="text-muted">No categories yet</p></div>
            @endforelse
        </div>
    </div>
</div>

{{-- Edit Category Modal --}}
<div id="editCategoryModal" style="display:none; position:fixed; inset:0; z-index:99999; background:rgba(0,0,0,0.55); backdrop-filter:blur(4px); -webkit-backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:16px;" onclick="handleModalOverlayClick(event)">
    <div class="card" style="max-width:520px; width:100%; box-shadow:0 24px 48px rgba(0,0,0,0.25); animation:modalFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1); margin:0;" onclick="event.stopPropagation();">
        <div class="card-header d-flex justify-between align-center" style="padding:16px 22px; border-bottom:1px solid var(--border-color);">
            <div style="display:flex; align-items:center; gap:8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                <h3 style="font-size:1.05rem; margin:0; font-weight:700;">Edit Category</h3>
            </div>
            <button type="button" onclick="closeEditCategoryModal()" class="btn btn-sm btn-secondary" style="border:none; background:transparent; font-size:1.2rem; line-height:1; cursor:pointer; padding:4px 8px; color:var(--text-secondary);" title="Close">✕</button>
        </div>
        <div class="card-body" style="padding:22px;">
            <form id="editCategoryForm" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_category_id">

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600; font-size:0.85rem;">Name *</label>
                    <input type="text" name="name" id="edit_category_name" class="form-control" required style="font-size:0.92rem;">
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600; font-size:0.85rem;">Description</label>
                    <textarea name="description" id="edit_category_description" class="form-control" rows="3" style="font-size:0.88rem; resize:vertical;" placeholder="Optional description..."></textarea>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600; font-size:0.85rem;">Parent Category</label>
                    <select name="parent_id" id="edit_category_parent_id" class="form-control" style="font-size:0.9rem;">
                        <option value="">None (Top Level)</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" class="edit-parent-opt" data-cat-id="{{ $cat->id }}">{{ $cat->full_path }}</option>
                        @endforeach
                    </select>
                    <span class="text-muted" style="font-size:0.75rem; display:block; margin-top:4px;">A category cannot be its own parent or choose any of its sub-categories.</span>
                </div>

                <div class="form-group mb-4" style="background:var(--bg-secondary); padding:14px 16px; border-radius:8px; border:1px solid var(--border-color);">
                    <label class="form-check" style="margin:0; display:flex; align-items:flex-start; gap:10px; cursor:pointer;">
                        <input type="checkbox" name="is_active" id="edit_category_is_active" value="1" style="width:18px; height:18px; accent-color:#000000; cursor:pointer; margin-top:2px;">
                        <div>
                            <span class="form-label" style="margin:0; font-weight:600; font-size:0.88rem; cursor:pointer;">Active</span>
                            <span class="text-muted" style="display:block; font-size:0.75rem; margin-top:2px;">When active, this category is visible in navigation filters and shop browsing.</span>
                        </div>
                    </label>
                </div>

                <div class="d-flex justify-end gap-2" style="border-top:1px solid var(--border-color); padding-top:16px;">
                    <button type="button" onclick="closeEditCategoryModal()" class="btn btn-secondary" style="padding:8px 18px; font-weight:600;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="padding:8px 22px; font-weight:700;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toast Notification Helper
    let toastTimeout = null;
    function showCategoryToast(message, isSuccess = true) {
        const toast = document.getElementById('categoryToast');
        const msg = document.getElementById('categoryToastMsg');
        if (!toast || !msg) return;

        msg.textContent = message;
        toast.querySelector('span:first-child').textContent = isSuccess ? '✓' : '⚠️';
        toast.querySelector('span:first-child').style.color = isSuccess ? '#22c55e' : '#f59e0b';

        toast.classList.add('show');
        if (toastTimeout) clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 2600);
    }

    // Toggle Category Status (Active <-> Inactive)
    async function toggleCategoryStatus(button, url) {
        if (!button || button.classList.contains('loading')) return;

        button.classList.add('loading');
        const svgActive = button.querySelector('.status-svg-active');
        const svgInactive = button.querySelector('.status-svg-inactive');

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token || ''
                }
            });

            const data = await res.json();

            if (res.ok && data.success) {
                const isActive = Boolean(data.is_active);
                button.dataset.active = isActive ? '1' : '0';

                if (isActive) {
                    button.classList.remove('is-inactive');
                    button.classList.add('is-active');
                    if (svgActive) svgActive.style.display = '';
                    if (svgInactive) svgInactive.style.display = 'none';
                    button.title = 'Active — Click to deactivate';
                    button.setAttribute('aria-label', 'Active');
                } else {
                    button.classList.remove('is-active');
                    button.classList.add('is-inactive');
                    if (svgActive) svgActive.style.display = 'none';
                    if (svgInactive) svgInactive.style.display = '';
                    button.title = 'Inactive — Click to activate';
                    button.setAttribute('aria-label', 'Inactive');
                }

                showCategoryToast(data.message || 'Status updated successfully!');
            } else {
                throw new Error(data.message || 'Failed to update category status.');
            }
        } catch (err) {
            console.error(err);
            showCategoryToast(err.message || 'Error updating status', false);
        } finally {
            button.classList.remove('loading');
        }
    }

    // Open Edit Category Modal
    function openEditCategoryModal(data) {
        const modal = document.getElementById('editCategoryModal');
        const form = document.getElementById('editCategoryForm');
        if (!modal || !form) return;

        form.action = data.update_url;
        document.getElementById('edit_category_id').value = data.id;
        document.getElementById('edit_category_name').value = data.name || '';
        document.getElementById('edit_category_description').value = data.description || '';
        document.getElementById('edit_category_is_active').checked = Boolean(data.is_active);

        const parentSelect = document.getElementById('edit_category_parent_id');
        if (parentSelect) {
            parentSelect.value = data.parent_id !== null && data.parent_id !== undefined ? String(data.parent_id) : '';

            // Disable selecting itself or any of its descendants as parent
            const forbiddenIds = Array.isArray(data.forbidden_ids) ? data.forbidden_ids.map(Number) : [Number(data.id)];
            const options = parentSelect.querySelectorAll('.edit-parent-opt');
            options.forEach(opt => {
                const optId = Number(opt.dataset.catId);
                if (forbiddenIds.includes(optId)) {
                    opt.disabled = true;
                    opt.style.display = 'none';
                } else {
                    opt.disabled = false;
                    opt.style.display = '';
                }
            });
        }

        modal.style.display = 'flex';
        document.getElementById('edit_category_name').focus();
    }

    // Close Edit Category Modal
    function closeEditCategoryModal() {
        const modal = document.getElementById('editCategoryModal');
        if (modal) modal.style.display = 'none';
    }

    function handleModalOverlayClick(e) {
        if (e.target.id === 'editCategoryModal') {
            closeEditCategoryModal();
        }
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditCategoryModal();
        }
    });

    // Bulk Category Selection & Deletion Logic
    function updateCategoryBulkToolbar() {
        const checkboxes = document.querySelectorAll('.category-select-checkbox');
        const checked = document.querySelectorAll('.category-select-checkbox:checked');
        const selectAll = document.getElementById('selectAllCategories');
        const toolbar = document.getElementById('categoryBulkToolbar');
        const countSpan = document.getElementById('categorySelectedCount');
        const btnCountSpan = document.getElementById('btnDeleteSelectedCount');

        const total = checkboxes.length;
        const count = checked.length;

        if (countSpan) countSpan.textContent = count;
        if (btnCountSpan) btnCountSpan.textContent = count;

        if (toolbar) {
            toolbar.style.display = count > 0 ? 'flex' : 'none';
        }

        if (selectAll) {
            selectAll.checked = total > 0 && count === total;
            selectAll.indeterminate = count > 0 && count < total;
        }

        // Highlight selected rows
        checkboxes.forEach(cb => {
            const row = document.getElementById(`category-row-${cb.value}`);
            if (row) {
                if (cb.checked) {
                    row.style.background = 'rgba(0, 0, 0, 0.03)';
                } else {
                    row.style.background = '';
                }
            }
        });
    }

    function toggleSelectAllCategories(master) {
        const checkboxes = document.querySelectorAll('.category-select-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = master.checked;
        });
        updateCategoryBulkToolbar();
    }

    function clearSelectedCategories() {
        const checkboxes = document.querySelectorAll('.category-select-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = false;
        });
        const selectAll = document.getElementById('selectAllCategories');
        if (selectAll) {
            selectAll.checked = false;
            selectAll.indeterminate = false;
        }
        updateCategoryBulkToolbar();
    }

    function executeBulkDeleteCategories() {
        const checked = document.querySelectorAll('.category-select-checkbox:checked');
        const count = checked.length;
        if (count === 0) return;

        const confirmMsg = `Are you sure you want to permanently delete the ${count} selected category(ies)?`;
        if (!confirm(confirmMsg)) {
            return;
        }

        const form = document.getElementById('bulkDeleteCategoriesForm');
        const container = document.getElementById('bulkCategoryInputsContainer');
        if (!form || !container) return;

        container.innerHTML = '';
        checked.forEach(cb => {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'category_ids[]';
            hidden.value = cb.value;
            container.appendChild(hidden);
        });

        form.submit();
    }
</script>
@endpush
@endsection
