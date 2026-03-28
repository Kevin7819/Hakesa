<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = ['user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function getTotalAttribute(): float
    {
        $this->loadMissing('items.product');

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
