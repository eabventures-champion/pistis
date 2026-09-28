<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->input('view', 'all');
        $query = Order::with('customer');

        if ($view === 'active') {
            $query->active();
        } elseif ($view === 'archived') {
            $query->archived();
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($paymentStatus = $request->input('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }

        $counts = [
            'all' => Order::count(),
            'active' => Order::active()->count(),
            'archived' => Order::archived()->count(),
        ];

        $orders = $query->latest()->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders', 'counts', 'view'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'customer');
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order status updated!');
    }

    /**
     * Archive an order
     */
    public function archive(Order $order)
    {
        $order->archive();

        return back()->with('success', "Order {$order->order_number} has been moved to Archive.");
    }

    /**
     * Unarchive an order
     */
    public function unarchive(Order $order)
    {
        $order->unarchive();

        return back()->with('success', "Order {$order->order_number} has been restored from Archive.");
    }

    /**
     * Delete an order permanently
     */
    public function destroy(Order $order)
    {
        $orderNumber = $order->order_number;
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', "Order {$orderNumber} has been permanently deleted.");
    }

    /**
     * Process bulk actions on orders
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'action' => 'required|in:archive,unarchive,delete',
        ]);

        $orderIds = $validated['order_ids'];
        $count = count($orderIds);

        switch ($validated['action']) {
            case 'archive':
                Order::whereIn('id', $orderIds)->update([
                    'is_archived' => true,
                    'archived_at' => now(),
                ]);
                $message = "{$count} order(s) successfully archived.";
                break;

            case 'unarchive':
                Order::whereIn('id', $orderIds)->update([
                    'is_archived' => false,
                    'archived_at' => null,
                ]);
                $message = "{$count} order(s) successfully unarchived.";
                break;

            case 'delete':
                Order::whereIn('id', $orderIds)->delete();
                $message = "{$count} order(s) permanently deleted.";
                break;
        }

        return back()->with('success', $message);
    }
}
