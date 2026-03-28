<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get category IDs
        $sublimacion = Category::where('name', 'Sublimación')->first()?->id;
        $corteLaser = Category::where('name', 'Corte Láser')->first()?->id;
        $grabadoLaser = Category::where('name', 'Grabado Láser')->first()?->id;
        $vinil = Category::where('name', 'Vinil y Stickers')->first()?->id;
        $tazas = Category::where('name', 'Tazas')->first()?->id;
        $camisas = Category::where('name', 'Camisas')->first()?->id;
        $termos = Category::where('name', 'Termos')->first()?->id;
        $alfombras = Category::where('name', 'Alfombras')->first()?->id;

        $products = [
            [
                'name' => 'Taza Cerámica Blanca',
                'description' => 'Taza de cerámica de 11oz con acabado brillante. Ideal para diseños personalizados con sublimación. Resistente al microondas y lavavajillas.',
                'price' => 3500,
                'category_id' => $tazas,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 50,
            ],
            [
                'name' => 'Taza Mágica (Cambio de Color)',
                'description' => 'Taza negra que cambia de color al agregar líquido caliente. Revela tu diseño personalizado con efecto sorpresa.',
                'price' => 5500,
                'category_id' => $tazas,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 25,
            ],
            [
                'name' => 'Camisa Algodón Blanca',
                'description' => 'Camisa de algodón 100% con estampado por sublimación. Tallas disponibles: S, M, L, XL. Diseño full color.',
                'price' => 8500,
                'category_id' => $camisas,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 30,
            ],
            [
                'name' => 'Termo Acero Inoxidable 500ml',
                'description' => 'Termo de acero inoxidable con doble pared. Mantiene bebidas calientes por 12h y frías por 24h. Personalización por sublimación.',
                'price' => 7500,
                'category_id' => $termos,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 20,
            ],
            [
                'name' => 'Alfombra para Ratón',
                'description' => 'Alfombrilla de mouse con base antideslizante. Tamaño 23x19cm. Impresión full color por sublimación.',
                'price' => 4000,
                'category_id' => $alfombras,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 35,
            ],
            [
                'name' => 'Placa Madera Grabada',
                'description' => 'Placa de madera MDF con grabado láser personalizado. Ideal para decoración, letreros o regalos. Medidas 20x15cm.',
                'price' => 6000,
                'category_id' => $corteLaser,
                'service_type' => 'laser',
                'is_active' => true,
                'stock' => 15,
            ],
            [
                'name' => 'Llavero Acrílico Personalizado',
                'description' => 'Llavero de acrílico transparente cortado y grabado con láser. Diseño a medida. Incluye herraje metálico.',
                'price' => 2000,
                'category_id' => $grabadoLaser,
                'service_type' => 'laser',
                'is_active' => true,
                'stock' => 100,
            ],
            [
                'name' => 'Rompecabezas Madera Láser',
                'description' => 'Rompecabezas de madera cortado con láser. Diseño personalizado. Tamaño 15x15cm con 50 piezas.',
                'price' => 8000,
                'category_id' => $corteLaser,
                'service_type' => 'laser',
                'is_active' => true,
                'stock' => 10,
            ],
            [
                'name' => 'Stickers Vinil 10 unidades',
                'description' => 'Pack de 10 stickers de vinil adhesivo resistente al agua. Corte a medida según tu diseño. Tamaño aproximado 5x5cm.',
                'price' => 2500,
                'category_id' => $vinil,
                'service_type' => 'vinil',
                'is_active' => true,
                'stock' => 80,
            ],
            [
                'name' => 'Calcomanía Vinil Grande',
                'description' => 'Calcomanía de vinil de alta calidad para vehículos, laptops o superficies lisas. Tamaño 20x15cm. Resistente a intemperie.',
                'price' => 3500,
                'category_id' => $vinil,
                'service_type' => 'vinil',
                'is_active' => true,
                'stock' => 40,
            ],
            [
                'name' => 'Letras Vinil Cortado',
                'description' => 'Letras y números cortados en vinil adhesivo. Varios colores disponibles. Precio por centímetro lineal.',
                'price' => 1500,
                'category_id' => $vinil,
                'service_type' => 'vinil',
                'is_active' => true,
                'stock' => 200,
            ],
            [
                'name' => 'Pack 50 Stickers Vinil',
                'description' => 'Pack económico de 50 stickers de vinil resistente al agua. Ideal para emprendedores que necesitan promocionar su marca.',
                'price' => 9000,
                'category_id' => $vinil,
                'service_type' => 'vinil',
                'is_active' => true,
                'stock' => 25,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['name' => $product['name']],
                $product
            );
        }

        $this->command->info('✓ '.count($products).' productos creados exitosamente!');
    }
}
