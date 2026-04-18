<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

/**
 * Modelo para anuncios y eventos
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string|null $image
 * @property Carbon $event_date
 * @property string|null $location
 * @property string|null $link
 * @property bool $is_active
 * @property Carbon|null $expires_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
#[Fillable(['title', 'description', 'image', 'event_date', 'location', 'link', 'is_active', 'expires_at'])]
#[Hidden(['created_at', 'updated_at'])]
class Announcement extends Model
{
    use HasFactory;

    /**
     * Casts de atributos
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'event_date' => 'date',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Scope para filtrar anuncios visibles (activos y no expirados)
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function (Builder $q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', Date::now());
            });
    }

    /**
     * Determina si el anuncio está expirado
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Obtiene la URL de la imagen o null
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset("storage/{$this->image}") : null;
    }
}
