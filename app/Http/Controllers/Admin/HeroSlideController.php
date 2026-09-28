<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::ordered()->get();
        return view('admin.hero-slides.index', compact('slides'));
    }

    public function create()
    {
        $nextOrder = (HeroSlide::max('sort_order') ?? 0) + 1;
        return view('admin.hero-slides.create', compact('nextOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'collection_name' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'title' => 'required|string|max:1000',
            'description' => 'nullable|string',
            'image' => 'required_without:image_url_input|nullable|image|max:10240',
            'image_url_input' => 'nullable|url|max:1000',
            'mobile_image' => 'nullable|image|max:10240',
            'mobile_image_url_input' => 'nullable|url|max:1000',
            'primary_button_text' => 'nullable|string|max:100',
            'primary_button_url' => 'nullable|string|max:500',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
            'display_duration' => 'nullable|integer|min:3|max:60',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? ((HeroSlide::max('sort_order') ?? 0) + 1);
        $validated['display_duration'] = $validated['display_duration'] ?? 7;
        $validated['primary_button_text'] = $validated['primary_button_text'] ?: 'SHOP COLLECTION';
        $validated['primary_button_url'] = $validated['primary_button_url'] ?: '/shop';

        // Handle Desktop Image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('hero', 'public');
        } elseif (!empty($request->input('image_url_input'))) {
            $validated['image'] = $request->input('image_url_input');
        }

        // Handle Mobile Image
        if ($request->hasFile('mobile_image')) {
            $validated['mobile_image'] = $request->file('mobile_image')->store('hero', 'public');
        } elseif (!empty($request->input('mobile_image_url_input'))) {
            $validated['mobile_image'] = $request->input('mobile_image_url_input');
        }

        unset($validated['image_url_input'], $validated['mobile_image_url_input']);

        HeroSlide::create($validated);

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Hero slide created successfully!');
    }

    public function edit(HeroSlide $heroSlide)
    {
        return view('admin.hero-slides.edit', compact('heroSlide'));
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $validated = $request->validate([
            'collection_name' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'title' => 'required|string|max:1000',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:10240',
            'image_url_input' => 'nullable|url|max:1000',
            'mobile_image' => 'nullable|image|max:10240',
            'mobile_image_url_input' => 'nullable|url|max:1000',
            'primary_button_text' => 'nullable|string|max:100',
            'primary_button_url' => 'nullable|string|max:500',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
            'display_duration' => 'nullable|integer|min:3|max:60',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? $heroSlide->sort_order;
        $validated['display_duration'] = $validated['display_duration'] ?? 7;
        $validated['primary_button_text'] = $validated['primary_button_text'] ?: 'SHOP COLLECTION';
        $validated['primary_button_url'] = $validated['primary_button_url'] ?: '/shop';

        // Handle Desktop Image
        if ($request->hasFile('image')) {
            if ($heroSlide->image && !str_starts_with($heroSlide->image, 'http')) {
                Storage::disk('public')->delete($heroSlide->image);
            }
            $validated['image'] = $request->file('image')->store('hero', 'public');
        } elseif (!empty($request->input('image_url_input'))) {
            $validated['image'] = $request->input('image_url_input');
        }

        // Handle Mobile Image
        if ($request->hasFile('mobile_image')) {
            if ($heroSlide->mobile_image && !str_starts_with($heroSlide->mobile_image, 'http')) {
                Storage::disk('public')->delete($heroSlide->mobile_image);
            }
            $validated['mobile_image'] = $request->file('mobile_image')->store('hero', 'public');
        } elseif (!empty($request->input('mobile_image_url_input'))) {
            $validated['mobile_image'] = $request->input('mobile_image_url_input');
        }

        unset($validated['image_url_input'], $validated['mobile_image_url_input']);

        $heroSlide->update($validated);

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Hero slide updated successfully!');
    }

    public function destroy(HeroSlide $heroSlide)
    {
        if ($heroSlide->image && !str_starts_with($heroSlide->image, 'http')) {
            Storage::disk('public')->delete($heroSlide->image);
        }
        if ($heroSlide->mobile_image && !str_starts_with($heroSlide->mobile_image, 'http')) {
            Storage::disk('public')->delete($heroSlide->mobile_image);
        }

        $heroSlide->delete();

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Hero slide deleted successfully!');
    }

    public function toggleStatus(HeroSlide $heroSlide)
    {
        $heroSlide->update(['is_active' => !$heroSlide->is_active]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $heroSlide->is_active,
                'message' => $heroSlide->is_active ? 'Slide activated' : 'Slide deactivated',
            ]);
        }

        return back()->with('success', 'Slide status updated!');
    }

    public function duplicate(HeroSlide $heroSlide)
    {
        $duplicate = $heroSlide->replicate();
        $duplicate->title = $heroSlide->title . ' (Copy)';
        $duplicate->sort_order = (HeroSlide::max('sort_order') ?? 0) + 1;
        $duplicate->is_active = false; // default duplicate to inactive for safety
        $duplicate->save();

        return redirect()->route('admin.hero-slides.index')
            ->with('success', 'Slide duplicated successfully as draft!');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:hero_slides,id',
        ]);

        foreach ($request->input('order') as $index => $id) {
            HeroSlide::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Slide order updated successfully!']);
    }
}
