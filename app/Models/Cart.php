<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

#[Fillable(['user_id'])]
#[Hidden(['created_at', 'updated_at'])]
class Cart extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get cart total via database query (avoids N+1).
     */
    public function getTotalAttribute(): float
    {
        if (! $this->exists) {
            return 0;
        }

        return (float) CartItem::where('cart_id', $this->id)
            ->join('products', 'cart_items.product_id', '=', 'products.id')
            ->sum(DB::raw('cart_items.quantity * products.price'));
    }

    public function getItemCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public static function getOrCreateForUser(User $user): static
    {
        // Use firstOrCreate to prevent race conditions
        return static::firstOrCreate(['user_id' => $user->id]);
    }
}
