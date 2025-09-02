-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-09-2025 a las 05:51:00
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
-- Base de datos: `db_sistema_horas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id_alumno` int(5) NOT NULL,
  `nombres_alumno` varchar(55) NOT NULL,
  `apellidos_alumno` varchar(55) NOT NULL,
  `carnet_alumno` varchar(12) NOT NULL,
  `DUI_alumno` varchar(10) NOT NULL,
  `telefono_alumno` varchar(9) NOT NULL,
  `correo_alumno` varchar(50) NOT NULL,
  `contactoEmergencia_alumno` varchar(50) NOT NULL,
  `telEmergencia_alumno` varchar(9) NOT NULL,
  `carrera_alumno` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciclos`
--

CREATE TABLE `ciclos` (
  `id_ciclo` int(3) NOT NULL,
  `nombre_ciclo` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciclo_actual`
--

CREATE TABLE `ciclo_actual` (
  `ciclo_actual` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encargados`
--

CREATE TABLE `encargados` (
  `id_encargado` int(4) NOT NULL,
  `nombres_encargado` varchar(50) NOT NULL,
  `apellidos_encargado` varchar(50) NOT NULL,
  `email_encargado` varchar(100) NOT NULL,
  `password_encargado` varchar(255) NOT NULL,
  `numEmpleado_encargado` varchar(15) NOT NULL,
  `DUI_encargado` varchar(10) NOT NULL,
  `id_rol_encargado` int(2) NOT NULL,
  `laboratorios_asignados` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horas`
--

CREATE TABLE `horas` (
  `id_registro_hora` int(6) NOT NULL,
  `entrada_hora` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `salida_hora` datetime DEFAULT NULL,
  `actividad_hora` varchar(100) DEFAULT NULL,
  `id_alumno_hora` varchar(12) DEFAULT NULL,
  `id_encargado_hora` int(4) DEFAULT NULL,
  `id_laboratorio_hora` int(2) DEFAULT NULL,
  `nombre_laboratorio_hora` varchar(50) NOT NULL,
  `nombre_ciclo_hora` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laboratorios`
--

CREATE TABLE `laboratorios` (
  `id_laboratorio` int(2) NOT NULL,
  `nombre_laboratorio` varchar(50) NOT NULL,
  `telefono_laboratorio` varchar(9) NOT NULL,
  `ubicacion_laboratorio` varchar(100) NOT NULL,
  `Denominacion_laboratorio` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(2) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_alumnos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_alumnos` (
`Carnet` varchar(12)
,`Nombre` varchar(111)
,`Carrera` varchar(50)
,`Telefono` varchar(9)
,`Correo` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_ciclos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_ciclos` (
`Ciclo` varchar(13)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_encargados`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_encargados` (
`nombre_completo` varchar(101)
,`laboratorios_asignados` varchar(1000)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_horas_alumnos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_horas_alumnos` (
`Id` int(6)
,`Carnet` varchar(12)
,`Nombre` varchar(111)
,`Entrada` timestamp
,`Salida` datetime
,`Actividad` varchar(100)
,`Laboratorio` varchar(50)
,`Encargado` varchar(50)
,`Horas` time
,`Ciclo` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_horas_ciclo`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_horas_ciclo` (
`Carnet` varchar(12)
,`Nombre` varchar(111)
,`Total_Horas_Original` time
,`Horas_Dobles` time
,`Horas_Normales` time
,`Total_Horas` time
,`Ciclo` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_horas_final`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_horas_final` (
`Carnet` varchar(12)
,`Nombre` varchar(111)
,`Total_Horas_Original` time
,`Horas_Dobles` time
,`Horas_Normales` time
,`Total_Horas` time
,`Total_General` time
,`Ciclo` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_horas_general`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_horas_general` (
`Carnet` varchar(12)
,`Nombre` varchar(111)
,`Total_Horas` time
,`Ciclo` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_horas_lab`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_horas_lab` (
`Carnet` varchar(12)
,`Nombre` varchar(111)
,`Ciclo` varchar(50)
,`Laboratorio` varchar(50)
,`total_horas_lab` time
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_infolab`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_infolab` (
`nombre_laboratorio` varchar(50)
,`Denominacion_laboratorio` varchar(200)
,`telefono_laboratorio` varchar(9)
,`ubicacion_laboratorio` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_laboratorios`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_laboratorios` (
`id_laboratorio` int(2)
,`nombre_laboratorio` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_alumnos`
--
DROP TABLE IF EXISTS `vista_alumnos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_alumnos`  AS SELECT `a`.`carnet_alumno` AS `Carnet`, concat(`a`.`nombres_alumno`,' ',`a`.`apellidos_alumno`) AS `Nombre`, `a`.`carrera_alumno` AS `Carrera`, `a`.`telefono_alumno` AS `Telefono`, `a`.`correo_alumno` AS `Correo` FROM `alumnos` AS `a` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_ciclos`
--
DROP TABLE IF EXISTS `vista_ciclos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_ciclos`  AS SELECT DISTINCT `ciclos`.`nombre_ciclo` AS `Ciclo` FROM `ciclos` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_encargados`
--
DROP TABLE IF EXISTS `vista_encargados`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_encargados`  AS SELECT concat(`encargados`.`nombres_encargado`,' ',`encargados`.`apellidos_encargado`) AS `nombre_completo`, `encargados`.`laboratorios_asignados` AS `laboratorios_asignados` FROM `encargados` WHERE `encargados`.`id_rol_encargado` = 2 ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_horas_alumnos`
--
DROP TABLE IF EXISTS `vista_horas_alumnos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_horas_alumnos`  AS SELECT `h`.`id_registro_hora` AS `Id`, `a`.`carnet_alumno` AS `Carnet`, concat(`a`.`nombres_alumno`,' ',`a`.`apellidos_alumno`) AS `Nombre`, `h`.`entrada_hora` AS `Entrada`, `h`.`salida_hora` AS `Salida`, `h`.`actividad_hora` AS `Actividad`, `l`.`nombre_laboratorio` AS `Laboratorio`, `e`.`apellidos_encargado` AS `Encargado`, sec_to_time(time_to_sec(timediff(`h`.`salida_hora`,`h`.`entrada_hora`)) - if(dayofweek(`h`.`entrada_hora`) between 2 and 7 and cast(`h`.`entrada_hora` as time) < '08:00:00' and cast(`h`.`salida_hora` as time) > '06:30:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 08:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 06:30:00')))),time_to_sec('01:30:00')),0) + 2 * if(dayofweek(`h`.`entrada_hora`) between 2 and 7 and cast(`h`.`entrada_hora` as time) < '08:00:00' and cast(`h`.`salida_hora` as time) > '06:30:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 08:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 06:30:00')))),time_to_sec('01:30:00')),0) + if(dayofweek(`h`.`entrada_hora`) = 7 and cast(`h`.`entrada_hora` as time) >= '13:00:00' and cast(`h`.`entrada_hora` as time) < '16:00:00' or dayofweek(`h`.`salida_hora`) = 7 and cast(`h`.`salida_hora` as time) > '13:00:00' and cast(`h`.`salida_hora` as time) <= '16:00:00' or dayofweek(`h`.`entrada_hora`) = 7 and dayofweek(`h`.`salida_hora`) = 7 and cast(`h`.`entrada_hora` as time) < '13:00:00' and cast(`h`.`salida_hora` as time) > '16:00:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 16:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 13:00:00')))),time_to_sec('03:00:00')),0) + if(dayofweek(`h`.`entrada_hora`) = 1 and cast(`h`.`entrada_hora` as time) >= '07:00:00' and cast(`h`.`entrada_hora` as time) < '13:00:00' or dayofweek(`h`.`salida_hora`) = 1 and cast(`h`.`salida_hora` as time) > '07:00:00' and cast(`h`.`salida_hora` as time) <= '13:00:00' or dayofweek(`h`.`entrada_hora`) = 1 and dayofweek(`h`.`salida_hora`) = 1 and cast(`h`.`entrada_hora` as time) < '07:00:00' and cast(`h`.`salida_hora` as time) > '13:00:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 13:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 07:00:00')))),time_to_sec('06:00:00')),0)) AS `Horas`, `h`.`nombre_ciclo_hora` AS `Ciclo` FROM (((`horas` `h` join `alumnos` `a` on(`h`.`id_alumno_hora` = `a`.`carnet_alumno`)) join `laboratorios` `l` on(`h`.`id_laboratorio_hora` = `l`.`id_laboratorio`)) join `encargados` `e` on(`h`.`id_encargado_hora` = `e`.`id_encargado`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_horas_ciclo`
--
DROP TABLE IF EXISTS `vista_horas_ciclo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_horas_ciclo`  AS SELECT `a`.`carnet_alumno` AS `Carnet`, concat(`a`.`nombres_alumno`,' ',`a`.`apellidos_alumno`) AS `Nombre`, sec_to_time(sum(time_to_sec(timediff(`h`.`salida_hora`,`h`.`entrada_hora`)))) AS `Total_Horas_Original`, sec_to_time(sum(if(dayofweek(`h`.`entrada_hora`) between 2 and 7 and cast(`h`.`entrada_hora` as time) < '08:00:00' and cast(`h`.`salida_hora` as time) > '06:30:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 08:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 06:30:00')))),time_to_sec('01:30:00')),0) + if(dayofweek(`h`.`entrada_hora`) = 7 and cast(`h`.`entrada_hora` as time) >= '13:00:00' and cast(`h`.`entrada_hora` as time) < '16:00:00' or dayofweek(`h`.`salida_hora`) = 7 and cast(`h`.`salida_hora` as time) > '13:00:00' and cast(`h`.`salida_hora` as time) <= '16:00:00' or dayofweek(`h`.`entrada_hora`) = 7 and dayofweek(`h`.`salida_hora`) = 7 and cast(`h`.`entrada_hora` as time) < '13:00:00' and cast(`h`.`salida_hora` as time) > '16:00:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 16:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 13:00:00')))),time_to_sec('03:00:00')),0) + if(dayofweek(`h`.`entrada_hora`) = 1 and cast(`h`.`entrada_hora` as time) >= '07:00:00' and cast(`h`.`entrada_hora` as time) < '13:00:00' or dayofweek(`h`.`salida_hora`) = 1 and cast(`h`.`salida_hora` as time) > '07:00:00' and cast(`h`.`salida_hora` as time) <= '13:00:00' or dayofweek(`h`.`entrada_hora`) = 1 and dayofweek(`h`.`salida_hora`) = 1 and cast(`h`.`entrada_hora` as time) < '07:00:00' and cast(`h`.`salida_hora` as time) > '13:00:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 13:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 07:00:00')))),time_to_sec('06:00:00')),0))) AS `Horas_Dobles`, sec_to_time(sum(time_to_sec(timediff(`h`.`salida_hora`,`h`.`entrada_hora`)) - if(dayofweek(`h`.`entrada_hora`) between 2 and 7 and cast(`h`.`entrada_hora` as time) < '08:00:00' and cast(`h`.`salida_hora` as time) > '06:30:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 08:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 06:30:00')))),time_to_sec('01:30:00')),0) - if(dayofweek(`h`.`entrada_hora`) = 7 and cast(`h`.`entrada_hora` as time) >= '13:00:00' and cast(`h`.`entrada_hora` as time) < '16:00:00' or dayofweek(`h`.`salida_hora`) = 7 and cast(`h`.`salida_hora` as time) > '13:00:00' and cast(`h`.`salida_hora` as time) <= '16:00:00' or dayofweek(`h`.`entrada_hora`) = 7 and dayofweek(`h`.`salida_hora`) = 7 and cast(`h`.`entrada_hora` as time) < '13:00:00' and cast(`h`.`salida_hora` as time) > '16:00:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 16:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 13:00:00')))),time_to_sec('03:00:00')),0) - if(dayofweek(`h`.`entrada_hora`) = 1 and cast(`h`.`entrada_hora` as time) >= '07:00:00' and cast(`h`.`entrada_hora` as time) < '13:00:00' or dayofweek(`h`.`salida_hora`) = 1 and cast(`h`.`salida_hora` as time) > '07:00:00' and cast(`h`.`salida_hora` as time) <= '13:00:00' or dayofweek(`h`.`entrada_hora`) = 1 and dayofweek(`h`.`salida_hora`) = 1 and cast(`h`.`entrada_hora` as time) < '07:00:00' and cast(`h`.`salida_hora` as time) > '13:00:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 13:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 07:00:00')))),time_to_sec('06:00:00')),0))) AS `Horas_Normales`, sec_to_time(sum(time_to_sec(timediff(`h`.`salida_hora`,`h`.`entrada_hora`)) - if(dayofweek(`h`.`entrada_hora`) between 2 and 7 and cast(`h`.`entrada_hora` as time) < '08:00:00' and cast(`h`.`salida_hora` as time) > '06:30:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 08:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 06:30:00')))),time_to_sec('01:30:00')),0) + 2 * if(dayofweek(`h`.`entrada_hora`) between 2 and 7 and cast(`h`.`entrada_hora` as time) < '08:00:00' and cast(`h`.`salida_hora` as time) > '06:30:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 08:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 06:30:00')))),time_to_sec('01:30:00')),0) + if(dayofweek(`h`.`entrada_hora`) = 7 and cast(`h`.`entrada_hora` as time) >= '13:00:00' and cast(`h`.`entrada_hora` as time) < '16:00:00' or dayofweek(`h`.`salida_hora`) = 7 and cast(`h`.`salida_hora` as time) > '13:00:00' and cast(`h`.`salida_hora` as time) <= '16:00:00' or dayofweek(`h`.`entrada_hora`) = 7 and dayofweek(`h`.`salida_hora`) = 7 and cast(`h`.`entrada_hora` as time) < '13:00:00' and cast(`h`.`salida_hora` as time) > '16:00:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 16:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 13:00:00')))),time_to_sec('03:00:00')),0) + if(dayofweek(`h`.`entrada_hora`) = 1 and cast(`h`.`entrada_hora` as time) >= '07:00:00' and cast(`h`.`entrada_hora` as time) < '13:00:00' or dayofweek(`h`.`salida_hora`) = 1 and cast(`h`.`salida_hora` as time) > '07:00:00' and cast(`h`.`salida_hora` as time) <= '13:00:00' or dayofweek(`h`.`entrada_hora`) = 1 and dayofweek(`h`.`salida_hora`) = 1 and cast(`h`.`entrada_hora` as time) < '07:00:00' and cast(`h`.`salida_hora` as time) > '13:00:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 13:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 07:00:00')))),time_to_sec('06:00:00')),0))) AS `Total_Horas`, `h`.`nombre_ciclo_hora` AS `Ciclo` FROM (`alumnos` `a` join `horas` `h` on(`a`.`carnet_alumno` = `h`.`id_alumno_hora`)) GROUP BY `a`.`carnet_alumno`, `h`.`nombre_ciclo_hora` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_horas_final`
--
DROP TABLE IF EXISTS `vista_horas_final`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_horas_final`  AS SELECT `subquery`.`Carnet` AS `Carnet`, `subquery`.`Nombre` AS `Nombre`, `subquery`.`Total_Horas_Original` AS `Total_Horas_Original`, `subquery`.`Horas_Dobles` AS `Horas_Dobles`, `subquery`.`Horas_Normales` AS `Horas_Normales`, `subquery`.`Total_Horas` AS `Total_Horas`, sec_to_time(sum(time_to_sec(`subquery`.`Total_Horas`)) over ( partition by `subquery`.`Carnet`)) AS `Total_General`, `subquery`.`Ciclo` AS `Ciclo` FROM (select `a`.`carnet_alumno` AS `Carnet`,concat(`a`.`nombres_alumno`,' ',`a`.`apellidos_alumno`) AS `Nombre`,`h`.`nombre_ciclo_hora` AS `Ciclo`,sec_to_time(sum(time_to_sec(timediff(`h`.`salida_hora`,`h`.`entrada_hora`)))) AS `Total_Horas_Original`,sec_to_time(sum(if(dayofweek(`h`.`entrada_hora`) between 2 and 7 and cast(`h`.`entrada_hora` as time) < '08:00:00' and cast(`h`.`salida_hora` as time) > '06:30:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 08:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 06:30:00')))),time_to_sec('01:30:00')),0) + if(dayofweek(`h`.`entrada_hora`) = 7 and cast(`h`.`entrada_hora` as time) >= '13:00:00' and cast(`h`.`entrada_hora` as time) < '16:00:00' or dayofweek(`h`.`salida_hora`) = 7 and cast(`h`.`salida_hora` as time) > '13:00:00' and cast(`h`.`salida_hora` as time) <= '16:00:00' or dayofweek(`h`.`entrada_hora`) = 7 and dayofweek(`h`.`salida_hora`) = 7 and cast(`h`.`entrada_hora` as time) < '13:00:00' and cast(`h`.`salida_hora` as time) > '16:00:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 16:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 13:00:00')))),time_to_sec('03:00:00')),0) + if(dayofweek(`h`.`entrada_hora`) = 1 and cast(`h`.`entrada_hora` as time) >= '07:00:00' and cast(`h`.`entrada_hora` as time) < '13:00:00' or dayofweek(`h`.`salida_hora`) = 1 and cast(`h`.`salida_hora` as time) > '07:00:00' and cast(`h`.`salida_hora` as time) <= '13:00:00' or dayofweek(`h`.`entrada_hora`) = 1 and dayofweek(`h`.`salida_hora`) = 1 and cast(`h`.`entrada_hora` as time) < '07:00:00' and cast(`h`.`salida_hora` as time) > '13:00:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 13:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 07:00:00')))),time_to_sec('06:00:00')),0))) AS `Horas_Dobles`,sec_to_time(sum(time_to_sec(timediff(`h`.`salida_hora`,`h`.`entrada_hora`)) - if(dayofweek(`h`.`entrada_hora`) between 2 and 7 and cast(`h`.`entrada_hora` as time) < '08:00:00' and cast(`h`.`salida_hora` as time) > '06:30:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 08:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 06:30:00')))),time_to_sec('01:30:00')),0) - if(dayofweek(`h`.`entrada_hora`) = 7 and cast(`h`.`entrada_hora` as time) >= '13:00:00' and cast(`h`.`entrada_hora` as time) < '16:00:00' or dayofweek(`h`.`salida_hora`) = 7 and cast(`h`.`salida_hora` as time) > '13:00:00' and cast(`h`.`salida_hora` as time) <= '16:00:00' or dayofweek(`h`.`entrada_hora`) = 7 and dayofweek(`h`.`salida_hora`) = 7 and cast(`h`.`entrada_hora` as time) < '13:00:00' and cast(`h`.`salida_hora` as time) > '16:00:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 16:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 13:00:00')))),time_to_sec('03:00:00')),0) - if(dayofweek(`h`.`entrada_hora`) = 1 and cast(`h`.`entrada_hora` as time) >= '07:00:00' and cast(`h`.`entrada_hora` as time) < '13:00:00' or dayofweek(`h`.`salida_hora`) = 1 and cast(`h`.`salida_hora` as time) > '07:00:00' and cast(`h`.`salida_hora` as time) <= '13:00:00' or dayofweek(`h`.`entrada_hora`) = 1 and dayofweek(`h`.`salida_hora`) = 1 and cast(`h`.`entrada_hora` as time) < '07:00:00' and cast(`h`.`salida_hora` as time) > '13:00:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 13:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 07:00:00')))),time_to_sec('06:00:00')),0))) AS `Horas_Normales`,sec_to_time(sum(time_to_sec(timediff(`h`.`salida_hora`,`h`.`entrada_hora`)) - if(dayofweek(`h`.`entrada_hora`) between 2 and 7 and cast(`h`.`entrada_hora` as time) < '08:00:00' and cast(`h`.`salida_hora` as time) > '06:30:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 08:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 06:30:00')))),time_to_sec('01:30:00')),0) + 2 * if(dayofweek(`h`.`entrada_hora`) between 2 and 7 and cast(`h`.`entrada_hora` as time) < '08:00:00' and cast(`h`.`salida_hora` as time) > '06:30:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 08:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 06:30:00')))),time_to_sec('01:30:00')),0) + if(dayofweek(`h`.`entrada_hora`) = 7 and cast(`h`.`entrada_hora` as time) >= '13:00:00' and cast(`h`.`entrada_hora` as time) < '16:00:00' or dayofweek(`h`.`salida_hora`) = 7 and cast(`h`.`salida_hora` as time) > '13:00:00' and cast(`h`.`salida_hora` as time) <= '16:00:00' or dayofweek(`h`.`entrada_hora`) = 7 and dayofweek(`h`.`salida_hora`) = 7 and cast(`h`.`entrada_hora` as time) < '13:00:00' and cast(`h`.`salida_hora` as time) > '16:00:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 16:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 13:00:00')))),time_to_sec('03:00:00')),0) + if(dayofweek(`h`.`entrada_hora`) = 1 and cast(`h`.`entrada_hora` as time) >= '07:00:00' and cast(`h`.`entrada_hora` as time) < '13:00:00' or dayofweek(`h`.`salida_hora`) = 1 and cast(`h`.`salida_hora` as time) > '07:00:00' and cast(`h`.`salida_hora` as time) <= '13:00:00' or dayofweek(`h`.`entrada_hora`) = 1 and dayofweek(`h`.`salida_hora`) = 1 and cast(`h`.`entrada_hora` as time) < '07:00:00' and cast(`h`.`salida_hora` as time) > '13:00:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 13:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 07:00:00')))),time_to_sec('06:00:00')),0))) AS `Total_Horas` from (`alumnos` `a` join `horas` `h` on(`a`.`carnet_alumno` = `h`.`id_alumno_hora`)) group by `a`.`carnet_alumno`,`h`.`nombre_ciclo_hora`) AS `subquery` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_horas_general`
--
DROP TABLE IF EXISTS `vista_horas_general`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_horas_general`  AS SELECT `a`.`carnet_alumno` AS `Carnet`, concat(`a`.`nombres_alumno`,' ',`a`.`apellidos_alumno`) AS `Nombre`, `h_total`.`Total_Horas` AS `Total_Horas`, `h`.`nombre_ciclo_hora` AS `Ciclo` FROM ((`alumnos` `a` join `horas` `h` on(`a`.`carnet_alumno` = `h`.`id_alumno_hora`)) join (select `h`.`id_alumno_hora` AS `id_alumno`,sec_to_time(sum(time_to_sec(timediff(`h`.`salida_hora`,`h`.`entrada_hora`)))) AS `Total_Horas` from `horas` `h` group by `h`.`id_alumno_hora`) `h_total` on(`a`.`carnet_alumno` = `h_total`.`id_alumno`)) GROUP BY `a`.`carnet_alumno`, `h`.`nombre_ciclo_hora` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_horas_lab`
--
DROP TABLE IF EXISTS `vista_horas_lab`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_horas_lab`  AS SELECT `a`.`carnet_alumno` AS `Carnet`, concat(`a`.`nombres_alumno`,' ',`a`.`apellidos_alumno`) AS `Nombre`, `h`.`nombre_ciclo_hora` AS `Ciclo`, `l`.`nombre_laboratorio` AS `Laboratorio`, sec_to_time(sum(time_to_sec(timediff(`h`.`salida_hora`,`h`.`entrada_hora`)) - if(dayofweek(`h`.`entrada_hora`) between 2 and 7 and cast(`h`.`entrada_hora` as time) < '08:00:00' and cast(`h`.`salida_hora` as time) > '06:30:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 08:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 06:30:00')))),time_to_sec('01:30:00')),0) + 2 * if(dayofweek(`h`.`entrada_hora`) between 2 and 7 and cast(`h`.`entrada_hora` as time) < '08:00:00' and cast(`h`.`salida_hora` as time) > '06:30:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 08:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 06:30:00')))),time_to_sec('01:30:00')),0) + if(dayofweek(`h`.`entrada_hora`) = 7 and cast(`h`.`entrada_hora` as time) >= '13:00:00' and cast(`h`.`entrada_hora` as time) < '16:00:00' or dayofweek(`h`.`salida_hora`) = 7 and cast(`h`.`salida_hora` as time) > '13:00:00' and cast(`h`.`salida_hora` as time) <= '16:00:00' or dayofweek(`h`.`entrada_hora`) = 7 and dayofweek(`h`.`salida_hora`) = 7 and cast(`h`.`entrada_hora` as time) < '13:00:00' and cast(`h`.`salida_hora` as time) > '16:00:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 16:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 13:00:00')))),time_to_sec('03:00:00')),0) + if(dayofweek(`h`.`entrada_hora`) = 1 and cast(`h`.`entrada_hora` as time) >= '07:00:00' and cast(`h`.`entrada_hora` as time) < '13:00:00' or dayofweek(`h`.`salida_hora`) = 1 and cast(`h`.`salida_hora` as time) > '07:00:00' and cast(`h`.`salida_hora` as time) <= '13:00:00' or dayofweek(`h`.`entrada_hora`) = 1 and dayofweek(`h`.`salida_hora`) = 1 and cast(`h`.`entrada_hora` as time) < '07:00:00' and cast(`h`.`salida_hora` as time) > '13:00:00',least(time_to_sec(timediff(least(`h`.`salida_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 13:00:00')),greatest(`h`.`entrada_hora`,date_format(`h`.`entrada_hora`,'%Y-%m-%d 07:00:00')))),time_to_sec('06:00:00')),0))) AS `total_horas_lab` FROM ((`horas` `h` join `alumnos` `a` on(`h`.`id_alumno_hora` = `a`.`carnet_alumno`)) join `laboratorios` `l` on(`h`.`id_laboratorio_hora` = `l`.`id_laboratorio`)) GROUP BY `a`.`carnet_alumno`, `h`.`nombre_ciclo_hora`, `l`.`nombre_laboratorio` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_infolab`
--
DROP TABLE IF EXISTS `vista_infolab`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_infolab`  AS SELECT `laboratorios`.`nombre_laboratorio` AS `nombre_laboratorio`, `laboratorios`.`Denominacion_laboratorio` AS `Denominacion_laboratorio`, `laboratorios`.`telefono_laboratorio` AS `telefono_laboratorio`, `laboratorios`.`ubicacion_laboratorio` AS `ubicacion_laboratorio` FROM `laboratorios` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_laboratorios`
--
DROP TABLE IF EXISTS `vista_laboratorios`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_laboratorios`  AS SELECT `laboratorios`.`id_laboratorio` AS `id_laboratorio`, `laboratorios`.`nombre_laboratorio` AS `nombre_laboratorio` FROM `laboratorios` ORDER BY `laboratorios`.`id_laboratorio` ASC ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id_alumno`),
  ADD UNIQUE KEY `carnet_alumno` (`carnet_alumno`,`DUI_alumno`,`correo_alumno`);

--
-- Indices de la tabla `ciclos`
--
ALTER TABLE `ciclos`
  ADD PRIMARY KEY (`id_ciclo`),
  ADD UNIQUE KEY `nombre_ciclo_2` (`nombre_ciclo`),
  ADD KEY `nombre_ciclo` (`nombre_ciclo`);

--
-- Indices de la tabla `encargados`
--
ALTER TABLE `encargados`
  ADD PRIMARY KEY (`id_encargado`),
  ADD UNIQUE KEY `numEmpleado_encargado` (`numEmpleado_encargado`,`DUI_encargado`),
  ADD KEY `Id_Rol` (`id_rol_encargado`);

--
-- Indices de la tabla `horas`
--
ALTER TABLE `horas`
  ADD PRIMARY KEY (`id_registro_hora`),
  ADD KEY `Id_Alumno` (`id_alumno_hora`),
  ADD KEY `Id_Encargado` (`id_encargado_hora`),
  ADD KEY `ciclo_horas` (`nombre_ciclo_hora`),
  ADD KEY `id_laboratorio_hora` (`id_laboratorio_hora`);

--
-- Indices de la tabla `laboratorios`
--
ALTER TABLE `laboratorios`
  ADD PRIMARY KEY (`id_laboratorio`),
  ADD KEY `nombre_laboratorio` (`nombre_laboratorio`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id_alumno` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ciclos`
--
ALTER TABLE `ciclos`
  MODIFY `id_ciclo` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `encargados`
--
ALTER TABLE `encargados`
  MODIFY `id_encargado` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horas`
--
ALTER TABLE `horas`
  MODIFY `id_registro_hora` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `laboratorios`
--
ALTER TABLE `laboratorios`
  MODIFY `id_laboratorio` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(2) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `encargados`
--
ALTER TABLE `encargados`
  ADD CONSTRAINT `encargados_ibfk_2` FOREIGN KEY (`id_rol_encargado`) REFERENCES `roles` (`id_rol`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `horas`
--
ALTER TABLE `horas`
  ADD CONSTRAINT `horas_ibfk_2` FOREIGN KEY (`id_encargado_hora`) REFERENCES `encargados` (`id_encargado`) ON UPDATE CASCADE,
  ADD CONSTRAINT `horas_ibfk_5` FOREIGN KEY (`id_alumno_hora`) REFERENCES `alumnos` (`carnet_alumno`) ON UPDATE CASCADE,
  ADD CONSTRAINT `horas_ibfk_6` FOREIGN KEY (`id_laboratorio_hora`) REFERENCES `laboratorios` (`id_laboratorio`) ON UPDATE CASCADE,
  ADD CONSTRAINT `horas_ibfk_7` FOREIGN KEY (`nombre_ciclo_hora`) REFERENCES `ciclos` (`nombre_ciclo`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
