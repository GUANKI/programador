-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-05-2024 a las 22:54:48
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
-- Base de datos: `programador_bd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `competencias`
--

CREATE TABLE `competencias` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instructores`
--

CREATE TABLE `instructores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `tipo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `instructores`
--

INSERT INTO `instructores` (`id`, `nombre`, `apellido`, `tipo_id`) VALUES
(1, 'Julian Ricardo', 'Gasca Cuellar', 2),
(2, 'Maria Eugenia', 'Florez Rocha', 1),
(3, 'Juan Carlos', 'Lozada Ramirez', 2),
(4, 'Marlio Fabian', 'Ospitia Thola', 1),
(5, 'Juan Carlos', 'Lozada Ramirez', 2),
(6, 'Marlio Fabian', 'Ospitia Thola', 1),
(7, 'Eduardo Cesar', 'Timaná Salamanca', 2),
(8, 'Jehison José', 'Ramos Fernandez', 2),
(9, 'Smylle Leonardo', 'Alvarado Zamora', 1),
(10, 'Julio ', 'Florez de la Hoz', 1),
(11, 'Mairalejanra', 'Ramirez Cuenca', 1),
(12, 'Yaddy Alejandra', 'Plazas Quibano', 1),
(13, 'Adriana Marcela ', 'Alarcon Rojas', 2),
(14, 'Arney ', 'Cardoso Perez', 2),
(15, 'Francisco ', 'Chavarro Ramirez', 1),
(16, 'Ricardo Javier', 'Vega Abreo', 2),
(17, 'Dennys Adriana ', 'Quintero Sandoval', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas_formacion`
--

CREATE TABLE `programas_formacion` (
  `id` int(11) NOT NULL,
  `nivel_formacion` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `ambiente` varchar(255) NOT NULL,
  `numero_ficha` varchar(255) NOT NULL,
  `horario` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `programas_formacion`
--

INSERT INTO `programas_formacion` (`id`, `nivel_formacion`, `nombre`, `ambiente`, `numero_ficha`, `horario`) VALUES
(1, 'Tecnologo', 'ANALISIS Y DESARROLLO DE SOFTWARE', 'AMBIENTE 9', '2502636', 'Mañana'),
(2, 'Complementario', 'EMPRENDIMIENTO INOVADOR', 'Vereda la Mesa', '2987652', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resultados_aprendizaje`
--

CREATE TABLE `resultados_aprendizaje` (
  `id` int(11) NOT NULL,
  `competencia_id` int(11) DEFAULT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_instructores`
--

CREATE TABLE `tipos_instructores` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `horas_maximas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tipos_instructores`
--

INSERT INTO `tipos_instructores` (`id`, `descripcion`, `horas_maximas`) VALUES
(1, 'Funcionario', 148),
(2, 'Contratista ', 160);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `competencias`
--
ALTER TABLE `competencias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_id` (`instructor_id`),
  ADD KEY `competencia_id` (`competencia_id`),
  ADD KEY `resultado_id` (`resultado_id`),
  ADD KEY `programa_id` (`programa_id`);

--
-- Indices de la tabla `horas_acumuladas`
--
ALTER TABLE `horas_acumuladas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indices de la tabla `instructores`
--
ALTER TABLE `instructores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_id` (`tipo_id`);

--
-- Indices de la tabla `programas_formacion`
--
ALTER TABLE `programas_formacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `resultados_aprendizaje`
--
ALTER TABLE `resultados_aprendizaje`
  ADD PRIMARY KEY (`id`),
  ADD KEY `competencia_id` (`competencia_id`);

--
-- Indices de la tabla `tipos_instructores`
--
ALTER TABLE `tipos_instructores`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `competencias`
--
ALTER TABLE `competencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horas_acumuladas`
--
ALTER TABLE `horas_acumuladas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `instructores`
--
ALTER TABLE `instructores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `programas_formacion`
--
ALTER TABLE `programas_formacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `resultados_aprendizaje`
--
ALTER TABLE `resultados_aprendizaje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_instructores`
--
ALTER TABLE `tipos_instructores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructores` (`id`),
  ADD CONSTRAINT `horarios_ibfk_2` FOREIGN KEY (`competencia_id`) REFERENCES `competencias` (`id`),
  ADD CONSTRAINT `horarios_ibfk_3` FOREIGN KEY (`resultado_id`) REFERENCES `resultados_aprendizaje` (`id`),
  ADD CONSTRAINT `horarios_ibfk_4` FOREIGN KEY (`programa_id`) REFERENCES `programas_formacion` (`id`);

--
-- Filtros para la tabla `horas_acumuladas`
--
ALTER TABLE `horas_acumuladas`
  ADD CONSTRAINT `horas_acumuladas_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `instructores` (`id`);

--
-- Filtros para la tabla `instructores`
--
ALTER TABLE `instructores`
  ADD CONSTRAINT `instructores_ibfk_1` FOREIGN KEY (`tipo_id`) REFERENCES `tipos_instructores` (`id`);

--
-- Filtros para la tabla `resultados_aprendizaje`
--
ALTER TABLE `resultados_aprendizaje`
  ADD CONSTRAINT `resultados_aprendizaje_ibfk_1` FOREIGN KEY (`competencia_id`) REFERENCES `competencias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
