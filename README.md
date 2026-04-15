# Sistema de Cotización de Servicios con Carrito Dinámico

## Descripción General

Sistema web profesional desarrollado en PHP con arquitectura MVC para la gestión de cotizaciones de servicios. Permite a usuarios visualizar un catálogo de servicios, gestionar un carrito dinámico, y generar cotizaciones automáticas con cálculos de subtotal, descuentos, IVA y total.

## ✨ Características Principales

- ✅ Catálogo de 12+ servicios en 3+ categorías
- ✅ Carrito dinámico con AJAX (sin recargas)
- ✅ Cálculos automáticos: subtotal, descuentos, IVA (13%), total
- ✅ Generación de cotizaciones (COT-YYYY-####)
- ✅ Validación dual (frontend JS + backend PHP)
- ✅ Arquitectura MVC completa con POO
- ✅ Interfaz responsiva Bootstrap 5
- ✅ Sistema de sesiones
- ✅ Endpoints AJAX
- ✅ Base de datos MySQL/PDO

## 📁 Estructura del Proyecto

````
Sistema_cotizacion_carrito_dinamico/
├── /app
│   ├── /config
│   │   └── database.php
│   ├── /controllers
│   │   ├── AuthController.php
│   │   ├── CartController.php
│   │   ├── QuoteController.php
│   │   └── ServiceController.php
│   ├── /models
│   │   ├── User.php
│   │   ├── Service.php
│   │   ├── Quote.php
│   │   └── QuoteDetail.php
│   └── /views
│       ├── /auth
│       ├── /cart
│       ├── /services
│       └── /quotes
├── /api
│   ├── add-to-cart.php
│   ├── update-cart.php
│   ├── remove-from-cart.php
│   └── process-quote.php
├── /public
│   ├── index.php
│   └── /assets
│       ├── /css
│       ├── /js
│       └── /img
└── README.md

## 🔧 Requisitos

- PHP 7.4+
- MySQL 5.7+
- Apache con mod_rewrite
- Bootstrap 5.3.2 (CDN)

## 📦 Instalación

1. Base de datos:
```sql
CREATE DATABASE sistema_cotizacion;
````

2. Configurar `/app/config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistema_cotizacion');
```

3. Acceder a: `http://localhost/Sistema_cotizacion_carrito_dinamico/public/index.php`

## 🎯 Funcionalidades

### Catálogo

- 12+ servicios en cards responsivas
- Organizados por categoría
- Información completa del servicio

### Carrito Dinámico

- Agregar/eliminar servicios (AJAX)
- Cantidades 1-10 por servicio
- Totales en tiempo real

### Cálculos

```
Subtotal = Precio × Cantidad
Descuento:
  - $500-$999: 5%
  - $1,000-$2,499: 10%
  - $2,500+: 15%
IVA = (Subtotal - Descuento) × 13%
Total = Subtotal - Descuento + IVA
```

### Cotizaciones

- Código único: COT-YYYY-####
- Validación de datos cliente
- Vencimiento: 7 días
- Historial de cotizaciones

## 🔐 Seguridad

- Contraseñas hasheadas (bcrypt)
- Validación dual
- Sanitización de datos
- Prepared statements
- Control de sesiones

## 📱 Diseño

- Bootstrap 5 responsivo
- Mobile-first approach
- Animaciones suaves
- Interfaz intuitiva

## 📚 Clases Principales

### Service

- id, nombre, descripción, precio, categoría
- getCatalogoCompleto()

### Quote

- código, cliente, items, subtotal, descuento, iva, total
- agregarItem(), calcularSubtotal(), calcularDescuento(), calcularIVA(), calcularTotal()
- generarCodigo(), validarMonto()

### User

- id, nombre, email, password, rol
- hashPassword(), verifyPassword()

## 🔗 API Endpoints

- POST `/api/add-to-cart.php` - Agregar servicio
- POST `/api/update-cart.php` - Actualizar cantidad
- POST `/api/remove-from-cart.php` - Eliminar servicio
- POST `/api/process-quote.php` - Generar cotización

## 👥 Autor

Desarrollado como proyecto académico - Universidad Don Bosco
Escuela de Computación | Lenguajes Interpretados en Servidor

---

# Sistema-de-Cotizaci-n-de-Servicios-con-Carrito-Din-mico
