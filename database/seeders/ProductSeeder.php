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
        $sublimacion = Category::where('slug', 'sublimacion')->first()?->id;
        $corteLaser = Category::where('slug', 'corte-laser')->first()?->id;
        $grabadoLaser = Category::where('slug', 'grabado-laser')->first()?->id;
        $vinil = Category::where('slug', 'vinil-y-stickers')->first()?->id;
        $tazas = Category::where('slug', 'tazas')->first()?->id;
        $camisas = Category::where('slug', 'camisas')->first()?->id;
        $termos = Category::where('slug', 'termos')->first()?->id;
        $alfombras = Category::where('slug', 'alfombras')->first()?->id;

        $products = [
            // ═══════════════════════════════════════════════════════════
            // TAZAS (6 products)
            // ═══════════════════════════════════════════════════════════
            [
                'name' => 'Taza Cerámica Blanca 11oz',
                'description' => 'Taza de cerámica blanca de alta calidad con acabado brillante. Perfecta para diseños personalizados con sublimación. Resistente al microondas y lavavajillas. Ideal para regalos corporativos o personales.',
                'price' => 3500,
                'category_id' => $tazas,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 50,
            ],
            [
                'name' => 'Taza Mágica (Cambio de Color)',
                'description' => 'Taza negra que cambia de color al agregar líquido caliente. Revela tu diseño personalizado con efecto sorpresa. Perfecta para fotos y regalos especiales que dejan huella.',
                'price' => 5500,
                'category_id' => $tazas,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 25,
            ],
            [
                'name' => 'Taza Cerámica Interior Color',
                'description' => 'Taza de cerámica blanca con interior de color (azul, rojo o rosa). Sublimación de alta calidad que resalta tu diseño. Capacidad 11oz, perfecta para café o té.',
                'price' => 4200,
                'category_id' => $tazas,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 35,
            ],
            [
                'name' => 'Taza Cónica Premium 12oz',
                'description' => 'Taza cónica de cerámica blanca con forma elegante y moderna. Acabado extra suave para sublimación de alta definición. Mayor capacidad de 12oz para los amantes del café.',
                'price' => 4800,
                'category_id' => $tazas,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 20,
            ],
            [
                'name' => 'Taza Mágica Interior Color',
                'description' => 'Taza mágica con interior de color que revela el diseño al agregar líquido caliente. Efecto sorpresa garantizado. Disponible en azul, rojo y rosa.',
                'price' => 6200,
                'category_id' => $tazas,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 18,
            ],
            [
                'name' => 'Taza Viajera con Tapa 14oz',
                'description' => 'Taza de cerámica con tapa hermética para llevar. Ideal para café o té en movimiento. Personalización por sublimación en toda la superficie. Capacidad 14oz.',
                'price' => 7500,
                'category_id' => $tazas,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 15,
            ],

            // ═══════════════════════════════════════════════════════════
            // CAMISAS (6 products)
            // ═══════════════════════════════════════════════════════════
            [
                'name' => 'Camisa Algodón Blanca Clásica',
                'description' => 'Camisa de algodón 100% con estampado por sublimación. Tallas disponibles: S, M, L, XL. Diseño full color en la parte frontal. Suave, cómoda y duradera.',
                'price' => 8500,
                'category_id' => $camisas,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 30,
            ],
            [
                'name' => 'Camisa Algodón Negra Premium',
                'description' => 'Camisa negra de algodón con sublimación especial para telas oscuras. Diseño vibrante que no se despinta después de varios lavados. Tallas S-XL.',
                'price' => 9500,
                'category_id' => $camisas,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 25,
            ],
            [
                'name' => 'Camisa Polo Personalizada',
                'description' => 'Camisa tipo polo con estampado personalizado. Ideal para uniformes empresariales o eventos especiales. Bordado opcional disponible. Tallas S-XXL.',
                'price' => 12000,
                'category_id' => $camisas,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 20,
            ],
            [
                'name' => 'Camisa Deportiva Dry-Fit',
                'description' => 'Camisa de material deportivo con sublimación total. Perfecta para equipos de fútbol, running o eventos deportivos. Tela transpirable que mantiene la frescura.',
                'price' => 10500,
                'category_id' => $camisas,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 40,
            ],
            [
                'name' => 'Camisa Manga Larga Premium',
                'description' => 'Camisa de manga larga con estampado completo. Algodón peinado de alta calidad. Cómoda y duradera para uso diario. Tallas S-XXL.',
                'price' => 11500,
                'category_id' => $camisas,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 18,
            ],
            [
                'name' => 'Camisa Infantil Sublimada',
                'description' => 'Camisa para niños con diseños personalizados. Algodón suave y cómodo. Perfecta para cumpleaños o eventos especiales. Tallas 4-12 años.',
                'price' => 6500,
                'category_id' => $camisas,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 35,
            ],

            // ═══════════════════════════════════════════════════════════
            // TERMOS (6 products)
            // ═══════════════════════════════════════════════════════════
            [
                'name' => 'Termo Acero Inoxidable 500ml',
                'description' => 'Termo de acero inoxidable con doble pared. Mantiene bebidas calientes por 12h y frías por 24h. Personalización por sublimación en toda la superficie.',
                'price' => 7500,
                'category_id' => $termos,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 20,
            ],
            [
                'name' => 'Termo Acero Inoxidable 750ml',
                'description' => 'Termo grande de 750ml. Ideal para el trabajo o gimnasio. Tapa hermética con asa integrada. Personalización completa con sublimación de alta calidad.',
                'price' => 9500,
                'category_id' => $termos,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 15,
            ],
            [
                'name' => 'Termo Deportivo con Pajilla 600ml',
                'description' => 'Termo de 600ml con pajilla integrada. Perfecto para deportes y actividades al aire libre. Base antideslizante y tapa a prueba de fugas.',
                'price' => 8800,
                'category_id' => $termos,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 22,
            ],
            [
                'name' => 'Termo Infantil 350ml',
                'description' => 'Termo tamaño infantil con diseños coloridos. Ligero y resistente. Ideal para el colegio. 350ml de capacidad. Fácil de abrir para niños.',
                'price' => 6200,
                'category_id' => $termos,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 28,
            ],
            [
                'name' => 'Termo Cerámico 400ml',
                'description' => 'Termo con interior cerámico que mantiene el sabor original de las bebidas. Exterior de acero inoxidable con sublimación personalizada. 400ml.',
                'price' => 8200,
                'category_id' => $termos,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 12,
            ],
            [
                'name' => 'Termo Premium con Temperatura Digital',
                'description' => 'Termo inteligente con pantalla LED que muestra la temperatura de la bebida. Acero inoxidable 304. Capacidad 500ml. Personalización con sublimación.',
                'price' => 15000,
                'category_id' => $termos,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 8,
            ],

            // ═══════════════════════════════════════════════════════════
            // VINIL Y STICKERS (6 products)
            // ═══════════════════════════════════════════════════════════
            [
                'name' => 'Pack 10 Stickers Vinil',
                'description' => 'Pack de 10 stickers de vinil adhesivo resistente al agua. Corte a medida según tu diseño. Tamaño aproximado 5x5cm. Perfectos para laptops, termos y más.',
                'price' => 2500,
                'category_id' => $vinil,
                'service_type' => 'vinil',
                'is_active' => true,
                'stock' => 80,
            ],
            [
                'name' => 'Calcomanía Vinil Grande 20x15cm',
                'description' => 'Calcomanía de vinil de alta calidad para vehículos, laptops o superficies lisas. Tamaño 20x15cm. Resistente a intemperie y rayos UV.',
                'price' => 3500,
                'category_id' => $vinil,
                'service_type' => 'vinil',
                'is_active' => true,
                'stock' => 40,
            ],
            [
                'name' => 'Letras Vinil Cortado Personalizadas',
                'description' => 'Letras y números cortados en vinil adhesivo. Varios colores disponibles. Precio por centímetro lineal. Ideal para decoración de paredes, vehículos o negocios.',
                'price' => 1500,
                'category_id' => $vinil,
                'service_type' => 'vinil',
                'is_active' => true,
                'stock' => 200,
            ],
            [
                'name' => 'Pack 50 Stickers Vinil Promo',
                'description' => 'Pack económico de 50 stickers de vinil resistente al agua. Ideal para emprendedores que necesitan promocionar su marca. Tamaño 5x5cm.',
                'price' => 9000,
                'category_id' => $vinil,
                'service_type' => 'vinil',
                'is_active' => true,
                'stock' => 25,
            ],
            [
                'name' => 'Stickers Holográficos x20',
                'description' => 'Pack de 20 stickers con acabado holográfico. Efecto irisado que cambia con la luz. Vinil resistente al agua. Tamaño 7x7cm. Perfectos para decoración.',
                'price' => 5500,
                'category_id' => $vinil,
                'service_type' => 'vinil',
                'is_active' => true,
                'stock' => 30,
            ],
            [
                'name' => 'Vinil Decorativo para Pared 30x40cm',
                'description' => 'Vinil adhesivo para decoración de paredes. Diseños personalizados. Fácil de aplicar y remover sin dañar la pintura. Tamaño 30x40cm.',
                'price' => 7800,
                'category_id' => $vinil,
                'service_type' => 'vinil',
                'is_active' => true,
                'stock' => 20,
            ],

            // ═══════════════════════════════════════════════════════════
            // CORTE LÁSER (6 products)
            // ═══════════════════════════════════════════════════════════
            [
                'name' => 'Placa Madera Grabada 20x15cm',
                'description' => 'Placa de madera MDF con grabado láser personalizado. Ideal para decoración, letreros o regalos especiales. Medidas 20x15cm con acabado natural.',
                'price' => 6000,
                'category_id' => $corteLaser,
                'service_type' => 'laser',
                'is_active' => true,
                'stock' => 15,
            ],
            [
                'name' => 'Rompecabezas Madera Láser 50 piezas',
                'description' => 'Rompecabezas de madera cortado con láser de precisión. Diseño personalizado con tu foto. Tamaño 15x15cm con 50 piezas. Regalo único y original.',
                'price' => 8000,
                'category_id' => $corteLaser,
                'service_type' => 'laser',
                'is_active' => true,
                'stock' => 10,
            ],
            [
                'name' => 'Caja Madera con Tapa Personalizada',
                'description' => 'Caja de madera MDF cortada con láser. Con tapa personalizable con grabado. Ideal para regalos o almacenamiento. Dimensiones 15x10x8cm.',
                'price' => 7500,
                'category_id' => $corteLaser,
                'service_type' => 'laser',
                'is_active' => true,
                'stock' => 12,
            ],
            [
                'name' => 'Señalización Acrílico 20x10cm',
                'description' => 'Letrero de acrílico transparente cortado y grabado con láser. Para baños, oficinas o negocios. Incluye soporte de mesa. Tamaño 20x10cm.',
                'price' => 9500,
                'category_id' => $corteLaser,
                'service_type' => 'laser',
                'is_active' => true,
                'stock' => 8,
            ],
            [
                'name' => 'Decoración Navideña Madera',
                'description' => 'Adornos navideños de madera cortados con láser. Diseños de árboles, estrellas y renos. Set de 6 piezas. Perfectos para decorar o regalar.',
                'price' => 5000,
                'category_id' => $corteLaser,
                'service_type' => 'laser',
                'is_active' => true,
                'stock' => 25,
            ],
            [
                'name' => 'Posavasos Madera x6 Unidades',
                'description' => 'Set de 6 posavasos de madera MDF cortados con láser. Personalizables con nombres o diseños. Diámetro 10cm. Ideales para regalos corporativos.',
                'price' => 4500,
                'category_id' => $corteLaser,
                'service_type' => 'laser',
                'is_active' => true,
                'stock' => 30,
            ],

            // ═══════════════════════════════════════════════════════════
            // GRABADO LÁSER (6 products)
            // ═══════════════════════════════════════════════════════════
            [
                'name' => 'Llavero Acrílico Personalizado',
                'description' => 'Llavero de acrílico transparente cortado y grabado con láser. Diseño a medida con nombre o logo. Incluye herraje metálico resistente.',
                'price' => 2000,
                'category_id' => $grabadoLaser,
                'service_type' => 'laser',
                'is_active' => true,
                'stock' => 100,
            ],
            [
                'name' => 'Portarretratos Madera con Grabado',
                'description' => 'Portarretratos de madera con grabado láser personalizado. Para foto 10x15cm. Diseño rústico elegante. Incluye soporte de mesa y para colgar.',
                'price' => 5500,
                'category_id' => $grabadoLaser,
                'service_type' => 'laser',
                'is_active' => true,
                'stock' => 18,
            ],
            [
                'name' => 'Placa Conmemorativa Metal',
                'description' => 'Placa de aluminio anodizado con grabado láser de alta precisión. Ideal para reconocimientos o placas de identificación. Tamaño 15x10cm.',
                'price' => 8500,
                'category_id' => $grabadoLaser,
                'service_type' => 'laser',
                'is_active' => true,
                'stock' => 15,
            ],
            [
                'name' => 'Bolígrafo Madera Grabado',
                'description' => 'Bolígrafo de madera con grabado láser personalizado. Incluye caja de regalo. Perfecto para regalos corporativos o de graduación. Tinta azul.',
                'price' => 4000,
                'category_id' => $grabadoLaser,
                'service_type' => 'laser',
                'is_active' => true,
                'stock' => 50,
            ],
            [
                'name' => 'Tabla de Cortar Personalizada',
                'description' => 'Tabla de cortar de bambú con grabado láser. Ideal para cocina o como regalo. Tamaño 30x20cm. Resistente y duradera. Incluye aceite protector.',
                'price' => 12000,
                'category_id' => $grabadoLaser,
                'service_type' => 'laser',
                'is_active' => true,
                'stock' => 10,
            ],
            [
                'name' => 'Reloj Pared Madera Grabado',
                'description' => 'Reloj de pared de madera con diseño grabado con láser. Mecanismo silencioso incluido. Diámetro 30cm. Requiere 1 pila AA (no incluida).',
                'price' => 15000,
                'category_id' => $grabadoLaser,
                'service_type' => 'laser',
                'is_active' => true,
                'stock' => 6,
            ],

            // ═══════════════════════════════════════════════════════════
            // ALFOMBRAS (6 products)
            // ═══════════════════════════════════════════════════════════
            [
                'name' => 'Alfombra para Ratón Clásica',
                'description' => 'Alfombrilla de mouse con base antideslizante. Tamaño 23x19cm. Impresión full color por sublimación. Superficie de tela premium para precisión.',
                'price' => 4000,
                'category_id' => $alfombras,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 35,
            ],
            [
                'name' => 'Alfombrilla Gaming XL 80x30cm',
                'description' => 'Alfombrilla grande para gaming (80x30cm). Borde cosido anti-desgaste. Base de goma natural. Sublimación de alta calidad en toda la superficie.',
                'price' => 12500,
                'category_id' => $alfombras,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 12,
            ],
            [
                'name' => 'Alfombrilla Ergonómica con Gel',
                'description' => 'Alfombrilla con soporte de gel para muñeca. Reduce la fatiga en uso prolongado. Diseño personalizado. Tamaño 22x24cm. Base antideslizante.',
                'price' => 6500,
                'category_id' => $alfombras,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 18,
            ],
            [
                'name' => 'Alfombrilla Speed para Gaming',
                'description' => 'Alfombrilla de velocidad para gaming competitivo. Superficie ultra lisa. Tamaño 40x90cm. Bordes cosidos. Ideal para jugadores profesionales.',
                'price' => 15000,
                'category_id' => $alfombras,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 8,
            ],
            [
                'name' => 'Alfombrilla Control Gaming',
                'description' => 'Alfombrilla de control para precisión en juegos. Superficie texturizada. Tamaño 40x90cm. Base de goma. Perfecta para FPS y juegos de estrategia.',
                'price' => 14000,
                'category_id' => $alfombras,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 10,
            ],
            [
                'name' => 'Alfombrilla Escritorio Premium',
                'description' => 'Alfombrilla grande para escritorio (60x35cm). Cubre teclado y mouse. Impresión HD personalizada. Borde cosido para mayor durabilidad.',
                'price' => 9500,
                'category_id' => $alfombras,
                'service_type' => 'sublimacion',
                'is_active' => true,
                'stock' => 15,
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
