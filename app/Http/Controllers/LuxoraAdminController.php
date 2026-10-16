<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LuxoraAdminController extends Controller
{
    public function index(): View
    {
        return view('luxora.admin.index', [
            'products' => Product::with('variants')->latest()->get(),
            'orders' => Order::with('items')->latest()->limit(50)->get(),
            'metrics' => ['products' => Product::where('is_active', true)->count(), 'orders' => Order::count(), 'pending' => Order::where('payment_status', 'pending')->count()],
        ]);
    }

    public function updateStock(Request $request, ProductVariant $variant): RedirectResponse
    {
        $data = $request->validate(['stock' => ['required', 'integer', 'min:0', 'max:1000000']]);
        $variant->update($data);
        return back()->with('success', 'Inventory updated.');
    }

    public function markPaid(Order $order): RedirectResponse
    {
        abort_if($order->status === 'cancelled', 409, 'Cancelled orders cannot be marked paid.');
        abort_if($order->payment_status === 'paid', 409, 'Order is already paid.');
        $order->update(['payment_status' => 'paid', 'status' => 'processing', 'paid_at' => now()]);
        return back()->with('success', 'Manual payment recorded. No provider was called.');
    }
}
