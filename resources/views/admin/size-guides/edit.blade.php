@extends('layouts.admin')

@section('title', 'Edit Size Guide')

@push('styles')
<style>
    .grid-table {
        width: 100%;
        border-collapse: collapse;
    }
    .grid-table th {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #334155;
    }
    .grid-table td {
        border: 1px solid #e2e8f0;
        padding: 8px 10px;
        background: #ffffff;
    }
    .grid-input {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        font-size: 0.85rem;
        text-align: center;
        transition: border-color 0.15s;
    }
    .grid-input:focus {
        border-color: #000000;
        outline: none;
    }
    .grid-name-input {
        text-align: left;
        font-weight: 600;
    }
    .btn-remove-row {
        background: none;
        border: none;
        color: #ef4444;
        cursor: pointer;
        padding: 4px 8px;
        font-size: 1rem;
        line-height: 1;
        border-radius: 4px;
    }
    .btn-remove-row:hover {
        background: #fef2f2;
    }
</style>
@endpush

@section('content')
<div class="admin-header">
    <div>
        <h1>Edit Size Guide: {{ $sizeGuide->name }}</h1>
        <p class="text-muted" style="font-size:0.875rem;margin-top:4px;">Update measurement points and size dimensions for this profile.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.size-guides.index') }}" class="btn btn-secondary">← Back to Size Guides</a>
        <a href="{{ route('admin.size-guides.create') }}" class="btn btn-secondary">+ Add Another</a>
    </div>
</div>

