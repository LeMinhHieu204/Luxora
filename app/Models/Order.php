<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['public_token', 'customer_name', 'email', 'shipping_address', 'total_cents', 'currency', 'status', 'payment_method', 'payment_status', 'paid_at'])]
class Order extends Model
{
    protected function casts(): array { return ['shipping_address' => 'array', 'total_cents' => 'integer', 'paid_at' => 'datetime']; }
    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
}
