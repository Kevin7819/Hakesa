<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Get cart total.
     *
     * @requires items.product to be eager-loaded to avoid N+1 queries.
     *           Use $cart->load('items.product') before accessing this accessor.
     */
    public function getTotalAttribute(): float
    {
        return $this->items->sum(function ($item) {
            return ($item->product?->price ?? 0) * $item->quantity;
        });
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
