<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['parent.parent.parent', 'children'])
            ->withCount(['products', 'children'])
            ->orderBy('sort_order')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'nullable|boolean',
        ]);

        $baseSlug = Str::slug($validated['name']) ?: 'category';
        $slug = $baseSlug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }
        $validated['slug'] = $slug;
        $validated['is_active'] = $request->boolean('is_active', true);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'nullable|boolean',
        ]);

        if (isset($validated['parent_id'])) {
            $forbiddenParentIds = $category->getRecursiveIds();
            if (in_array((int) $validated['parent_id'], $forbiddenParentIds, true)) {
                $validated['parent_id'] = $category->parent_id;
            }
        }

        $baseSlug = Str::slug($validated['name']) ?: 'category';
        $slug = $baseSlug;
        $counter = 1;
        while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }
        $validated['slug'] = $slug;
        $validated['is_active'] = $request->boolean('is_active');

        $category->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'category' => $category->fresh('parent'),
                'message' => "Category \"{$category->name}\" updated successfully!",
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', "Category \"{$category->name}\" updated successfully!");
    }

    public function toggleStatus(Category $category)
    {
        $category->is_active = !$category->is_active;
        $category->save();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => (bool) $category->is_active,
                'category_id' => $category->id,
                'category_name' => $category->name,
                'message' => "Category \"{$category->name}\" is now " . ($category->is_active ? 'Active' : 'Inactive') . '.',
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', "Category \"{$category->name}\" is now " . ($category->is_active ? 'Active' : 'Inactive') . '.');
    }

    public function destroy(Category $category)
    {
        $subCount = $category->children()->count();
        if ($subCount > 0) {
            $msg = "Cannot delete category \"{$category->name}\" because it has {$subCount} sub-category(ies) under it. Please reassign or delete its sub-categories first.";
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                ], 422);
            }

            return redirect()->route('admin.categories.index')
                ->with('error', $msg);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', "Category \"{$category->name}\" deleted successfully!");
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $selectedIds = array_map('intval', $validated['category_ids']);
        if (empty($selectedIds)) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'No categories selected for deletion.');
        }

        $categories = Category::whereIn('id', $selectedIds)->get();

        // Enforce parent-protection rule:
        // If a category has sub-categories that are NOT included in this deletion set, block it.
        $blocked = [];
        foreach ($categories as $cat) {
            $hasRemainingChildren = Category::where('parent_id', $cat->id)
                ->whereNotIn('id', $selectedIds)
                ->exists();

            if ($hasRemainingChildren) {
                $blocked[] = "\"{$cat->name}\"";
            }
        }

        if (!empty($blocked)) {
            $names = implode(', ', $blocked);
            $msg = "Cannot delete {$names} because they have sub-category(ies) under them that are not selected for deletion. Please reassign or select their sub-categories first.";

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }

            return redirect()->route('admin.categories.index')->with('error', $msg);
        }

        // Delete bottom-up (deepest descendants first)
        $sortedCategories = $categories->sortByDesc(function ($cat) {
            return count($cat->getAncestors());
        });

        $count = 0;
        foreach ($sortedCategories as $cat) {
            $cat->delete();
            $count++;
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'count' => $count,
                'message' => "{$count} category(ies) deleted successfully!",
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', "{$count} category(ies) deleted successfully!");
    }
}
