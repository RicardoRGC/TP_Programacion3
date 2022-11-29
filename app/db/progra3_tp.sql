-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-11-2022 a las 13:34:26
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
(1, '4', 'kvzb2', 2, 6, 5, 4, 'muy rica comida.', '2022-11-27');

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
(1, 0, 'libre', ''),
(2, 0, 'ocupada', 'ramon'),
(3, 0, 'ocupada', 'ramon'),
(4, 0, 'libre', ''),
(5, 0, 'ocupada', 'ramon'),
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
(10017, 'kvzb2', 4, 8, 20, 'preparando', '', NULL, NULL),
(10018, '3paue', 5, 8, NULL, 'pendiente', './pedido/7-3paue.jpeg', NULL, NULL),
(10019, '0nye7', 2, 7, NULL, 'listo', '', '2022-11-23', NULL),
(10020, 'bh8ql', 2, 7, NULL, 'pendiente', '', '2022-11-23', NULL),
(10021, 't5r1h', 3, 7, NULL, 'pendiente', '', '2022-11-23', NULL);

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
(40, 5, 'listo', 1, 0, 'kvzb2', NULL, NULL, NULL),
(41, 5, 'listo', 4, 0, 'kvzb2', NULL, NULL, NULL),
(42, 5, 'preparando', 4, 20, 'kvzb2', '2022-11-23', NULL, NULL),
(43, 5, 'pendiente', 4, NULL, 'kvzb2', '2022-11-23', NULL, NULL),
(44, 5, 'listo', 2, 0, 'kvzb2', '2022-11-25', NULL, 9),
(45, 5, 'pendiente', 1, NULL, 'kvzb2', '2022-11-25', NULL, NULL);

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
(14, 'socio', '$2y$10$u7rvvb6/ll4fIwHIuUg6hu3zonern4DzURruQSj2cisMGxLdvSu2G', 'socio', '2022-11-25', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10022;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productospedidos`
--
ALTER TABLE `productospedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `registros_login`
--
ALTER TABLE `registros_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
