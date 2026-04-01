<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'content', 'status'])]
#[Hidden(['created_at', 'updated_at'])]
class Comment extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'aprobado');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pendiente');
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rechazado');
    }

    // Helpers
    public function isPending(): bool
    {
        return $this->status === 'pendiente';
    }

    public function isApproved(): bool
    {
        return $this->status === 'aprobado';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rechazado';
    }
}
