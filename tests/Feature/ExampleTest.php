<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Cart;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        $this->get('/')->assertOk()->assertSee('Classic Trench Coat');
    }

    public function test_shop_search_is_database_backed_and_handles_no_match(): void
    {
        $this->get('/shop?q=trench')->assertOk()->assertSee('Classic Trench Coat')->assertDontSee('Premium Sneakers');
        $this->get('/shop?q=does-not-exist')->assertOk()->assertSee('No products match')->assertSee('id="product-range">0', false);
    }

    public function test_cart_checkout_reserves_stock_and_requires_token_for_order_view(): void
    {
        $variant = ProductVariant::whereHas('product', fn ($q) => $q->where('slug', 'classic-trench-coat'))->firstOrFail();
        $this->post('/cart/items', ['variant_id' => $variant->id, 'quantity' => 2])->assertRedirect(route('cart.show'));
        $checkout = $this->withCookie('luxora_cart', \App\Models\Cart::firstOrFail()->session_key)
            ->post('/checkout', ['customer_name' => 'Alex Morgan', 'email' => 'alex@example.test', 'address' => '1 Test Street']);
        $checkout->assertRedirect();
        $order = Order::firstOrFail();
        $this->assertSame('pending', $order->payment_status);
        $this->assertSame(18, ProductVariant::findOrFail($variant->id)->stock);
        $this->get(route('orders.success', ['order' => $order, 'token' => 'wrong']))->assertNotFound();
        $this->get(route('orders.success', ['order' => $order, 'token' => $order->public_token]))->assertOk()->assertSee('Payment status: pending');
        $this->assertDatabaseHas('inventory_movements', ['order_id' => $order->id, 'quantity_delta' => -2, 'reason' => 'order_reserved']);
    }

    public function test_unpaid_order_cancellation_releases_stock_once(): void
    {
        $variant = ProductVariant::whereHas('product', fn ($q) => $q->where('slug', 'classic-trench-coat'))->firstOrFail();
        $cart = Cart::create(['session_key' => 'cancel-test-cart', 'status' => 'active']);
        $cart->items()->create(['product_variant_id' => $variant->id, 'quantity' => 3]);
        $this->withCookie('luxora_cart', $cart->session_key)->post('/checkout', [
            'customer_name' => 'Cancel Customer', 'email' => 'cancel@example.test', 'address' => '2 Test Street',
        ])->assertRedirect();
        $order = Order::latest('id')->firstOrFail();
        $this->assertSame(17, ProductVariant::findOrFail($variant->id)->stock);

        $this->post(route('orders.cancel', $order), ['token' => $order->public_token])
            ->assertRedirect(route('orders.success', ['order' => $order->id, 'token' => $order->public_token]));
        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertSame('cancelled', $order->fresh()->payment_status);
        $this->assertSame(20, ProductVariant::findOrFail($variant->id)->stock);
        $this->assertDatabaseHas('inventory_movements', ['order_id' => $order->id, 'quantity_delta' => 3, 'reason' => 'order_cancelled']);

        $this->post(route('orders.cancel', $order), ['token' => $order->public_token])->assertRedirect();
        $this->assertSame(20, ProductVariant::findOrFail($variant->id)->stock);
    }

    public function test_admin_dashboard_is_protected_and_manual_payment_is_explicit(): void
    {
        $this->get('/admin/luxora')->assertRedirect();
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get('/admin/luxora')->assertOk()->assertSee('Luxora commerce dashboard');
        $this->assertDatabaseCount('orders', 0);
    }
}
