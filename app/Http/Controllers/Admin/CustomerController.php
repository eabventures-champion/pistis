<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->input('view', 'all');
        $query = Customer::query();

        if ($view === 'active') {
            $query->active();
        } elseif ($view === 'archived') {
            $query->archived();
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $counts = [
            'all' => Customer::count(),
            'active' => Customer::active()->count(),
            'archived' => Customer::archived()->count(),
        ];

        $customers = $query->withCount('orders')->latest()->paginate(20)->withQueryString();

        return view('admin.customers.index', compact('customers', 'counts', 'view'));
    }

    public function show(Customer $customer)
    {
        $customer->load('orders');
        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Archive an individual customer
     */
    public function archive(Customer $customer)
    {
        $customer->archive();
        return back()->with('success', "Customer {$customer->full_name} has been moved to Archive.");
    }

    /**
     * Unarchive / Restore an individual customer
     */
    public function unarchive(Customer $customer)
    {
        $customer->unarchive();
        return back()->with('success', "Customer {$customer->full_name} has been restored from Archive.");
    }

    /**
     * Delete an individual customer permanently
     */
    public function destroy(Customer $customer)
    {
        $name = $customer->full_name;

        // Clean up associated cart if exists
        Cart::where('customer_id', $customer->id)->delete();

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', "Customer {$name} has been permanently deleted.");
    }

    /**
     * Archive ALL active customers
     */
    public function archiveAll(Request $request)
    {
        $count = Customer::active()->count();

        if ($count === 0) {
            return back()->with('info', 'There are no active customers to archive.');
        }

        Customer::active()->update(['archived_at' => now()]);

        return back()->with('success', "All {$count} customer(s) have been successfully archived.");
    }

    /**
     * Restore ALL archived customers
     */
    public function unarchiveAll(Request $request)
    {
        $count = Customer::archived()->count();

        if ($count === 0) {
            return back()->with('info', 'There are no archived customers to restore.');
        }

        Customer::archived()->update(['archived_at' => null]);

        return back()->with('success', "All {$count} archived customer(s) have been restored.");
    }

    /**
     * Delete ALL customers (or all within current scope)
     */
    public function destroyAll(Request $request)
    {
        $scope = $request->input('scope', 'all');

        $query = Customer::query();
        if ($scope === 'archived') {
            $query->archived();
        } elseif ($scope === 'active') {
            $query->active();
        }

        $count = $query->count();

        if ($count === 0) {
            return back()->with('info', 'There are no customers to delete.');
        }

        $customerIds = (clone $query)->pluck('id');

        // Delete related carts
        Cart::whereIn('customer_id', $customerIds)->delete();

        // Delete customers
        $query->delete();

        $scopeLabel = $scope === 'all' ? 'all' : ($scope === 'archived' ? 'all archived' : 'all active');
        return redirect()->route('admin.customers.index')
            ->with('success', "Successfully deleted {$count} {$scopeLabel} customer(s).");
    }

    /**
     * Process bulk action on selected customers
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'customer_ids' => 'required|array',
            'customer_ids.*' => 'exists:customers,id',
            'action' => 'required|in:archive,unarchive,delete',
        ]);

        $ids = $validated['customer_ids'];
        $count = count($ids);

        switch ($validated['action']) {
            case 'archive':
                Customer::whereIn('id', $ids)->update(['archived_at' => now()]);
                $message = "{$count} customer(s) successfully archived.";
                break;

            case 'unarchive':
                Customer::whereIn('id', $ids)->update(['archived_at' => null]);
                $message = "{$count} customer(s) successfully restored.";
                break;

            case 'delete':
                Cart::whereIn('customer_id', $ids)->delete();
                Customer::whereIn('id', $ids)->delete();
                $message = "{$count} customer(s) permanently deleted.";
                break;
        }

        return back()->with('success', $message);
    }
}
