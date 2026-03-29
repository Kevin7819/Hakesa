<p align="center">
  <a href="https://hakesa.com" target="_blank">
    <img src="https://via.placeholder.com/400x120/FF6B8A/FFFFFF?text=HAKESA" alt="Hakesa Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/Kevin7819/Hakesa/actions"><img src="https://github.com/Kevin7819/Hakesa/actions/workflows/tests.yml/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/hakesa/core"><img src="https://img.shields.io/packagist/dt/hakesa/core" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/hakesa/core"><img src="https://img.shields.io/packagist/v/hakesa/core" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/hakesa/core"><img src="https://img.shields.io/packagist/l/hakesa/core" alt="License"></a>
</p>

## 🛍️ ¿Qué es Hakesa?

**Hakesa** es una plataforma de comercio electrónico para la venta de **productos personalizados y merchandising**. Permite a los clientes navegar un catálogo de productos, personalizarlos con opciones como colores, tallas, grabado, y realizar pedidos directamente.

### 🎯 Ideal para:

- Tiendas de merchandise corporativo
- Negocio de regalos personalizados
- Marcas que venden productos customizables
- Emprendimientos de personalización de productos

---

## 🚀 Funcionalidades

### Para Clientes:
- 📦 **Catálogo de productos** con búsqueda y filtros por categoría y precio
- 🛒 **Carrito de compras** con personalización por producto (color, tamaño, texto)
- 💳 **Checkout simplificado** con datos del cliente
- 📋 **Historial de pedidos** para rastrear estado
- 🔐 **Autenticación** con registro/login (Laravel Breeze)

### Para Administradores:
- 📊 **Dashboard** con estadísticas de pedidos
- 🏷️ **Gestión de productos** (crear, editar, eliminar)
- 📂 **Gestión de categorías**
- 📋 **Gestión de pedidos** (ver detalles, cambiar estados)
- 🔐 **Panel de admin** separado con autenticación independiente

---

## 🛠️ Tech Stack

| Capa | Tecnología |
|------|-------------|
| Backend | Laravel 13 (PHP 8.3+) |
| Frontend | Blade Templates + Tailwind CSS + Alpine.js |
| Build | Vite |
| Base de datos | SQLite (desarrollo) / MySQL, PostgreSQL (producción) |
| Auth | Laravel Breeze |
| Testing | Pest PHP |

---

## 📋 Estructura del Proyecto

```
hakesa/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/           # Controladores del panel admin
│   │   ├── Auth/            # Controladores de autenticación
│   │   ├── CartController.php
│   │   ├── CatalogController.php
│   │   ├── CheckoutController.php
│   │   └── ClientOrderController.php
│   ├── Models/              # Modelos Eloquent
│   │   ├── Product.php      # Productos del catálogo
│   │   ├── Category.php     # Categorías
│   │   ├── Cart.php         # Carrito de compras
│   │   ├── CartItem.php     # Items del carrito
│   │   ├── Order.php        # Pedidos
│   │   └── OrderItem.php    # Items de cada pedido
│   └── Middleware/
│       └── AdminAuth.php    # Middleware de autenticación admin
├── resources/views/
│   ├── admin/               # Vistas del panel admin
│   ├── auth/                # Vistas de autenticación
│   ├── cart/                # Carrito
│   ├── catalog/             # Catálogo público
│   ├── checkout/            # Checkout
│   └── orders/              # Pedidos del cliente
├── database/
│   ├── migrations/          # Tablas del sistema
│   └── seeders/             # Datos de prueba
└── routes/
    └── web.php              # Todas las rutas
```

---

## ⚙️ Cómo Funciona

### Flujo del Cliente:
1. El cliente se registra e inicia sesión
2. Navega el catálogo, filtra por categoría/precio
3. Agrega productos al carrito con personalización (color, tamaño, texto)
4. Completa el checkout con datos de entrega
5. El pedido se registra con estado "pendiente"
6. El cliente puede ver el historial de sus pedidos

### Flujo del Administrador:
1. Accede al panel admin (`/admin/login`)
2. Gestiona el catálogo de productos
3. Gestiona categorías
4. Ve los pedidos recibidos y actualiza su estado (pending → completed, etc.)

---

## 🚦 Estados de Pedido

- **pending** - Pedido realizado, esperando atención
- **processing** - Pedido en proceso
- **completed** - Pedido completado/entregado
- **cancelled** - Pedido cancelado

---

## 💻 Primeros Pasos

### Requisitos

- PHP 8.3+
- Composer
- Node.js 18+
- SQLite (desarrollo)

### Instalación

```bash
# Clonar el repositorio
git clone https://github.com/Kevin7819/Hakesa.git
cd Hakesa

# Instalar dependencias PHP
composer install

# Instalar dependencias frontend
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Ejecutar migraciones y seeders
php artisan migrate --seed

# Iniciar servidor de desarrollo
composer dev
```

### Usuarios de Prueba

**Cliente:**
- Email: `cliente@test.com`
- Password: `password`

**Administrador:**
- Email: `admin@hakesa.com`
- Password: `admin123`

### Comandos útiles

```bash
# Ejecutar tests
composer test

# Formatear código
vendor/bin/pint

# Limpiar caché
php artisan config:clear && php artisan cache:clear && php artisan route:clear
```

---

## 🤝 Contribuir

¿Querés contribuir a Hakesa?encil 
1. Fork del proyecto
2. Creá una rama (`git checkout -b feature/nueva-funcionalidad`)
3. Commitá tus cambios (`git commit -m 'feat: nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abrí un Pull Request

---

## 📄 Licencia

MIT License - ver [LICENSE](https://opensource.org/licenses/MIT) para más detalles.

---

## 👨‍💻 Autor

**Kevin7819** - https://github.com/Kevin7819