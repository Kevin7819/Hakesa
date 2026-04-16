<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get category IDs by slug
        $tazas = Category::where('slug', 'tazas')->first()?->id;
        $camisas = Category::where('slug', 'camisas')->first()?->id;
        $termos = Category::where('slug', 'termos')->first()?->id;
        $vinil = Category::where('slug', 'vinil-y-stickers')->first()?->id;
        $corteLaser = Category::where('slug', 'corte-laser')->first()?->id;
        $grabadoLaser = Category::where('slug', 'grabado-laser')->first()?->id;
        $alfombras = Category::where('slug', 'alfombras')->first()?->id;

        $products = [
            // TAZAS (6 products)
            [
                'name' => 'Taza Cerámica Blanca 11oz',
                'description' => 'Taza de cerámica blanca de alta calidad con acabado brillante. Perfecta para diseños personalizados con sublimación.',
                'price' => 3500,
                'category_id' => $tazas,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Taza Mágica (Cambio de Color)',
                'description' => 'Taza negra que cambia de color al agregar líquido caliente. Revela tu diseño personalizado.',
                'price' => 5500,
                'category_id' => $tazas,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Taza Cerámica Interior Color',
                'description' => 'Taza de cerámica blanca con interior de color. Sublimación de alta calidad.',
                'price' => 4200,
                'category_id' => $tazas,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Taza Cónica Premium 12oz',
                'description' => 'Taza cónica de cerámica blanca con forma elegante y moderna.',
                'price' => 4800,
                'category_id' => $tazas,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Taza Mágica Interior Color',
                'description' => 'Taza mágica con interior de color que revela el diseño al agregar líquido caliente.',
                'price' => 6200,
                'category_id' => $tazas,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Taza Viajera con Tapa 14oz',
                'description' => 'Taza de cerámica con tapa hermética para llevar.',
                'price' => 7500,
                'category_id' => $tazas,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],

            // CAMISAS (6 products)
            [
                'name' => 'Camisa Algodón Blanca Clásica',
                'description' => 'Camisa de algodón 100% con estampado por sublimación.',
                'price' => 8500,
                'category_id' => $camisas,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Camisa Algodón Negra Premium',
                'description' => 'Camisa negra de algodón con sublimación especial para telas oscuras.',
                'price' => 9500,
                'category_id' => $camisas,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Camisa Polo Personalizada',
                'description' => 'Camisa tipo polo con estampado personalizado.',
                'price' => 12000,
                'category_id' => $camisas,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Camisa Deportiva Dry-Fit',
                'description' => 'Camisa de material deportivo con sublimación total.',
                'price' => 10500,
                'category_id' => $camisas,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Camisa Manga Larga Premium',
                'description' => 'Camisa de manga larga con estampado completo.',
                'price' => 11500,
                'category_id' => $camisas,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Camisa Infantil Sublimada',
                'description' => 'Camisa para niños con diseños personalizados.',
                'price' => 6500,
                'category_id' => $camisas,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],

            // TERMOS (6 products)
            [
                'name' => 'Termo Acero Inoxidable 500ml',
                'description' => 'Termo de acero inoxidable con doble pared.',
                'price' => 7500,
                'category_id' => $termos,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Termo Acero Inoxidable 750ml',
                'description' => 'Termo grande de 750ml ideal para el trabajo.',
                'price' => 9500,
                'category_id' => $termos,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Termo Deportivo con Pajilla 600ml',
                'description' => 'Termo de 600ml con pajilla integrada.',
                'price' => 8800,
                'category_id' => $termos,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Termo Infantil 350ml',
                'description' => 'Termo tamaño infantil con diseños coloridos.',
                'price' => 6200,
                'category_id' => $termos,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Termo Cerámico 400ml',
                'description' => 'Termo con interior cerámico que mantiene el sabor.',
                'price' => 8200,
                'category_id' => $termos,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Termo Premium con Temperatura Digital',
                'description' => 'Termo inteligente con pantalla LED.',
                'price' => 15000,
                'category_id' => $termos,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],

            // VINIL Y STICKERS (6 products)
            [
                'name' => 'Pack 10 Stickers Vinil',
                'description' => 'Pack de 10 stickers de vinil adhesivo resistente al agua.',
                'price' => 2500,
                'category_id' => $vinil,
                'service_type' => 'vinil',
                'is_active' => true,
            ],
            [
                'name' => 'Calcomanía Vinil Grande 20x15cm',
                'description' => 'Calcomanía de vinil de alta calidad.',
                'price' => 3500,
                'category_id' => $vinil,
                'service_type' => 'vinil',
                'is_active' => true,
            ],
            [
                'name' => 'Letras Vinil Cortado Personalizadas',
                'description' => 'Letras y números cortados en vinil adhesivo.',
                'price' => 1500,
                'category_id' => $vinil,
                'service_type' => 'vinil',
                'is_active' => true,
            ],
            [
                'name' => 'Pack 50 Stickers Vinil Promo',
                'description' => 'Pack económico de 50 stickers de vinil.',
                'price' => 9000,
                'category_id' => $vinil,
                'service_type' => 'vinil',
                'is_active' => true,
            ],
            [
                'name' => 'Stickers Holográficos x20',
                'description' => 'Pack de 20 stickers con acabado holográfico.',
                'price' => 5500,
                'category_id' => $vinil,
                'service_type' => 'vinil',
                'is_active' => true,
            ],
            [
                'name' => 'Vinil Decorativo para Pared 30x40cm',
                'description' => 'Vinil adhesivo para decoración de paredes.',
                'price' => 7800,
                'category_id' => $vinil,
                'service_type' => 'vinil',
                'is_active' => true,
            ],

            // CORTE LÁSER (6 products)
            [
                'name' => 'Placa Madera Grabada 20x15cm',
                'description' => 'Placa de madera MDF con grabado láser personalizado.',
                'price' => 6000,
                'category_id' => $corteLaser,
                'service_type' => 'laser',
                'is_active' => true,
            ],
            [
                'name' => 'Rompecabezas Madera Láser 50 piezas',
                'description' => 'Rompecabezas de madera cortado con láser de precisión.',
                'price' => 8000,
                'category_id' => $corteLaser,
                'service_type' => 'laser',
                'is_active' => true,
            ],
            [
                'name' => 'Caja Madera con Tapa Personalizada',
                'description' => 'Caja de madera MDF cortada con láser.',
                'price' => 7500,
                'category_id' => $corteLaser,
                'service_type' => 'laser',
                'is_active' => true,
            ],
            [
                'name' => 'Señalización Acrílico 20x10cm',
                'description' => 'Letrero de acrílico transparente cortado y grabado.',
                'price' => 9500,
                'category_id' => $corteLaser,
                'service_type' => 'laser',
                'is_active' => true,
            ],
            [
                'name' => 'Decoración Navideña Madera',
                'description' => 'Adornos navideños de madera cortados con láser.',
                'price' => 5000,
                'category_id' => $corteLaser,
                'service_type' => 'laser',
                'is_active' => true,
            ],
            [
                'name' => 'Posavasos Madera x6 Unidades',
                'description' => 'Set de 6 posavasos de madera MDF cortados con láser.',
                'price' => 4500,
                'category_id' => $corteLaser,
                'service_type' => 'laser',
                'is_active' => true,
            ],

            // GRABADO LÁSER (6 products)
            [
                'name' => 'Llavero Acrílico Personalizado',
                'description' => 'Llavero de acrílico transparente cortado y grabado con láser.',
                'price' => 2000,
                'category_id' => $grabadoLaser,
                'service_type' => 'laser',
                'is_active' => true,
            ],
            [
                'name' => 'Portarretratos Madera con Grabado',
                'description' => 'Portarretratos de madera con grabado láser personalizado.',
                'price' => 5500,
                'category_id' => $grabadoLaser,
                'service_type' => 'laser',
                'is_active' => true,
            ],
            [
                'name' => 'Placa Conmemorativa Metal',
                'description' => 'Placa de aluminio anodizado con grabado láser.',
                'price' => 8500,
                'category_id' => $grabadoLaser,
                'service_type' => 'laser',
                'is_active' => true,
            ],
            [
                'name' => 'Bolígrafo Madera Grabado',
                'description' => 'Bolígrafo de madera con grabado láser personalizado.',
                'price' => 4000,
                'category_id' => $grabadoLaser,
                'service_type' => 'laser',
                'is_active' => true,
            ],
            [
                'name' => 'Tabla de Cortar Personalizada',
                'description' => 'Tabla de cortar de bambú con grabado láser.',
                'price' => 12000,
                'category_id' => $grabadoLaser,
                'service_type' => 'laser',
                'is_active' => true,
            ],
            [
                'name' => 'Reloj Pared Madera Grabado',
                'description' => 'Reloj de pared de madera con diseño grabado con láser.',
                'price' => 15000,
                'category_id' => $grabadoLaser,
                'service_type' => 'laser',
                'is_active' => true,
            ],

            // ALFOMBRAS (6 products)
            [
                'name' => 'Alfombra para Ratón Clásica',
                'description' => 'Alfombrilla de mouse con base antideslizante.',
                'price' => 4000,
                'category_id' => $alfombras,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Alfombrilla Gaming XL 80x30cm',
                'description' => 'Alfombrilla grande para gaming 80x30cm.',
                'price' => 12500,
                'category_id' => $alfombras,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Alfombrilla Ergonómica con Gel',
                'description' => 'Alfombrilla con soporte de gel para muñeca.',
                'price' => 6500,
                'category_id' => $alfombras,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Alfombrilla Speed para Gaming',
                'description' => 'Alfombrilla de velocidad para gaming competitivo.',
                'price' => 15000,
                'category_id' => $alfombras,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Alfombrilla Control Gaming',
                'description' => 'Alfombrilla de control para precisión en juegos.',
                'price' => 14000,
                'category_id' => $alfombras,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
            [
                'name' => 'Alfombrilla Escritorio Premium',
                'description' => 'Alfombrilla grande para escritorio 60x35cm.',
                'price' => 9500,
                'category_id' => $alfombras,
                'service_type' => 'sublimacion',
                'is_active' => true,
            ],
        ];

        $created = 0;
        foreach ($products as $product) {
            Product::firstOrCreate(
                ['name' => $product['name']],
                $product
            );
            $created++;
        }

        $this->command->info('✓ '.$created.' productos creados exitosamente!');
    }
}
