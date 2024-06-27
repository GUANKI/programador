-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-06-2024 a las 22:05:05
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
(3, 'Gilberto', 'Murcia', 1);

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
(11, '2502663', 3, '2024-06-18 12:00:00', '2024-06-18 17:59:59', 'Ejemplo', NULL);

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
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `instructores`
--
ALTER TABLE `instructores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `programaciones`
--
ALTER TABLE `programaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
