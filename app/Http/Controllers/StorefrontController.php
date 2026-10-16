<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function home(): View
    {
        $products = Product::where('is_active', true)->with('variants')->latest()->limit(12)->get()->map->toPresentationArray()->all();
        return view('luxora.home', ['pageTitle' => 'Elevate your style. Live with intention.', 'categories' => $this->categories(), 'products' => $products, 'reviews' => \App\Support\StorefrontData::reviews()]);
    }

    public function shop(Request $request): View
    {
        $query = trim($request->string('q')->toString());
        $category = trim($request->string('category')->toString());
        $products = Product::where('is_active', true)->with('variants')
            ->when($query !== '', fn ($q) => $q->where(fn ($q) => $q->where('name', 'like', '%'.$query.'%')->orWhere('category', 'like', '%'.$query.'%')))
            ->when($category !== '', fn ($q) => $q->where('category', $category))
            ->latest()->paginate(24)->through(fn (Product $product) => $product->toPresentationArray());
        return view('luxora.shop', ['pageTitle' => 'Shop Our Collection', 'categories' => $this->categories(), 'products' => $products, 'query' => $query, 'resultCount' => $products->total(), 'catalogTotal' => Product::where('is_active', true)->count()]);
    }

    public function product(string $slug): View
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->with('variants')->firstOrFail();
        return view('luxora.product', ['pageTitle' => $product->name, 'product' => $product->toPresentationArray(), 'variants' => $product->variants]);
    }

    public function about(): View { return view('luxora.about', ['pageTitle' => 'Passion for quality, focus on you.']); }

    public function cart(Request $request): View
    {
        $cart = $this->cartFor($request)->load('items.variant.product');
        return view('luxora.cart', ['pageTitle' => 'Your cart', 'cart' => $cart, 'total' => $this->cartTotal($cart)]);
    }

    public function addToCart(Request $request): RedirectResponse
    {
        $data = $request->validate(['variant_id' => ['required', 'integer', 'exists:product_variants,id'], 'quantity' => ['required', 'integer', 'min:1', 'max:99']]);
        $cart = $this->cartFor($request);
        $variant = ProductVariant::with('product')->findOrFail($data['variant_id']);
        $item = $cart->items()->firstOrNew(['product_variant_id' => $variant->id]);
        $quantity = ($item->exists ? $item->quantity : 0) + $data['quantity'];
        abort_if($quantity > $variant->stock, 409, 'Not enough stock is available.');
        $item->quantity = $quantity;
        $item->save();
        return redirect()->route('cart.show')->with('success', 'Added to cart.')
            ->withCookie(cookie('luxora_cart', $cart->session_key, 120));
    }

    public function updateCart(Request $request): RedirectResponse
    {
        $data = $request->validate(['items' => ['array'], 'items.*.id' => ['integer'], 'items.*.quantity' => ['integer', 'min:0', 'max:99']]);
        $cart = $this->cartFor($request);
        foreach ($data['items'] ?? [] as $itemData) {
            $item = $cart->items()->whereKey($itemData['id'])->with('variant')->first();
            if (!$item) continue;
            if ($itemData['quantity'] === 0) $item->delete();
            else { abort_if($itemData['quantity'] > $item->variant->stock, 409, 'Not enough stock is available.'); $item->update(['quantity' => $itemData['quantity']]); }
        }
        return back()->with('success', 'Cart updated.');
    }

    public function checkout(Request $request): View|RedirectResponse
    {
        $cart = $this->cartFor($request)->load('items.variant.product');
        if ($cart->items->isEmpty()) return redirect()->route('cart.show')->withErrors(['cart' => 'Your cart is empty.']);
        return view('luxora.checkout', ['pageTitle' => 'Checkout', 'cart' => $cart, 'total' => $this->cartTotal($cart)]);
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $data = $request->validate(['customer_name' => ['required', 'string', 'max:160'], 'email' => ['required', 'email:rfc', 'max:190'], 'address' => ['required', 'string', 'max:1000'], 'cart_key' => ['nullable', 'string', 'max:190']]);
        $order = app(DatabaseManager::class)->transaction(function () use ($request, $data) {
            $cart = $this->cartFor($request)->load('items.variant.product');
            abort_if($cart->items->isEmpty(), 409, 'Your cart is empty.');
            $total = 0;
            $locked = [];
            foreach ($cart->items as $item) {
                $variant = ProductVariant::whereKey($item->product_variant_id)->with('product')->lockForUpdate()->firstOrFail();
                abort_if($variant->stock < $item->quantity, 409, 'Stock changed. Please review your cart.');
                $total += $variant->product->price_cents * $item->quantity;
                $locked[$variant->id] = $variant;
            }
            $order = Order::create(['public_token' => Str::random(48), 'customer_name' => $data['customer_name'], 'email' => strtolower($data['email']), 'shipping_address' => ['address' => $data['address']], 'total_cents' => $total, 'payment_method' => 'manual', 'payment_status' => 'pending', 'status' => 'pending']);
            foreach ($cart->items as $item) {
                $variant = $locked[$item->product_variant_id];
                $variant->decrement('stock', $item->quantity);
                DB::table('inventory_movements')->insert([
                    'product_variant_id' => $variant->id,
                    'order_id' => $order->id,
                    'quantity_delta' => -$item->quantity,
                    'reason' => 'order_reserved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $order->items()->create(['product_variant_id' => $variant->id, 'product_name' => $variant->product->name, 'sku' => $variant->sku, 'quantity' => $item->quantity, 'unit_price_cents' => $variant->product->price_cents]);
            }
            $cart->update(['status' => 'converted']);
            $cart->items()->delete();
            return $order;
        });
        return redirect()->route('orders.success', ['order' => $order->id, 'token' => $order->public_token]);
    }

    public function cancel(Order $order, Request $request): RedirectResponse
    {
        $data = $request->validate(['token' => ['required', 'string', 'max:80']]);
        abort_unless(hash_equals($order->public_token, $data['token']), 404);

        app(DatabaseManager::class)->transaction(function () use ($order) {
            $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            if ($lockedOrder->status === 'cancelled') return;
            abort_if($lockedOrder->status !== 'pending' || $lockedOrder->payment_status !== 'pending', 409, 'Only unpaid pending orders can be cancelled.');

            foreach ($lockedOrder->items as $item) {
                if (!$item->product_variant_id) continue;
                $variant = ProductVariant::whereKey($item->product_variant_id)->lockForUpdate()->first();
                if (!$variant) continue;
                $variant->increment('stock', $item->quantity);
                DB::table('inventory_movements')->insert([
                    'product_variant_id' => $variant->id,
                    'order_id' => $lockedOrder->id,
                    'quantity_delta' => $item->quantity,
                    'reason' => 'order_cancelled',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $lockedOrder->update(['status' => 'cancelled', 'payment_status' => 'cancelled']);
        });

        return redirect()->route('orders.success', ['order' => $order->id, 'token' => $order->public_token])->with('success', 'Order cancelled and stock released.');
    }

    public function success(Order $order, Request $request): View
    {
        abort_unless(hash_equals($order->public_token, (string) $request->query('token')), 404);
        return view('luxora.success', ['pageTitle' => 'Order received', 'order' => $order->load('items')]);
    }

    private function cartFor(Request $request): Cart
    {
        $key = $request->input('cart_key') ?: $request->cookie('luxora_cart') ?: $request->session()->getId();
        return Cart::firstOrCreate(['session_key' => $key], ['status' => 'active']);
    }
    private function cartTotal(Cart $cart): int { return $cart->items->sum(fn ($item) => $item->variant->product->price_cents * $item->quantity); }
    private function categories(): array
    {
        $images = collect(\App\Support\StorefrontData::categories())->keyBy('name');
        return Product::where('is_active', true)->select('category')->selectRaw('count(*) as count')->groupBy('category')->get()->map(
            fn ($row) => ['name' => $row->category, 'count' => $row->count, 'image' => $images->get($row->category)['image'] ?? asset('assets/luxora/hero-neutral.jpg')]
        )->all();
    }
}
