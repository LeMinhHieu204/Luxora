<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;
use App\Support\StorefrontData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        foreach (StorefrontData::products() as $source) {
            $slug = Str::slug($source['name']);
            $product = Product::updateOrCreate(['slug' => $slug], [
                'name' => $source['name'], 'category' => $source['category'], 'price_cents' => (int) round($source['price'] * 100),
                'old_price_cents' => isset($source['oldPrice']) ? (int) round($source['oldPrice'] * 100) : null,
                'image' => $source['image'], 'alt_text' => $source['alt'], 'description' => 'A considered Luxora product.', 'is_active' => true,
            ]);
            ProductVariant::updateOrCreate(['sku' => strtoupper(Str::limit($slug, 55, '').'-STD')], ['product_id' => $product->id, 'name' => 'Default', 'stock' => 20]);
        }
    }
}
