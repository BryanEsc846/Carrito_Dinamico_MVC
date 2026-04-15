-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-04-2026 a las 00:21:38
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cotizador_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quotes`
--

CREATE TABLE `quotes` (
  `id` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cliente_nombre` varchar(100) NOT NULL,
  `cliente_empresa` varchar(100) DEFAULT NULL,
  `cliente_email` varchar(100) NOT NULL,
  `cliente_telefono` varchar(20) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `descuento` decimal(10,2) NOT NULL,
  `iva` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha_emision` datetime NOT NULL,
  `fecha_validez` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quote_details`
--

CREATE TABLE `quote_details` (
  `id` int(11) NOT NULL,
  `quote_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `categoria` enum('Desarrollo Web','Marketing','Soporte Técnico') NOT NULL,
  `imagen` varchar(255) DEFAULT 'default.jpg',
  `delivery` varchar(100) DEFAULT 'Por definir'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `services`
--

INSERT INTO `services` (`id`, `nombre`, `descripcion`, `precio`, `categoria`, `imagen`, `delivery`) VALUES
(1, 'Diseño Web Básico', 'Sitio web informativo responsive', 600.00, 'Desarrollo Web', 'diseno-web-basico.jpg', 'Por definir'),
(2, 'Tienda Online', 'E-commerce completo', 1200.00, 'Desarrollo Web', 'tienda-online.jpg', 'Por definir'),
(3, 'Landing Page', 'Página optimizada para conversión', 450.00, 'Desarrollo Web', 'landing page.webp', 'Por definir'),
(4, 'SEO Básico', 'Optimización para buscadores', 500.00, 'Marketing', 'seo-basico.webp', 'Por definir'),
(5, 'Publicidad en Redes', 'Campañas digitales', 700.00, 'Marketing', 'publicidad-redes.jpg', 'Por definir'),
(6, 'Email Marketing', 'Automatización de correos', 650.00, 'Marketing', 'email-marketing.webp', 'Por definir'),
(7, 'Soporte Técnico Mensual', 'Mantenimiento empresarial', 300.00, 'Soporte Técnico', 'soporte-tecnico.webp', 'Por definir'),
(8, 'Instalación de Redes', 'Configuración de red empresarial', 900.00, 'Soporte Técnico', 'instalacion-redes.webp', 'Por definir'),
(9, 'Respaldo de Información', 'Backup y recuperación de datos', 400.00, 'Soporte Técnico', 'respaldo-informacion.webp', 'Por definir'),
(10, 'Auditoría Web', 'Análisis técnico completo', 700.00, 'Desarrollo Web', 'auditoria-web.webp', 'Por definir'),
(11, 'Branding Empresarial', 'Diseño de identidad visual', 1100.00, 'Marketing', 'branding-empresarial.webp', 'Por definir'),
(12, 'Seguridad Informática', 'Protección contra vulnerabilidades', 1500.00, 'Soporte Técnico', 'seguridad-informatica.webp', 'Por definir');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','user') DEFAULT 'user',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre`, `email`, `password`, `rol`, `creado_en`) VALUES
(1, 'Administrador', 'admin@ejemplo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-04-14 22:02:53');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `quote_details`
--
ALTER TABLE `quote_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quote_id` (`quote_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indices de la tabla `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `quote_details`
--
ALTER TABLE `quote_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `quotes`
--
ALTER TABLE `quotes`
  ADD CONSTRAINT `quotes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `quote_details`
--
ALTER TABLE `quote_details`
  ADD CONSTRAINT `quote_details_ibfk_1` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quote_details_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
