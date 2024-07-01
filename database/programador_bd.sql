-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-07-2024 a las 07:48:57
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `programador_bd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `competencias`
--

CREATE TABLE `competencias` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `competencia_id` int(11) DEFAULT NULL,
  `resultado_id` int(11) DEFAULT NULL,
  `programa_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horas_acumuladas`
--

CREATE TABLE `horas_acumuladas` (
  `id` int(11) NOT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `mes` int(11) DEFAULT NULL,
  `año` int(11) DEFAULT NULL,
  `horas_acumuladas` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instructores`
--

CREATE TABLE `instructores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `tipo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `instructores`
--

INSERT INTO `instructores` (`id`, `nombre`, `apellido`, `tipo_id`) VALUES
(1, 'Julián ', 'Gasca Cuellar', 1),
(2, 'Maria Eugenia', 'Florez Rocha', 2),
(3, 'Gilberto', 'Murcia', 1),
(4, 'Julian Felipe', 'Pedroza', 1),
(5, 'Mairalejandra', 'Ramirez Cuenca', 2),
(6, 'Dennys Adriana ', 'Quintero Sandoval', 1),
(7, 'Adriana Marcela ', 'Alarcón Rojas', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programaciones`
--

CREATE TABLE `programaciones` (
  `id` int(11) NOT NULL,
  `ficha` varchar(255) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `resultado_aprendizaje` varchar(255) NOT NULL,
  `programa_formacion_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programaciones`
--

INSERT INTO `programaciones` (`id`, `ficha`, `instructor_id`, `start`, `end`, `resultado_aprendizaje`, `programa_formacion_id`) VALUES
(1, '2502636', 3, '2024-06-20 06:00:00', '2024-06-20 11:59:59', 'Ejemplo', NULL),
(2, '2502636', 3, '2024-06-21 06:00:00', '2024-06-21 11:59:59', 'Ejemplo', NULL),
(3, '2502663', 3, '2024-06-20 12:00:00', '2024-06-20 17:59:59', 'Ejemplo', NULL),
(4, '2502663', 3, '2024-06-21 12:00:00', '2024-06-21 17:59:59', 'Ejemplo', NULL),
(5, '2502663', 3, '2024-06-20 12:00:00', '2024-06-20 17:59:59', 'Ejemplo', NULL),
(6, '2502636', 3, '2024-06-17 06:00:00', '2024-06-17 11:59:59', 'Ejemplo', NULL),
(7, '2502636', 3, '2024-06-18 06:00:00', '2024-06-18 11:59:59', 'Ejemplo', NULL),
(8, '2502663', 3, '2024-06-17 12:00:00', '2024-06-17 17:59:59', 'Ejemplo', NULL),
(9, '2502663', 3, '2024-06-18 12:00:00', '2024-06-18 17:59:59', 'Ejemplo', NULL),
(10, '2502663', 3, '2024-06-18 12:00:00', '2024-06-18 17:59:59', 'Ejemplo', NULL),
(11, '2502663', 3, '2024-06-18 12:00:00', '2024-06-18 17:59:59', 'Ejemplo', NULL),
(12, '2502636', 2, '2024-06-24 06:00:00', '2024-06-24 11:59:59', 'OLA MUNDO SIN H', NULL),
(13, '2502636', 2, '2024-06-25 06:00:00', '2024-06-25 11:59:59', 'OLA MUNDO SIN H', NULL),
(14, '123456', 1, '2024-06-11 06:00:00', '2024-06-11 11:59:59', 'ejemplo 3', NULL),
(15, '123456', 1, '2024-06-12 06:00:00', '2024-06-12 11:59:59', 'ejemplo 3', NULL),
(16, '2502636', 6, '2024-06-19 06:00:00', '2024-06-19 11:59:59', 'Ejemplo de resultado', NULL),
(17, '2502636', 1, '2024-06-26 06:00:00', '2024-06-26 11:59:59', 'AQUI VA EL NOMBRE DEL RESULTADO DE PARENDIZAJE QUE VANA DESARROLLAR LOS INSTRUCTORES', NULL),
(18, '2502636', 6, '2024-06-26 06:00:00', '2024-06-26 11:59:59', 'AQUI VA EL NOMBRE DEL RESULTADO DE PARENDIZAJE QUE VANA DESARROLLAR LOS INSTRUCTORES', NULL),
(19, '2502636', 7, '2024-06-27 06:00:00', '2024-06-27 11:59:59', 'Ejemplo', NULL),
(20, '2502636', 4, '2024-06-27 12:00:00', '2024-06-27 17:59:59', 'Ejemplo222', NULL),
(21, '2502636', 4, '2024-06-27 12:00:00', '2024-06-27 17:59:59', 'Ejemplo222', NULL),
(22, '2502636', 7, '2024-06-27 18:00:00', '2024-06-27 23:00:00', 'XYZ', NULL),
(23, '2502636', 7, '2024-06-27 23:15:00', '2024-06-27 01:20:00', '', NULL),
(24, '2502636', 4, '2024-06-11 06:00:00', '2024-06-11 11:59:59', 'Ejemplo de resultado 2', NULL),
(25, '2502636', 1, '2024-06-13 18:00:00', '2024-06-13 23:00:00', 'ejemplo otro otro', NULL),
(26, '2502636', 7, '2024-06-14 06:00:00', '2024-06-14 11:59:59', 'dsdasd', NULL),
(27, '2502636', 5, '2024-06-15 06:00:00', '2024-06-15 11:59:59', 'XYZ', NULL),
(28, '2502636', 7, '2024-06-12 06:00:00', '2024-06-12 11:59:59', 'XYZ', NULL),
(29, '2502636', 5, '2024-06-11 06:00:00', '2024-06-11 11:59:59', 'dsadasdas', NULL),
(30, '2502636', 6, '2024-06-22 06:00:00', '2024-06-22 11:59:59', 'XYZ', NULL),
(31, '2502636', 5, '2024-06-10 06:00:00', '2024-06-10 11:59:59', 'ejemplooooo', NULL),
(32, '2502636', 3, '2024-06-23 06:00:00', '2024-06-23 11:59:59', 'qqqqq', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas_formacion`
--

CREATE TABLE `programas_formacion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `numero_ficha` int(11) NOT NULL,
  `nivel_formacion` varchar(255) NOT NULL,
  `horario` varchar(255) DEFAULT NULL,
  `ambiente` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `programas_formacion`
--

INSERT INTO `programas_formacion` (`id`, `nombre`, `numero_ficha`, `nivel_formacion`, `horario`, `ambiente`) VALUES
(1, 'ADSO', 2502636, 'Tecnologo', 'Mañana', 'AMBIENTE 9'),
(2, 'MEDIOS GRAFICOS VISUALES ', 2502663, 'Tecnologo', 'Mañana', 'Ambiente 8');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_instructores`
--

CREATE TABLE `tipos_instructores` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `horas_maximas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_instructores`
--

INSERT INTO `tipos_instructores` (`id`, `descripcion`, `horas_maximas`) VALUES
(1, 'Contrastista', 160),
(2, 'Planta', 148);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `cedula` int(11) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `privilegio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `cedula`, `contraseña`, `privilegio`) VALUES
(1, 'administrador', 'administrador', 1080041730, 'paloma20', 1),
(3, 'instructur', 'instructor', 1014480735, 'paloma20', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `instructores`
--
ALTER TABLE `instructores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_instructor_id` (`tipo_id`);

--
-- Indices de la tabla `programaciones`
--
ALTER TABLE `programaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_id` (`instructor_id`),
  ADD KEY `fk_programas_formacion` (`programa_formacion_id`);

--
-- Indices de la tabla `programas_formacion`
--
ALTER TABLE `programas_formacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos_instructores`
--
ALTER TABLE `tipos_instructores`
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
-- AUTO_INCREMENT de la tabla `instructores`
--
ALTER TABLE `instructores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `programaciones`
--
ALTER TABLE `programaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `programas_formacion`
--
ALTER TABLE `programas_formacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipos_instructores`
--
ALTER TABLE `tipos_instructores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `instructores`
--
ALTER TABLE `instructores`
  ADD CONSTRAINT `instructores_ibfk_1` FOREIGN KEY (`tipo_id`) REFERENCES `tipos_instructores` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `programaciones`
--
ALTER TABLE `programaciones`
  ADD CONSTRAINT `fk_programas_formacion` FOREIGN KEY (`programa_formacion_id`) REFERENCES `programas_formacion` (`id`),
  ADD CONSTRAINT `programaciones_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructores` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
