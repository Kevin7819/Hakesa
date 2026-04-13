<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Comment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
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

        $users = $this->createDemoUsers();
        $this->createDemoActivity($users);
        $this->createTestComments($users);
    }

    /**
     * Create 3 demo client accounts with realistic Costa Rican names.
     * All use password: demo123
     *
     * @return array<string, User>
     */
    protected function createDemoUsers(): array
    {
        $password = Hash::make('demo123');

        // Client 1 — Active buyer
        $maria = User::firstOrCreate(
            ['email' => 'maria.mora@demo.com'],
            [
                'name' => 'María Fernanda Mora Jiménez',
                'password' => $password,
                'phone' => '+506 8745 3321',
                'birthday' => '1992-08-15',
                'email_verified_at' => now(),
            ]
        );

        // Client 2 — Browsing user
        $carlos = User::firstOrCreate(
            ['email' => 'carlos.solano@demo.com'],
            [
                'name' => 'Carlos Andrés Solano Rodríguez',
                'password' => $password,
                'phone' => '+506 6234 8890',
                'birthday' => '1988-03-22',
                'email_verified_at' => now(),
            ]
        );

        // Client 3 — New user
        $ana = User::firstOrCreate(
            ['email' => 'ana.campos@demo.com'],
            [
                'name' => 'Ana Lucía Campos Vargas',
                'password' => $password,
                'phone' => '+506 7123 4567',
                'birthday' => '1998-11-30',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✓ 3 clientes demo creados (password: demo123)');
        $this->command->info('  - María: maria.mora@demo.com (compradora activa)');
        $this->command->info('  - Carlos: carlos.solano@demo.com (navegando)');
        $this->command->info('  - Ana: ana.campos@demo.com (usuario nueva)');

        return compact('maria', 'carlos', 'ana');
    }

    /**
     * Create demo activity: orders, wishlists, cart items.
     *
     * @param  array<string, User>  $users
     */
    protected function createDemoActivity(array $users): void
    {
        $maria = $users['maria'];
        $carlos = $users['carlos'];
        $ana = $users['ana'];

        $products = Product::where('is_active', true)->get();

        if ($products->count() < 10) {
            $this->command->warn('⚠ No hay suficientes productos para crear actividad demo.');

            return;
        }

        // ═══════════════════════════════════════════════════════════
        // CLIENT 1 — María: Active buyer (3 completed orders, 5 wishlist, 3 cart items)
        // ═══════════════════════════════════════════════════════════

        // Order 1: Completed - Multiple products
        $this->createOrder($maria, 'GC-2026-0001', 'completed', [
            [
                'product' => $products->firstWhere('name', 'Taza Cerámica Blanca 11oz'),
                'quantity' => 2,
                'customization' => 'Foto familiar con texto "Familia Mora"',
            ],
            [
                'product' => $products->firstWhere('name', 'Taza Mágica (Cambio de Color)'),
                'quantity' => 1,
                'customization' => 'Logo de la empresa con nombre',
            ],
            [
                'product' => $products->firstWhere('name', 'Camisa Algodón Blanca Clásica'),
                'quantity' => 1,
                'customization' => 'Talla M, diseño de mariposas',
            ],
        ], 'Por favor envolver para regalo de cumpleaños.');

        // Order 2: Completed - Stickers and vinyl
        $this->createOrder($maria, 'GC-2026-0002', 'completed', [
            [
                'product' => $products->firstWhere('name', 'Pack 50 Stickers Vinil Promo'),
                'quantity' => 1,
                'customization' => 'Logo de mi emprendimiento de repostería',
            ],
            [
                'product' => $products->firstWhere('name', 'Stickers Holográficos x20'),
                'quantity' => 2,
                'customization' => 'Diseños de cupcakes y pasteles',
            ],
        ], null);

        // Order 3: Completed - Laser engraving
        $this->createOrder($maria, 'GC-2026-0003', 'completed', [
            [
                'product' => $products->firstWhere('name', 'Llavero Acrílico Personalizado'),
                'quantity' => 5,
                'customization' => 'Nombres: María, Juan, Sofía, Mateo, Valentina',
            ],
            [
                'product' => $products->firstWhere('name', 'Termo Acero Inoxidable 500ml'),
                'quantity' => 1,
                'customization' => 'Nombre "María" en letra cursiva',
            ],
        ], 'Son para regalos de fin de año.');

        // Wishlist: 5 items
        $this->addToWishlist($maria, $products->firstWhere('name', 'Alfombrilla Gaming XL 80x30cm'));
        $this->addToWishlist($maria, $products->firstWhere('name', 'Reloj Pared Madera Grabado'));
        $this->addToWishlist($maria, $products->firstWhere('name', 'Tabla de Cortar Personalizada'));
        $this->addToWishlist($maria, $products->firstWhere('name', 'Termo Premium con Temperatura Digital'));
        $this->addToWishlist($maria, $products->firstWhere('name', 'Camisa Polo Personalizada'));

        // Cart: 3 items
        $this->addToCart($maria, $products->firstWhere('name', 'Taza Cónica Premium 12oz'), 2);
        $this->addToCart($maria, $products->firstWhere('name', 'Pack 10 Stickers Vinil'), 1);
        $this->addToCart($maria, $products->firstWhere('name', 'Camisa Infantil Sublimada'), 1, 'Talla 8 años, diseño de unicornio');

        // ═══════════════════════════════════════════════════════════
        // CLIENT 2 — Carlos: Browsing user (1 pending order, 3 wishlist, empty cart)
        // ═══════════════════════════════════════════════════════════

        // Order 1: Pending
        $this->createOrder($carlos, 'GC-2026-0004', 'pending', [
            [
                'product' => $products->firstWhere('name', 'Camisa Deportiva Dry-Fit'),
                'quantity' => 1,
                'customization' => 'Talla L, logo del equipo de fútbol "Los Halcones"',
            ],
            [
                'product' => $products->firstWhere('name', 'Termo Deportivo con Pajilla 600ml'),
                'quantity' => 1,
                'customization' => 'Nombre "Carlos" y número 10',
            ],
        ], 'Necesito que esté listo para el sábado, es para un torneo.');

        // Wishlist: 3 items
        $this->addToWishlist($carlos, $products->firstWhere('name', 'Alfombrilla Speed para Gaming'));
        $this->addToWishlist($carlos, $products->firstWhere('name', 'Señalización Acrílico 20x10cm'));
        $this->addToWishlist($carlos, $products->firstWhere('name', 'Rompecabezas Madera Láser 50 piezas'));

        // Empty cart (don't create)

        // ═══════════════════════════════════════════════════════════
        // CLIENT 3 — Ana: New user (no orders, 2 wishlist, 1 cart item)
        // ═══════════════════════════════════════════════════════════

        // No orders

        // Wishlist: 2 items
        $this->addToWishlist($ana, $products->firstWhere('name', 'Taza Mágica Interior Color'));
        $this->addToWishlist($ana, $products->firstWhere('name', 'Stickers Holográficos x20'));

        // Cart: 1 item
        $this->addToCart($ana, $products->firstWhere('name', 'Taza Cerámica Interior Color'), 1, 'Interior rosa, con foto de mi mascota');

        $this->command->info('✓ Actividad demo creada:');
        $this->command->info('  - María: 3 órdenes completadas, 5 favoritos, 3 en carrito');
        $this->command->info('  - Carlos: 1 orden pendiente, 3 favoritos, carrito vacío');
        $this->command->info('  - Ana: 0 órdenes, 2 favoritos, 1 en carrito');
    }

    /**
     * Create an order with items.
     *
     * @param  array<array{product: ?Product, quantity: int, customization: ?string}>  $items
     */
    protected function createOrder(User $user, string $orderNumber, string $status, array $items, ?string $notes): void
    {
        $subtotal = 0;
        foreach ($items as $item) {
            if ($item['product']) {
                $subtotal += $item['product']->price * $item['quantity'];
            }
        }

        $shipping = $subtotal > 15000 ? 0 : 2500;
        $total = $subtotal + $shipping;

        $order = Order::firstOrCreate(
            ['order_number' => $orderNumber],
            [
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone ?? '+506 8888 0000',
                'customer_address' => 'San José, Costa Rica',
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'total' => $total,
                'status' => $status,
                'notes' => $notes,
            ]
        );

        foreach ($items as $item) {
            if ($item['product']) {
                OrderItem::firstOrCreate(
                    ['order_id' => $order->id, 'product_id' => $item['product']->id],
                    [
                        'product_name' => $item['product']->name,
                        'price' => $item['product']->price,
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['product']->price * $item['quantity'],
                        'customization' => $item['customization'],
                    ]
                );
            }
        }
    }

    /**
     * Add product to user's wishlist.
     */
    protected function addToWishlist(User $user, ?Product $product): void
    {
        if (! $product) {
            return;
        }

        Wishlist::firstOrCreate(
            ['user_id' => $user->id, 'product_id' => $product->id]
        );
    }

    /**
     * Add product to user's cart.
     */
    protected function addToCart(User $user, ?Product $product, int $quantity = 1, ?string $customization = null): void
    {
        if (! $product) {
            return;
        }

        $cart = Cart::getOrCreateForUser($user);

        CartItem::firstOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $product->id],
            [
                'quantity' => $quantity,
                'customization' => $customization,
            ]
        );
    }

    /**
     * Create test comments for demo users.
     *
     * @param  array<string, User>  $users
     */
    protected function createTestComments(array $users): void
    {
        $comments = [
            ['user' => 'maria', 'content' => '¡Excelente calidad! Pedí una taza personalizada y quedó hermosa. La recomiendo totalmente.', 'status' => 'aprobado'],
            ['user' => 'carlos', 'content' => 'Los stickers de vinil son resistentes al agua como prometen. Ya llevo 3 meses con el mío en el carro y perfecto.', 'status' => 'aprobado'],
            ['user' => 'ana', 'content' => 'Muy buen servicio, el grabado láser en la placa de madera quedó muy detallado. Solo tardó 2 días.', 'status' => 'aprobado'],
            ['user' => 'maria', 'content' => 'El termo mantiene la temperatura perfecto. Lo uso todos los días para el café. Muy contenta con la compra.', 'status' => 'aprobado'],
            ['user' => 'carlos', 'content' => 'Pedí 50 stickers para mi negocio y quedaron increíbles. Buena resolución de impresión y el vinil es de calidad.', 'status' => 'aprobado'],
            ['user' => 'ana', 'content' => 'La camisa de algodón es suave y el estampado no se ha despintado después de varios lavados. ¡Volveré a comprar!', 'status' => 'aprobado'],
        ];

        foreach ($comments as $data) {
            $user = $users[$data['user']] ?? null;
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
}
