═══════════════════════════════════════════════════════════════════════════════
                    GUÍA DE COLORES Y SECCIONES - LANDING PAGE
                         Gracia Creativa (Hakesa Project)
═══════════════════════════════════════════════════════════════════════════════

═══════════════════════════════════════════════════════════════════════════════
                              PALETA DE COLORES
═══════════════════════════════════════════════════════════════════════════════

La paleta de colores está definida en tailwind.config.js y CSS personalizada.

┌─────────────────────────────────────────────────────────────────────────────┐
│ COLORES PRINCIPALES (Brand Colors)                                          │
└─────────────────────────────────────────────────────────────────────────────┘

  ┌─────────────────┬──────────────┬─────────────────────────────────────────┐
  │ Color           │ Hexadecimal  │ Uso Principal                           │
  ├─────────────────┼──────────────┼─────────────────────────────────────────┤
  │ PRIMARY         │ #BF5098      │ CTAs, botones principales, highlights   │
  │ PRIMARY-DARK    │ #A84385      │ Estados hover de botones                │
  │ PRIMARY-LIGHT   │ #D46BB5      │ Fondos sutiles, acentos                  │
  ├─────────────────┼──────────────┼─────────────────────────────────────────┤
  │ SECONDARY       │ #7D5A8C      │ Iconos, elementos de soporte             │
  │ SECONDARY-DARK  │ #6A4C78      │ Estados hover                            │
  │ SECONDARY-LIGHT │ #9A7AA8      │ Fondos, badges secundarios               │
  ├─────────────────┼──────────────┼─────────────────────────────────────────┤
  │ ACCENT          │ #B6D936      │ Badges, tags, highlights especiales     │
  │ ACCENT-DARK     │ #A3C22E      │ Estados hover                            │
  │ ACCENT-LIGHT    │ #C8E058      │ Fondos sutiles                           │
  ├─────────────────┼──────────────┼─────────────────────────────────────────┤
  │ BASE            │ #0D0D0D      │ Texto principal, backgrounds oscuros    │
  │ BASE-LIGHT      │ #262626      │ Backgrounds de tarjetas                 │
  │ BASE-LIGHTER    │ #404040      │ Bordes, elementos divisorios             │
  └─────────────────┴──────────────┴─────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│ COLORES NEUTRALES (Tailwind grayscale)                                      │
└─────────────────────────────────────────────────────────────────────────────┘

  - bg-gray-100 a bg-gray-900 para fondos secundarios
  - text-gray-100 a text-gray-900 para texto
  - border-gray-600/700 para bordes de tarjetas

