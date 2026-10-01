<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SizeGuide;
use Illuminate\Http\Request;

class SizeGuideController extends Controller
{
    public function index()
    {
        $sizeGuides = SizeGuide::with('category')
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.size-guides.index', compact('sizeGuides'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $defaultSizes = ['XXS', 'XS', 'S', 'M', 'L', 'XL', '2XL'];

        return view('admin.size-guides.create', compact('categories', 'defaultSizes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'category_id'  => 'nullable|exists:categories,id',
            'fit_type'     => 'nullable|string|max:100',
            'description'  => 'nullable|string',
            'default_unit' => 'nullable|string|in:cm,in',
            'sizes_input'  => 'nullable|string',
            'measurements' => 'nullable|array',
            'is_active'    => 'nullable|boolean',
            'sort_order'   => 'nullable|integer',
        ]);

        $sizes = $this->parseSizes($request->input('sizes_input'));
        $measurements = $this->parseMeasurements($request->input('measurements', []), $sizes);

        $sizeGuide = SizeGuide::create([
            'name'         => $validated['name'],
            'category_id'  => $validated['category_id'] ?: null,
            'fit_type'     => $validated['fit_type'] ?: 'Standard Fit',
            'description'  => $validated['description'] ?? null,
            'default_unit' => $validated['default_unit'] ?: 'cm',
            'sizes'        => $sizes,
            'measurements' => $measurements,
            'is_active'    => $request->boolean('is_active', true),
            'sort_order'   => (int) ($validated['sort_order'] ?? 0),
        ]);

        return redirect()->route('admin.size-guides.index')->with('success', "Size Guide \"{$sizeGuide->name}\" created successfully.");
    }

    public function show(SizeGuide $sizeGuide)
    {
        return redirect()->route('admin.size-guides.edit', $sizeGuide);
    }

    public function edit(SizeGuide $sizeGuide)
    {
        $categories = Category::orderBy('name')->get();
        $sizes = $sizeGuide->sizes ?? ['XXS', 'XS', 'S', 'M', 'L', 'XL', '2XL'];

        return view('admin.size-guides.edit', compact('sizeGuide', 'categories', 'sizes'));
    }

    public function update(Request $request, SizeGuide $sizeGuide)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'category_id'  => 'nullable|exists:categories,id',
            'fit_type'     => 'nullable|string|max:100',
            'description'  => 'nullable|string',
            'default_unit' => 'nullable|string|in:cm,in',
            'sizes_input'  => 'nullable|string',
            'measurements' => 'nullable|array',
            'is_active'    => 'nullable|boolean',
            'sort_order'   => 'nullable|integer',
        ]);

        $sizes = $this->parseSizes($request->input('sizes_input'));
        $measurements = $this->parseMeasurements($request->input('measurements', []), $sizes);

        $sizeGuide->update([
            'name'         => $validated['name'],
            'category_id'  => $validated['category_id'] ?: null,
            'fit_type'     => $validated['fit_type'] ?: 'Standard Fit',
            'description'  => $validated['description'] ?? null,
            'default_unit' => $validated['default_unit'] ?: 'cm',
            'sizes'        => $sizes,
            'measurements' => $measurements,
            'is_active'    => $request->boolean('is_active', true),
            'sort_order'   => (int) ($validated['sort_order'] ?? 0),
        ]);

        return redirect()->route('admin.size-guides.index')->with('success', "Size Guide \"{$sizeGuide->name}\" updated successfully.");
    }

    public function destroy(SizeGuide $sizeGuide)
    {
        $name = $sizeGuide->name;
        $sizeGuide->delete();

        return redirect()->route('admin.size-guides.index')->with('success', "Size Guide \"{$name}\" deleted successfully.");
    }

    public function toggleStatus(SizeGuide $sizeGuide)
    {
        $sizeGuide->is_active = !$sizeGuide->is_active;
        $sizeGuide->save();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success'   => true,
                'is_active' => $sizeGuide->is_active,
                'message'   => "Status updated to " . ($sizeGuide->is_active ? 'Active' : 'Inactive'),
            ]);
        }

        return back()->with('success', "Status of \"{$sizeGuide->name}\" updated.");
    }

    private function parseSizes(?string $sizesInput): array
    {
        if (empty($sizesInput)) {
            return ['XXS', 'XS', 'S', 'M', 'L', 'XL', '2XL'];
        }

        $parts = array_map('trim', explode(',', $sizesInput));
        $cleaned = array_values(array_filter($parts, fn($s) => $s !== ''));

        return !empty($cleaned) ? $cleaned : ['XXS', 'XS', 'S', 'M', 'L', 'XL', '2XL'];
    }

    private function parseMeasurements(array $rawMeasurements, array $sizes): array
    {
        $result = [];

        foreach ($rawMeasurements as $row) {
            $name = trim($row['name'] ?? '');
            if ($name === '') {
                continue;
            }

            $unit = trim($row['unit'] ?? 'cm');
            $values = [];

            foreach ($sizes as $sz) {
                $val = trim($row['values'][$sz] ?? '');
                $values[$sz] = $val !== '' ? (is_numeric($val) ? (float) $val : $val) : null;
            }

            $result[] = [
                'name'   => $name,
                'unit'   => $unit,
                'values' => $values,
            ];
        }

        return $result;
    }
}
