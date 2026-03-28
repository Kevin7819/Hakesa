<p align="center"><a href="https://hakesa.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Hakesa Logo"></a></p>

<p align="center">
<a href="https://github.com/hakesa/hakesa/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/hakesa/core"><img src="https://img.shields.io/packagist/dt/hakesa/core" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/hakesa/core"><img src="https://img.shields.io/packagist/v/hakesa/core" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/hakesa/core"><img src="https://img.shields.io/packagist/l/hakesa/core" alt="License"></a>
</p>

## About Hakesa

Hakesa es un sistema de gestión de aceleradoras de startups, diseñado para administrar empresas, postulaciones, programas de aceleración, Mentorías, y inversores. Construido sobre Laravel + React/Vite.

## Características

- Gestión integral de aceleradoras y startups
- Postulaciones y seguimiento de candidatos
- Programas de aceleración personalizados
- Sistema de mentorías
- Gestión de inversores y rondas de inversión
- Panel administrativo con Laravel Breeze
- Interfaz moderna con React/Vite + Tailwind CSS

## Tech Stack

- **Backend**: Laravel 13 (PHP 8.3+)
- **Frontend**: Vite + React + Tailwind CSS
- **Base de datos**: SQLite (desarrollo) / MySQL, PostgreSQL (producción)
- **Autenticación**: Laravel Breeze

## Primeros Pasos

### Requisitos

- PHP 8.3+
- Composer
- Node.js 18+
- SQLite (para desarrollo)

### Instalación

```bash
# Clonar el repositorio
git clone https://github.com/hakesa/hakesa.git
cd hakesa

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

### Comandos de Desarrollo

```bash
# Ejecutar tests
composer test

# Formatear código (Pint)
vendor/bin/pint

# Limpiar caché
php artisan config:clear && php artisan cache:clear && php artisan route:clear
```

## Estructura del Proyecto

```
hakesa/
├── app/                  # Código de la aplicación
│   ├── Http/Controllers # Controladores
│   ├── Models/          # Modelos Eloquent
│   └── Policies/        # Políticas de autorización
├── resources/views/     # Vistas Blade
├── routes/              # Rutas web y API
├── database/            # Migraciones y seeders
└── tests/               # Tests Pest PHP
```

## Contribuir

¿Querés contribuir a Hakesa? ¡Genial! Por favor leé nuestras [guías de contribución](https://github.com/hakesa/hakesa/blob/main/CONTRIBUTING.md) antes de enviar un PR.

## Licencia

MIT License - ver [LICENSE](https://opensource.org/licenses/MIT) para más detalles.