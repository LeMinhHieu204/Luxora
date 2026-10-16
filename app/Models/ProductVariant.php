<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'sku', 'name', 'attributes', 'stock'])]
class ProductVariant extends Model
{
    protected function casts(): array { return ['attributes' => 'array', 'stock' => 'integer']; }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}
