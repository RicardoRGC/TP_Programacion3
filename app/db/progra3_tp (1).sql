-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-11-2022 a las 21:50:13
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `progra3_tp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuenta`
--

CREATE TABLE `cuenta` (
  `id` int(11) NOT NULL,
  `precioCuenta` float DEFAULT NULL,
  `codigoPedido` int(11) DEFAULT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE `encuestas` (
  `id` int(11) NOT NULL,
  `codigoMesa` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `codigoPedido` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `puntuacionMesa` int(11) NOT NULL,
  `puntuacionRestaurante` int(11) NOT NULL,
  `puntuacionMozo` int(11) NOT NULL,
  `puntuacionCocinero` int(11) NOT NULL,
  `detalles` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `encuestas`
--

INSERT INTO `encuestas` (`id`, `codigoMesa`, `codigoPedido`, `puntuacionMesa`, `puntuacionRestaurante`, `puntuacionMozo`, `puntuacionCocinero`, `detalles`, `fecha`) VALUES
(1, '4', 'kvzb2', 2, 6, 5, 4, 'muy rica comida.', '2022-11-27'),
(2, '4', 'kvzb2', 10, 6, 5, 4, 'muy rica comida.', '2022-11-29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `numeroMesa` int(11) NOT NULL,
  `estado` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `nombreCliente` varchar(50) COLLATE utf8mb4_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `numeroMesa`, `estado`, `nombreCliente`) VALUES
(1, 0, 'Esperando Pedido', 'ramon'),
(2, 0, 'Esperando Pedido', 'ramon'),
(3, 0, 'libre', ''),
(4, 0, 'libre', ''),
(5, 0, 'libre', ''),
(6, 0, 'libre', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `codigoPedido` varchar(5) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `codigoMesa` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `demoraPedido` int(11) DEFAULT NULL,
  `estado` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL DEFAULT 'pendiente',
  `foto` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `fecha_alta` date DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `codigoPedido`, `codigoMesa`, `idUsuario`, `demoraPedido`, `estado`, `foto`, `fecha_alta`, `fecha_baja`) VALUES
(10023, '7g53n', 1, 16, 0, 'listo', './pedido/1-7g53n.jpeg', '2022-11-29', NULL),
(10024, 'y8wph', 2, 16, NULL, 'pendiente', './pedido/2-y8wph.jpeg', '2022-11-29', NULL),
(10025, 'lq4uh', 1, 16, NULL, 'pendiente', './pedido/1-lq4uh.jpeg', '2022-11-29', NULL),
(10026, 'ptv9y', 2, 16, NULL, 'pendiente', './pedido/2-ptv9y.jpeg', '2022-11-29', NULL),
(10027, '0ld9n', 2, 16, NULL, 'pendiente', './pedido/2-0ld9n.jpeg', '2022-11-29', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `tipo` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `precio` float NOT NULL,
  `fecha_alta` date DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `tipo`, `precio`, `fecha_alta`, `fecha_baja`) VALUES
(1, 'empanadas', 'comida', 200, NULL, NULL),
(2, 'milanesa', 'comida', 200, NULL, NULL),
(3, 'coca', 'bebida', 200, NULL, NULL),
(4, 'vino', 'bebida', 200, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productospedidos`
--

CREATE TABLE `productospedidos` (
  `id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `estado` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL DEFAULT 'pendiente',
  `idProducto` int(11) NOT NULL,
  `demora` int(11) DEFAULT NULL,
  `codigoPedido` varchar(110) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `fecha_alta` date DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL,
  `id_preparador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `productospedidos`
--

INSERT INTO `productospedidos` (`id`, `cantidad`, `estado`, `idProducto`, `demora`, `codigoPedido`, `fecha_alta`, `fecha_baja`, `id_preparador`) VALUES
(48, 2, 'listo', 1, 0, '7g53n', '2022-11-29', NULL, 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros_login`
--

CREATE TABLE `registros_login` (
  `id` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `tipo` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `fechaIngreso` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `registros_login`
--

INSERT INTO `registros_login` (`id`, `idUsuario`, `nombre`, `tipo`, `fechaIngreso`) VALUES
(21, 16, 'mozo', 'mozo', '2022-11-29 14:54:10'),
(22, 14, 'socio', 'socio', '2022-11-29 14:54:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `clave` varchar(1500) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `tipo` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `fecha_alta` date DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `clave`, `tipo`, `fecha_alta`, `fecha_baja`) VALUES
(14, 'socio', '$2y$10$u7rvvb6/ll4fIwHIuUg6hu3zonern4DzURruQSj2cisMGxLdvSu2G', 'socio', '2022-11-25', NULL),
(15, 'cocinero1', '$2y$10$mpjQTaSJVKgt0/rSJWam4OuYJi8dBbfPmuKhhUVWvkZQJ1ujF6JUS', 'cocinero', '2022-11-29', '2022-11-29'),
(16, 'mozo', '$2y$10$Vs3CuiY2I226/rsqXIUGaedsKVl5S7Wmig1QUkqqml0InyEBUnCTK', 'mozo', '2022-11-29', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productospedidos`
--
ALTER TABLE `productospedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registros_login`
--
ALTER TABLE `registros_login`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cuenta`
--
ALTER TABLE `cuenta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10028;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productospedidos`
--
ALTER TABLE `productospedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `registros_login`
--
ALTER TABLE `registros_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
