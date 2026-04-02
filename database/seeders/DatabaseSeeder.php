<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);

        $this->createTestUsers();
        $this->createTestComments();
        $this->createTestOrders();
    }

    protected function createTestUsers(): void
    {
        $users = [
            [
                'name' => 'María González',
                'email' => 'maria@hakesa.com',
                'password' => 'Hakesa2026!',
                'phone' => '+506 8888 1111',
                'birthday' => '1990-05-15',
            ],
            [
                'name' => 'Carlos Ramírez',
                'email' => 'carlos@hakesa.com',
                'password' => 'Hakesa2026!',
                'phone' => '+506 8888 2222',
                'birthday' => '1985-11-20',
            ],
            [
                'name' => 'Ana Solano',
                'email' => 'ana@hakesa.com',
                'password' => 'Hakesa2026!',
                'phone' => '+506 8888 3333',
                'birthday' => '1995-03-08',
            ],
        ];

        foreach ($users as $data) {
            User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'phone' => $data['phone'],
                    'birthday' => $data['birthday'],
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('✓ '.count($users).' usuarios de prueba creados.');
    }

    protected function createTestComments(): void
    {
        $comments = [
            ['user_email' => 'maria@hakesa.com', 'content' => '¡Excelente calidad! Pedí una taza personalizada y quedó hermosa. La recomiendo totalmente.', 'status' => 'aprobado'],
            ['user_email' => 'carlos@hakesa.com', 'content' => 'Los stickers de vinil son resistentes al agua como prometen. Ya llevo 3 meses con el mío en el carro y perfecto.', 'status' => 'aprobado'],
            ['user_email' => 'ana@hakesa.com', 'content' => 'Muy buen servicio, el grabado láser en la placa de madera quedó muy detallado. Solo tardó 2 días.', 'status' => 'aprobado'],
            ['user_email' => 'maria@hakesa.com', 'content' => 'El termo mantiene la temperatura perfecto. Lo uso todos los días para el café. Muy contenta con la compra.', 'status' => 'aprobado'],
            ['user_email' => 'carlos@hakesa.com', 'content' => 'Pedí 50 stickers para mi negocio y quedaron increíbles. Buena resolución de impresión y el vinil es de calidad.', 'status' => 'aprobado'],
            ['user_email' => 'ana@hakesa.com', 'content' => 'La camisa de algodón es suave y el estampado no se ha despintado después de varios lavados. ¡Volveré a comprar!', 'status' => 'aprobado'],
            ['user_email' => 'maria@hakesa.com', 'content' => 'Me encantó la taza mágica. El efecto de revelar la foto al poner el café caliente es genial. Mi familia quedó impresionada.', 'status' => 'aprobado'],
            ['user_email' => 'carlos@hakesa.com', 'content' => 'El llavero de acrílico es muy bonito. Lo grabaron con el nombre de mi hija y quedó perfecto. Buen detalle para regalo.', 'status' => 'aprobado'],
            ['user_email' => 'ana@hakesa.com', 'content' => 'La alfombra para ratón tiene buena calidad de impresión. Los colores son vivos y la base no se mueve. Recomendado.', 'status' => 'aprobado'],
            ['user_email' => 'maria@hakesa.com', 'content' => 'Pedí un rompecabezas personalizado con una foto familiar. El resultado fue increíble, la calidad del corte láser es precisa.', 'status' => 'aprobado'],
            ['user_email' => 'carlos@hakesa.com', 'content' => 'Buen producto, pero tardó un poco más de lo esperado en llegar. La calidad del grabado es buena.', 'status' => 'pendiente'],
            ['user_email' => 'ana@hakesa.com', 'content' => 'Las letras de vinil cortado son fáciles de aplicar. Las usé para decorar la puerta de mi cuarto. Quedó muy bien.', 'status' => 'pendiente'],
        ];

        foreach ($comments as $data) {
            $user = User::where('email', $data['user_email'])->first();
            if ($user) {
                Comment::firstOrCreate(
                    ['user_id' => $user->id, 'content' => $data['content']],
                    [
                        'user_id' => $user->id,
                        'content' => $data['content'],
                        'status' => $data['status'],
                    ]
                );
            }
        }

        $this->command->info('✓ '.count($comments).' comentarios de prueba creados.');
    }

    protected function createTestOrders(): void
    {
        $users = User::all();
        $products = Product::where('is_active', true)->get();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        $orders = [
            [
                'user_email' => 'maria@hakesa.com',
                'order_number' => 'HAK-2026-0001',
                'status' => 'completed',
                'items' => [
                    ['product_name' => 'Taza Cerámica Blanca', 'quantity' => 2, 'customization' => 'Foto familiar con texto "Familia González"'],
                    ['product_name' => 'Taza Mágica (Cambio de Color)', 'quantity' => 1, 'customization' => 'Logo de la empresa'],
                ],
                'notes' => 'Por favor envolver para regalo.',
            ],
            [
                'user_email' => 'carlos@hakesa.com',
                'order_number' => 'HAK-2026-0002',
                'status' => 'in_progress',
                'items' => [
                    ['product_name' => 'Pack 50 Stickers Vinil', 'quantity' => 1, 'customization' => 'Diseño de logo de mi negocio'],
                ],
                'notes' => null,
            ],
            [
                'user_email' => 'ana@hakesa.com',
                'order_number' => 'HAK-2026-0003',
                'status' => 'pending',
                'items' => [
                    ['product_name' => 'Camisa Algodón Blanca', 'quantity' => 1, 'customization' => 'Talla M, diseño de mariposas'],
                    ['product_name' => 'Termo Acero Inoxidable 500ml', 'quantity' => 1, 'customization' => 'Nombre "Ana" en letra cursiva'],
                ],
                'notes' => 'Necesito que esté listo para el viernes.',
            ],
        ];

        foreach ($orders as $orderData) {
            $user = User::where('email', $orderData['user_email'])->first();
            if (! $user) {
                continue;
            }

            $subtotal = 0;
            foreach ($orderData['items'] as $itemData) {
                $product = $products->firstWhere('name', $itemData['product_name']);
                if ($product) {
                    $subtotal += $product->price * $itemData['quantity'];
                }
            }

            $order = Order::firstOrCreate(
                ['order_number' => $orderData['order_number']],
                [
                    'user_id' => $user->id,
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                    'customer_phone' => $user->phone ?? '+506 8888 0000',
                    'customer_address' => null,
                    'subtotal' => $subtotal,
                    'shipping_cost' => 0,
                    'total' => $subtotal,
                    'status' => $orderData['status'],
                    'notes' => $orderData['notes'],
                ]
            );

            foreach ($orderData['items'] as $itemData) {
                $product = $products->firstWhere('name', $itemData['product_name']);
                if ($product) {
                    $order->items()->firstOrCreate(
                        ['product_id' => $product->id],
                        [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'price' => $product->price,
                            'quantity' => $itemData['quantity'],
                            'subtotal' => $product->price * $itemData['quantity'],
                            'customization' => $itemData['customization'],
                        ]
                    );
                }
            }
        }

        $this->command->info('✓ '.count($orders).' órdenes de prueba creadas.');
    }
}
