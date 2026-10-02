<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartService
{
    /**
     * Get or create a cart for the current session/customer
     */
    public function getCart(): Cart
    {
        $customerId = auth('customer')->id();
        $sessionId = session()->getId();

        if ($customerId) {
            $cart = Cart::where('customer_id', $customerId)->first();
            if (!$cart) {
                // Check if there's a session cart to merge
                $sessionCart = Cart::where('session_id', $sessionId)->first();
                if ($sessionCart) {
                    $sessionCart->update(['customer_id' => $customerId, 'session_id' => null]);
                    return $sessionCart->load('items.product');
                }
                $cart = Cart::create(['customer_id' => $customerId]);
            }
            return $cart->load('items.product');
        }

        $cart = Cart::where('session_id', $sessionId)->first();
        if (!$cart) {
            $cart = Cart::create(['session_id' => $sessionId]);
        }

        return $cart->load('items.product');
    }

    /**
     * Add a product to the cart
     */
    public function addItem(Product $product, int $quantity = 1, ?string $color = null, ?string $size = null): CartItem
    {
        $cart = $this->getCart();

        $query = $cart->items()->where('product_id', $product->id);
        if ($color !== null && $color !== '') {
            $query->where('color', $color);
        } else {
            $query->whereNull('color');
        }

        if ($size !== null && $size !== '') {
            $query->where('size', $size);
        } else {
            $query->whereNull('size');
        }
        $existingItem = $query->first();

        // Calculate price based on selected color (or product fallback)
        $itemPrice = $product->getPriceForColor($color);

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $quantity;
            if ($newQuantity > $product->stock_quantity) {
                $newQuantity = $product->stock_quantity;
            }
            $existingItem->update([
                'quantity' => $newQuantity,
                'price' => $itemPrice,
            ]);
            return $existingItem->fresh();
        }

        $quantity = min($quantity, $product->stock_quantity);

        return $cart->items()->create([
            'product_id' => $product->id,
            'color' => $color ?: null,
            'size' => $size ?: null,
            'quantity' => $quantity,
            'price' => $itemPrice,
        ]);
    }

    /**
     * Update item quantity
     */
    public function updateItem(CartItem $item, int $quantity): CartItem
    {
        if ($quantity <= 0) {
            $item->delete();
            return $item;
        }

        $maxQuantity = $item->product->stock_quantity;
        $quantity = min($quantity, $maxQuantity);

        $item->update(['quantity' => $quantity]);
        return $item->fresh();
    }

    /**
     * Remove an item from the cart
     */
    public function removeItem(CartItem $item): void
    {
        $item->delete();
    }

    /**
     * Clear the entire cart
     */
    public function clearCart(): void
    {
        $cart = $this->getCart();
        $cart->items()->delete();
    }

    /**
     * Get cart totals
     */
    public function getCartTotals(): array
    {
        $cart = $this->getCart();

        $subtotal = $cart->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $tax = 0; // Can be configured later
        $shipping = 0; // Can be configured later
        $total = $subtotal + $tax + $shipping;

        return [
            'subtotal' => round($subtotal, 2),
            'tax' => round($tax, 2),
            'shipping' => round($shipping, 2),
            'total' => round($total, 2),
            'item_count' => $cart->items->sum('quantity'),
        ];
    }
}
