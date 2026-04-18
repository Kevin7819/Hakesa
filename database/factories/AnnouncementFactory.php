<?php

namespace Database\Factories;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Factory para Announcement
 */
class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition(): array
    {
        $titles = [
            'Gran Fiesta de Fin de Año',
            'Taller de Sublimación',
            'Evento Especial de Precios',
            'Promoción de Verano',
            'Nueva Colección Disponible',
            'Liquidación de Inventario',
            'Celebración de Aniversario',
            'Expo Creativa 2026',
        ];

        return [
            'title' => array_shift($titles) ?? 'Anuncio #' . $this->sequence,
            'description' => 'Descripción del evento especial con todos los detalles.',
            'event_date' => Carbon::now()->addWeek(),
            'location' => 'San José, Costa Rica',
            'link' => null,
            'image' => null,
            'is_active' => true,
            'expires_at' => null,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => Carbon::now()->subDay(),
        ]);
    }

    public function withImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'image' => 'announcements/test-' . Str::uuid() . '.webp',
        ]);
    }
}