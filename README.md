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

##  Guía de Instalación y Despliegue Local

Sigue estos pasos para configurar y ejecutar el proyecto en tu propio entorno de desarrollo:

1. **Clonar el repositorio:**
   Descarga o haz un `pull` de la rama `main` de este repositorio. Asegúrate de colocar los archivos dentro de la carpeta pública de tu servidor local (por ejemplo, la carpeta `htdocs` si usas XAMPP, o `www` si usas WampServer).

2. **Iniciar el entorno local:**
   Abre tu programa de servidor local (XAMPP, WampServer o el de tu preferencia) y asegúrate de encender los servicios de **Apache** y **MySQL**.

3. **Configurar la base de datos:**
   Abre tu gestor de bases de datos MySQL (como phpMyAdmin o DBeaver). Crea una base de datos e importa el archivo `cotizador_db.sql` que se incluye en este proyecto para cargar la estructura y los datos iniciales.

4. **Ejecutar el proyecto:**
   Abre tu navegador web de preferencia e ingresa a la ruta local donde guardaste el proyecto (por ejemplo: `http://localhost/tu-carpeta-del-proyecto/public/`).

---

### 🔐 Credenciales de Acceso

Para probar el sistema con privilegios completos, utiliza las siguientes credenciales de administrador:

* **Correo:** `admin@ejemplo.com`
* **Contraseña:** `password`

> **💡 Nota sobre los roles:** El sistema cuenta con control de accesos. Por defecto, todos los usuarios nuevos que se registren en la plataforma tendrán asignado el rol estándar de **"user"**.

---

# Sistema-de-Cotizaci-n-de-Servicios-con-Carrito-Din-mico