┌─────────────────────────────────────────────────────────────────────────────┐
│ GRADIENTES DEFINIDOS                                                        │
└─────────────────────────────────────────────────────────────────────────────┘

  1. gradient-hero (Hero Section):
     Degradado de PRIMARY (#BF5098) → PRIMARY-DARK (#A84385) → SECONDARY (#7D5A8C)
     CSS: app.css línea 170-172

  2. gradient-gracia (Cards de pasos):
     Degradado de PRIMARY (#BF5098) → SECONDARY (#7D5A8C)

  3. gradient-gracia-warm:
     Degradado de PRIMARY (#BF5098) → ACCENT (#B6D936)

  4. gradient-gracia-cool:
     Degradado de SECONDARY (#7D5A8C) → ACCENT (#B6D936)


═══════════════════════════════════════════════════════════════════════════════
                           ESTRUCTURA DEL LANDING
═══════════════════════════════════════════════════════════════════════════════

El archivo principal está en: resources/views/welcome.blade.php

Secciones en orden:

┌─────────────────────────────────────────────────────────────────────────────┐
│ 1. HERO SECTION (#inicio)                                                   │
│    Líneas: 10-66                                                            │
└─────────────────────────────────────────────────────────────────────────────┘

  Background: gradient-hero (degradado magenta/púrpura)
  Altura: min-h-[90vh] (90% de la altura viewport)
  Elementos decorativos:
    - Círculos borrosos (blur-3xl) en gris y accent
    - Forma de ola al final (SVG wave) en bg-gray-800 (#1f2937)

  Contenido:
    - Badge superior: bg-gray-800/20 (semi-transparente)
    - Título principal: texto blanco, palabra "huella" en text-gracia-accent
    - Botón principal: btn-gracia (bg-gracia-primary)
    - Botón secundario: btn-gracia-outline (border white)
    - Imagen/logo: contenedor bg-gray-800/10 con blur
    - Badge flotante "Costa Rica": bg-gracia-accent, texto blanco

┌─────────────────────────────────────────────────────────────────────────────┐
│ 2. SERVICIOS SECTION (#servicios)                                          │
│    Líneas: 71-125                                                           │
└─────────────────────────────────────────────────────────────────────────────┘

  Background: bg-gray-800 (#1f2937)
  Padding: section-padding (py-20 lg:py-28)

  Contenido:
    - Label: bg-gracia-primary/10, text-gracia-primary
    - Título: section-title text-gracia-primary
    - Subtítulo: section-subtitle (text-gray-400)

  Tarjetas de servicios (card-gracia):
    - Fondo: bg-gray-800 con borde border-gray-700
    - Hover: hover:-translate-y-1, shadow aumentado

  4 Servicios con iconos de colores:
    1. Sublimación: bg-gracia-primary/10, icon text-gracia-primary
    2. Corte Láser: bg-gracia-secondary/10, icon text-gracia-secondary
    3. Vinil: bg-gracia-accent/10, icon text-gracia-accent
    4. Envíos CR: bg-gracia-accent-dark/10, icon text-gracia-accent-dark

┌─────────────────────────────────────────────────────────────────────────────┐
│ 3. CATÁLOGO SECTION (#catalogo)                                            │
│    Líneas: 130-218                                                          │
└─────────────────────────────────────────────────────────────────────────────┘

  Background: bg-gray-900 (#111827)
  Padding: section-padding

  Label: bg-gracia-secondary-light/30, text-teal-700
  Título: section-title text-gracia-primary
  Subtítulo: section-subtitle

  Carrusel de productos (Alpine.js):
    - Contenedor: carousel-container rounded-2xl
    - Tarjetas: card-gracia con imagen aspect-square
    - Badge de categoría: bg-gracia-secondary/20, text-gracia-secondary-light
    - Precio: text-2xl font-bold text-gracia-primary-dark
    - Botón "Consultar": btn-gracia

  Navegación del carrusel:
    - Flechas: bg-gray-800, hover:bg-gracia-primary
    - Dots: bg-gray-300 inactivo, bg-gracia-primary activo

  Estados empty:
    - Fondo de imagen placeholder: gradient de primary/secondary

┌─────────────────────────────────────────────────────────────────────────────┐
│ 4. CÓMO FUNCIONA SECTION                                                   │
│    Líneas: 223-263                                                          │
└─────────────────────────────────────────────────────────────────────────────┘

  Background: bg-gray-800
  Padding: section-padding

  Label: bg-gracia-accent-dark/10, text-yellow-700
  Título: section-title text-gracia-primary

  Pasos (3 círculos conectados con línea degradada):
    - Línea de conexión: gradient de primary → secondary → accent
    - Paso 1 (Elige): gradient-gracia, círculo con número
    - Paso 2 (Personaliza): bg-gracia-secondary
    - Paso 3 (Recibe): bg-gracia-accent

  Sombras: shadow-xl shadow-[color]/20 para dar profundidad

┌─────────────────────────────────────────────────────────────────────────────┐
│ 5. TESTIMONIOS SECTION (#testimonios)                                       │
│    Líneas: 268-378                                                          │
└─────────────────────────────────────────────────────────────────────────────┘

  Background: bg-gray-900
  Padding: section-padding

  Label: bg-gracia-primary/10, text-gracia-primary
  Título: section-title text-gracia-primary

  Formulario de comentarios:
    - Tarjeta: card-gracia
    - Textarea: border-gray-600, bg-gray-800, focus:ring-gracia-primary
    - Botón enviar: btn-gracia

  Tarjetas de comentarios:
    - Fondo: card-gracia
    - Avatar inicial: bg-gracia-primary/20, texto text-gracia-primary

  Estado vacío: bg-gracia-secondary/10, icon text-gracia-secondary

┌─────────────────────────────────────────────────────────────────────────────┐
│ 6. CONTACTO / CTA SECTION (#contacto)                                      │
│    Líneas: 383-403                                                          │
└─────────────────────────────────────────────────────────────────────────────┘

  Background: gradient-hero (mismo que hero)
  Decoración: círculos borrosos igual que hero

  Contenido:
    - Título: texto blanco
    - Subtítulo: text-white/80
    - Botón WhatsApp: bg-green-500 hover:bg-green-600
      - Sombras: shadow-2xl shadow-green-500/30
      - Hover: hover:-translate-y-1
    - Texto inferior: text-white/60

═══════════════════════════════════════════════════════════════════════════════
                        COMPONENTES REUTILIZABLES
═══════════════════════════════════════════════════════════════════════════════

Definidos en resources/css/app.css:

┌─────────────────┬────────────────────────────────────────────────────────────┐
│ Componente      │ Descripción                                                 │
├─────────────────┼────────────────────────────────────────────────────────────┤
│ btn-gracia      │ Botón primario: bg-primary, sombra, hover animation       │
│ btn-gracia-outline │ Botón outline: border-primary, hover fill            │
│ btn-teal        │ Botón secundario: bg-secondary                            │
│ btn-dark        │ Botón oscuro: bg-gray-700                                 │
│ card-gracia     │ Tarjeta oscura: bg-gray-800, borde, hover lift            │
│ card-dark       │ Tarjeta simple: bg-gray-800, borde                        │
│ section-padding │ Padding de secciones: py-20 lg:py-28                     │
│ section-title   │ Títulos: text-3xl md:text-4xl lg:text-5xl font-bold      │
│ section-subtitle│ Subtítulos: text-gray-400, max-w-2xl                    │
│ gradient-hero   │ Degradado hero: primary → primary-dark → secondary        │
│ gradient-gracia │ Degradado genérico: primary → secondary                    │
└─────────────────┴────────────────────────────────────────────────────────────┘

═══════════════════════════════════════════════════════════════════════════════
                              RESUMEN VISUAL
═══════════════════════════════════════════════════════════════════════════════

  SECCIÓN              │ BACKGROUND        │ COLOR DESTACADO
  ─────────────────────┼───────────────────┼───────────────────
  Hero                 │ gradient-hero    │ Primary (#BF5098)
  Servicios            │ gray-800         │ Primary + Secondary
  Catálogo             │ gray-900         │ Primary
  Cómo Funciona        │ gray-800         │ Primary + Secondary + Accent
  Testimonios          │ gray-900         │ Primary
  Contacto             │ gradient-hero    │ Primary + Green (WhatsApp)

  Textos: siempre white o white/80 para párrafos, gray-400 para secundarias
  Fondos oscuros: gray-800, gray-900 (muy oscuros para contraste)
  Elementos interactivos: siempre con transitions de 300ms

═══════════════════════════════════════════════════════════════════════════════
                            ARCHIVOS RELACIONADOS
═══════════════════════════════════════════════════════════════════════════════

  - tailwind.config.js        → Definición de colores brand
  - resources/css/app.css     → Componentes personalizados y gradientes
  - resources/views/wake.php → Estructura completa del landing

═══════════════════════════════════════════════════════════════════════════════
                                 NOTAS FINALES
═══════════════════════════════════════════════════════════════════════════════

  ✓ La marca usa un tono "dark mode" por defecto
  ✓ El PRIMARY (#BF5098) es el color más usado para CTAs
  ✓ El ACCENT (#B6D936) se usa para destacar elementos especiales
  ✓ Todos los botones tienen animaciones hover con translate-y
  ✓ Las tarjetas tienen efecto "lift" al hacer hover
  ✓ Los gradientes dan profundidad y sensación moderna

  Created: 2026-04-16
  Project: Hakesa - Gracia Creativa
═══════════════════════════════════════════════════════════════════════════════