<form action="{{ route('admin.size-guides.update', $sizeGuide) }}" method="POST" id="size-guide-form">
    @csrf
    @method('PUT')

    <div class="two-col" style="grid-template-columns: 2fr 1fr; gap: 24px;">
        {{-- Left: Main Details & Measurements Table --}}
        <div>
            <div class="card mb-4">
                <div class="card-header"><h3 style="font-size:1rem;">Guide Information</h3></div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label class="form-label" for="name">Guide Title <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $sizeGuide->name) }}" placeholder="e.g. Men's Hoodie Size Guide (Unisex Fit)" required>
                        @error('name') <div class="text-danger" style="font-size:0.8rem;margin-top:4px;">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-row" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" class="mb-3">
                        <div class="form-group">
                            <label class="form-label" for="category_id">Category</label>
                            <select id="category_id" name="category_id" class="form-control">
                                <option value="">-- Global / Any Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $sizeGuide->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-muted" style="font-size:0.75rem;">Products in this category will automatically inherit this guide.</span>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="fit_type">Fit Silhouette / Type</label>
                            <input type="text" id="fit_type" name="fit_type" class="form-control" value="{{ old('fit_type', $sizeGuide->fit_type) }}" placeholder="e.g. Unisex Fit, Standard Fit, Oversized Fit">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" for="description">Customer Measuring Notes / Instructions</label>
                        <textarea id="description" name="description" class="form-control" rows="2" placeholder="e.g. Pistis luxury hoodie sizing in centimeters. For an oversized drape, we recommend choosing your regular size.">{{ old('description', $sizeGuide->description) }}</textarea>
                    </div>

                    <div class="form-group mb-0">
                        <label class="form-label" for="sizes_input">Size Columns (comma-separated) <span class="text-danger">*</span></label>
                        <input type="text" id="sizes_input" name="sizes_input" class="form-control" value="{{ old('sizes_input', implode(', ', $sizes)) }}" placeholder="XXS, XS, S, M, L, XL, 2XL" onchange="syncSizeColumns()">
                        <span class="text-muted" style="font-size:0.75rem;">Modify sizes separated by commas to add or remove size columns.</span>
                    </div>
                </div>
            </div>

            {{-- Measurements Grid --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-between align-center">
                    <div>
                        <h3 style="font-size:1rem;margin:0;">Dynamic Measurement Matrix</h3>
                        <span class="text-muted" style="font-size:0.8rem;">Enter garment dimensions for each size point</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="addMeasurementRow()">+ Add Metric Row</button>
                </div>
                <div class="card-body" style="padding:0;overflow-x:auto;">
                    <table class="grid-table" id="measurements-grid-table">
                        <thead id="grid-thead">
                            <tr>
                                <th style="width:200px;text-align:left;">Measurement (Metric)</th>
                                @foreach($sizes as $sz)
                                    <th class="size-col-th" data-size="{{ $sz }}" style="min-width:68px;">{{ $sz }}</th>
                                @endforeach
                                <th style="width:44px;text-align:center;"></th>
                            </tr>
                        </thead>
                        <tbody id="grid-tbody">
                            @php
                                $existingRows = $sizeGuide->measurements ?? [];
                            @endphp
                            @forelse($existingRows as $rIdx => $row)
                                <tr data-row-index="{{ $rIdx }}">
                                    <td>
                                        <input type="text" name="measurements[{{ $rIdx }}][name]" class="grid-input grid-name-input" value="{{ $row['name'] ?? '' }}" placeholder="e.g. Body Length" required>
                                        <input type="hidden" name="measurements[{{ $rIdx }}][unit]" value="{{ $row['unit'] ?? 'cm' }}">
                                    </td>
                                    @foreach($sizes as $sz)
                                        <td class="size-col-td" data-size="{{ $sz }}">
                                            <input type="text" name="measurements[{{ $rIdx }}][values][{{ $sz }}]" class="grid-input" value="{{ $row['values'][$sz] ?? '' }}">
                                        </td>
                                    @endforeach
                                    <td style="text-align:center;">
                                        <button type="button" class="btn-remove-row" onclick="removeMeasurementRow(this)" title="Remove row">✕</button>
                                    </td>
                                </tr>
                            @empty
                                <tr data-row-index="0">
                                    <td>
                                        <input type="text" name="measurements[0][name]" class="grid-input grid-name-input" value="Body Length" placeholder="e.g. Body Length" required>
                                        <input type="hidden" name="measurements[0][unit]" value="cm">
                                    </td>
                                    @foreach($sizes as $sz)
                                        <td class="size-col-td" data-size="{{ $sz }}">
                                            <input type="text" name="measurements[0][values][{{ $sz }}]" class="grid-input" value="">
                                        </td>
                                    @endforeach
                                    <td style="text-align:center;">
                                        <button type="button" class="btn-remove-row" onclick="removeMeasurementRow(this)" title="Remove row">✕</button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="padding:12px 18px;background:#fafafa;border-top:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;">
                    <span class="text-muted" style="font-size:0.8rem;">💡 Tip: Measurements are automatically converted to inches on the storefront when customers toggle the INCH / CM switch.</span>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="addMeasurementRow()">+ Add Metric Row</button>
                </div>
            </div>
        </div>

        {{-- Right: Settings & Actions --}}
        <div>
            <div class="card mb-4">
                <div class="card-header"><h3 style="font-size:1rem;">Publishing & Display</h3></div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label class="form-label" for="default_unit">Input Unit</label>
                        <select id="default_unit" name="default_unit" class="form-control">
                            <option value="cm" {{ old('default_unit', $sizeGuide->default_unit) === 'cm' ? 'selected' : '' }}>Centimeters (CM)</option>
                            <option value="in" {{ old('default_unit', $sizeGuide->default_unit) === 'in' ? 'selected' : '' }}>Inches (IN)</option>
                        </select>
                        <span class="text-muted" style="font-size:0.75rem;">Modal will offer instant conversion switch for customers.</span>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" for="sort_order">Display Sort Priority</label>
                        <input type="number" id="sort_order" name="sort_order" class="form-control" value="{{ old('sort_order', $sizeGuide->sort_order) }}">
                        <span class="text-muted" style="font-size:0.75rem;">Lower numbers appear first in modal tabs.</span>
                    </div>

                    <div class="form-group mb-4">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $sizeGuide->is_active) ? 'checked' : '' }} style="width:18px;height:18px;">
                            <span style="font-weight:600;font-size:0.9rem;">Active & Visible in Store</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" style="padding:12px;font-weight:600;">Update Size Guide</button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    let rowIndex = {{ count($existingRows) > 0 ? count($existingRows) : 1 }};

    function getCurrentSizes() {
        const input = document.getElementById('sizes_input');
        if (!input) return [];
        return input.value.split(',').map(s => s.trim()).filter(s => s.length > 0);
    }

    function syncSizeColumns() {
        const sizes = getCurrentSizes();
        const thead = document.getElementById('grid-thead');
        const tbody = document.getElementById('grid-tbody');

        // Rebuild thead
        let headHtml = `<tr>
            <th style="width:200px;text-align:left;">Measurement (Metric)</th>`;
        sizes.forEach(sz => {
            headHtml += `<th class="size-col-th" data-size="${sz}" style="min-width:68px;">${sz}</th>`;
        });
        headHtml += `<th style="width:44px;text-align:center;"></th></tr>`;
        thead.innerHTML = headHtml;

        // Update each row in tbody
        const rows = tbody.querySelectorAll('tr');
        rows.forEach(tr => {
            const rIdx = tr.getAttribute('data-row-index');
            const nameInput = tr.querySelector('.grid-name-input');
            const metricName = nameInput ? nameInput.value : '';

            // Collect existing values
            const oldValues = {};
            tr.querySelectorAll('.size-col-td input').forEach(inp => {
                const match = inp.name.match(/\[values\]\[(.*?)\]/);
                if (match && match[1]) {
                    oldValues[match[1]] = inp.value;
                }
            });

            let rowHtml = `<td>
                <input type="text" name="measurements[${rIdx}][name]" class="grid-input grid-name-input" value="${metricName}" placeholder="e.g. Body Length" required>
                <input type="hidden" name="measurements[${rIdx}][unit]" value="cm">
            </td>`;

            sizes.forEach(sz => {
                const val = oldValues[sz] !== undefined ? oldValues[sz] : '';
                rowHtml += `<td class="size-col-td" data-size="${sz}">
                    <input type="text" name="measurements[${rIdx}][values][${sz}]" class="grid-input" value="${val}">
                </td>`;
            });

            rowHtml += `<td style="text-align:center;">
                <button type="button" class="btn-remove-row" onclick="removeMeasurementRow(this)" title="Remove row">✕</button>
            </td>`;

            tr.innerHTML = rowHtml;
        });
    }

    function addMeasurementRow(name = '') {
        const tbody = document.getElementById('grid-tbody');
        const sizes = getCurrentSizes();
        const rIdx = rowIndex++;

        const tr = document.createElement('tr');
        tr.setAttribute('data-row-index', rIdx);

        let rowHtml = `<td>
            <input type="text" name="measurements[${rIdx}][name]" class="grid-input grid-name-input" value="${name}" placeholder="e.g. Metric Name" required>
            <input type="hidden" name="measurements[${rIdx}][unit]" value="cm">
        </td>`;

        sizes.forEach(sz => {
            rowHtml += `<td class="size-col-td" data-size="${sz}">
                <input type="text" name="measurements[${rIdx}][values][${sz}]" class="grid-input" value="">
            </td>`;
        });

        rowHtml += `<td style="text-align:center;">
            <button type="button" class="btn-remove-row" onclick="removeMeasurementRow(this)" title="Remove row">✕</button>
        </td>`;

        tr.innerHTML = rowHtml;
        tbody.appendChild(tr);
    }

    function removeMeasurementRow(btn) {
        const tbody = document.getElementById('grid-tbody');
        if (tbody.querySelectorAll('tr').length <= 1) {
            alert('A size guide requires at least one measurement point.');
            return;
        }
        btn.closest('tr').remove();
    }
</script>
@endpush
@endsection
