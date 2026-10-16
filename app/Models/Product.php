<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['slug', 'name', 'category', 'description', 'price_cents', 'old_price_cents', 'image', 'alt_text', 'is_active'])]
class Product extends Model
{
    protected function casts(): array
    {
        return ['price_cents' => 'integer', 'old_price_cents' => 'integer', 'is_active' => 'boolean'];
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function toPresentationArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'category' => $this->category,
            'price' => $this->price_cents / 100,
            'oldPrice' => $this->old_price_cents ? $this->old_price_cents / 100 : null,
            'image' => $this->image,
            'alt' => $this->alt_text ?: $this->name,
            'stock' => (int) ($this->variants->sum('stock')),
            'variant_id' => $this->variants->first()?->id,
        ];
    }
}
