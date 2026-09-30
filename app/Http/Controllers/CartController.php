<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService
    ) {}

    public function index()
    {
        $cart = $this->cartService->getCart();
        $totals = $this->cartService->getCartTotals();

        return view('cart.index', compact('cart', 'totals'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
            'color' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:50',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->is_in_stock) {
            return back()->with('error', 'This product is out of stock.');
        }

        $color = $request->input('color');
        // If product has colors defined and no color was selected, pick the first color as default
        if (empty($color) && !empty($product->colors_list)) {
            $color = $product->colors_list[0]['name'] ?? null;
        }

        $size = $request->input('size');
        // If product has sizes defined and no size was selected, pick the first size as default
        if (empty($size) && !empty($product->sizes_list)) {
            $size = $product->sizes_list[0] ?? null;
        }

        $this->cartService->addItem($product, $request->input('quantity', 1), $color, $size);

        $notices = array_filter([$color, $size ? "Size: {$size}" : null]);
        $detailsStr = !empty($notices) ? " (" . implode(' · ', $notices) . ")" : "";

        return redirect()->route('cart.index')->with('success', "{$product->name}{$detailsStr} added to cart!");
    }

    public function update(Request $request, int $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = $this->cartService->getCart();
        $item = $cart->items()->findOrFail($itemId);

        $this->cartService->updateItem($item, $request->quantity);

        return redirect()->route('cart.index')->with('success', 'Cart updated!');
    }

    public function destroy(int $itemId)
    {
        $cart = $this->cartService->getCart();
        $item = $cart->items()->findOrFail($itemId);

        $this->cartService->removeItem($item);

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}
