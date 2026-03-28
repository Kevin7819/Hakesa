<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Sublimación', 'description' => 'Tazas, camisas, termos, alfombras y más productos personalizados con sublimación.', 'sort_order' => 1],
            ['name' => 'Corte Láser', 'description' => 'Corte preciso en madera, acrílico y metal para decoración y regalos.', 'sort_order' => 2],
            ['name' => 'Grabado Láser', 'description' => 'Grabado personalizado en madera, acrílico y metal.', 'sort_order' => 3],
            ['name' => 'Vinil y Stickers', 'description' => 'Stickers personalizados, calcomanías y cortes de vinil adhesivo.', 'sort_order' => 4],
            ['name' => 'Tazas', 'description' => 'Tazas de cerámica, mágicas y especiales para sublimación.', 'sort_order' => 5],
            ['name' => 'Camisas', 'description' => 'Camisas de algodón con estampado personalizado por sublimación.', 'sort_order' => 6],
            ['name' => 'Termos', 'description' => 'Termos de acero inoxidable con personalización por sublimación.', 'sort_order' => 7],
            ['name' => 'Alfombras', 'description' => 'Alfombras personalizadas con impresión por sublimación.', 'sort_order' => 8],
        ];

        foreach ($categories as $data) {
            Category::firstOrCreate(
                ['name' => $data['name']],
                $data
            );
        }

        $this->command->info('✓ '.count($categories).' categorías creadas exitosamente!');
    }
}
