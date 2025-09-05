-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-09-2025 a las 04:29:49
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

DELIMITER $$
--
-- Procedimientos
--
CREATE PROCEDURE `agregar_encargado` (IN `p_nombres` VARCHAR(50), IN `p_apellidos` VARCHAR(50), IN `p_email` VARCHAR(100), IN `p_contraseña` VARCHAR(255), IN `p_numero_empleado` VARCHAR(15), IN `p_dui` VARCHAR(10), IN `p_tipo_usuario` INT, IN `p_laboratorios_asignados` VARCHAR(1000))   BEGIN

    -- Insertar los datos en la base de datos

    INSERT INTO encargados (nombres_encargado, apellidos_encargado, email_encargado, password_encargado, numEmpleado_encargado, DUI_encargado, id_rol_encargado, laboratorios_asignados) 

    VALUES (p_nombres, p_apellidos, p_email, p_contraseña, p_numero_empleado, p_dui, p_tipo_usuario, p_laboratorios_asignados);

END$$

CREATE PROCEDURE `agregar_laboratorio` (IN `nombre_lab` VARCHAR(255), IN `telefono_lab` VARCHAR(255), IN `ubicacion_lab` VARCHAR(255), IN `Denominacion_laboratorio` VARCHAR(255))   BEGIN
    INSERT INTO laboratorios (nombre_laboratorio, telefono_laboratorio, ubicacion_laboratorio, Denominacion_laboratorio)
    VALUES (nombre_lab, telefono_lab, ubicacion_lab, Denominacion_laboratorio);
END$$

CREATE PROCEDURE `alumnos_marcacion` (IN `p_carnet` VARCHAR(12))   BEGIN
    SELECT 
        `a`.`id_alumno` AS `Id`,
        `a`.`carnet_alumno` AS `Carnet`,
        CONCAT(`a`.`nombres_alumno`, ' ', `a`.`apellidos_alumno`) AS `Nombre`,
        `a`.`carrera_alumno` AS `Carrera`, 
        `a`.`telefono_alumno` AS `Telefono`,
        `a`.`correo_alumno` AS `Correo`,
        TIMEDIFF(`h`.`salida_hora`, `h`.`entrada_hora`) AS `Tiempo`,
        `c`.`ciclo_actual` AS `Ciclo`
    FROM 
        `alumnos` `a`
    JOIN (
        SELECT 
            `id_alumno_hora`,
            MAX(`salida_hora`) AS `ultima_salida`
        FROM 
            `horas`
        GROUP BY 
            `id_alumno_hora`
    ) `max_salida` ON `a`.`carnet_alumno` = `max_salida`.`id_alumno_hora`
    JOIN `horas` `h` ON `h`.`id_alumno_hora` = `max_salida`.`id_alumno_hora` AND `h`.`salida_hora` = `max_salida`.`ultima_salida`
    CROSS JOIN `ciclo_actual` `c`
    WHERE 
        `a`.`carnet_alumno` = p_carnet
    ORDER BY 
        `h`.`salida_hora` DESC
    LIMIT 1;
END$$

CREATE PROCEDURE `CambiarContraseñaEncargado` (IN `p_id_encargado` INT, IN `p_contraseña_actual` VARCHAR(255), IN `p_nueva_contraseña` VARCHAR(255), OUT `resultado` INT)   BEGIN
    DECLARE v_password_actual VARCHAR(15);
    
    -- Verificar si la contraseña actual proporcionada coincide con la almacenada en la base de datos
    SELECT password_encargado INTO v_password_actual 
    FROM encargados 
    WHERE id_encargado = p_id_encargado;
    
    IF v_password_actual = p_contraseña_actual THEN
        -- Actualizar la contraseña del encargado
        UPDATE encargados 
        SET password_encargado = p_nueva_contraseña 
        WHERE id_encargado = p_id_encargado;
        SET resultado = 1; -- Éxito
    ELSE
        SET resultado = 0; -- Contraseña actual no coincide
    END IF;
END$$

CREATE PROCEDURE `InsertarAlumno` (IN `nombres` VARCHAR(100), IN `apellidos` VARCHAR(100), IN `carnet` VARCHAR(20), IN `dui` VARCHAR(20), IN `telefono` VARCHAR(20), IN `email` VARCHAR(100), IN `contacto_emergencia` VARCHAR(100), IN `telefono_emergencia` VARCHAR(20), IN `carrera` VARCHAR(100))   BEGIN
    INSERT INTO alumnos (nombres_alumno, apellidos_alumno, carnet_alumno, DUI_alumno, telefono_alumno, correo_alumno, contactoEmergencia_alumno, telEmergencia_alumno, carrera_alumno) 
    VALUES (nombres, apellidos, carnet, dui, telefono, email, contacto_emergencia, telefono_emergencia, carrera);
END$$

CREATE PROCEDURE `InsertarCiclo` (IN `nombre_ciclo` VARCHAR(255))   BEGIN
    INSERT INTO ciclos (nombre_ciclo) VALUES (nombre_ciclo);
END$$

CREATE PROCEDURE `InsertarCicloRecibido` (IN `p_ciclo_actual` VARCHAR(255))   BEGIN
    DECLARE v_count INT;
 
    -- Verificar si ya existe un registro en la tabla
    SELECT COUNT(*) INTO v_count FROM ciclo_actual;
 
    IF v_count > 0 THEN
        -- Si existe, actualizar el registro existente
        UPDATE ciclo_actual SET ciclo_actual = p_ciclo_actual LIMIT 1;
    ELSE
        -- Si no existe, insertar un nuevo registro
        INSERT INTO ciclo_actual (ciclo_actual) VALUES (p_ciclo_actual);
    END IF;
END$$

CREATE PROCEDURE `Insertar_Horas` (IN `entrada_hora_val` DATETIME, IN `salida_hora_val` DATETIME, IN `actividad_val` VARCHAR(100), IN `id_alumno_hora_val` VARCHAR(50), IN `id_encargado_val` VARCHAR(50), IN `id_laboratorio_val` VARCHAR(50), IN `nombre_laboratorio_val` VARCHAR(50), IN `nombre_ciclo_val` VARCHAR(50))   BEGIN
    INSERT INTO horas (
        
        entrada_hora,
        salida_hora,
        actividad_hora,
		id_alumno_hora,
        id_encargado_hora,
        id_laboratorio_hora,
        nombre_laboratorio_hora,
        nombre_ciclo_hora
    ) VALUES (
        entrada_hora_val,
        salida_hora_val,
        actividad_val,
        id_alumno_hora_val,
        id_encargado_val,
        id_laboratorio_val,
        nombre_laboratorio_val,
        nombre_ciclo_val
    );
END$$

CREATE PROCEDURE `modificar_alumno` (IN `p_id_alumno` INT, IN `p_nombres_alumno` VARCHAR(50), IN `p_apellidos_alumno` VARCHAR(50), IN `p_carnet_alumno` VARCHAR(12), IN `p_dui_alumno` VARCHAR(10), IN `p_telefono_alumno` VARCHAR(9), IN `p_correo_alumno` VARCHAR(50), IN `p_contactoEmergencia_alumno` VARCHAR(50), IN `p_telEmergencia_alumno` VARCHAR(9), IN `p_carrera_alumno` VARCHAR(50))   BEGIN     UPDATE alumnos     SET nombres_alumno = p_nombres_alumno,         apellidos_alumno = p_apellidos_alumno, 		carnet_alumno = p_carnet_alumno,         DUI_alumno = p_dui_alumno,         telefono_alumno = p_telefono_alumno,         correo_alumno = p_correo_alumno,         contactoEmergencia_alumno = p_contactoEmergencia_alumno,         telEmergencia_alumno = p_telEmergencia_alumno, 		carrera_alumno = p_carrera_alumno     WHERE 		id_alumno = p_id_alumno; END$$

CREATE PROCEDURE `modificar_encargado` (IN `p_id_encargado` INT, IN `p_nombres_encargado` VARCHAR(50), IN `p_apellidos_encargado` VARCHAR(50), IN `p_email_encargado` VARCHAR(100), IN `p_contraseña_encargado` VARCHAR(255), IN `p_numero_empleado` VARCHAR(15), IN `p_dui_encargado` VARCHAR(10), IN `p_tipo_usuario` INT, IN `p_laboratorios_asignados` VARCHAR(1000))   BEGIN
    UPDATE encargados
    SET 
        nombres_encargado = p_nombres_encargado,
        apellidos_encargado = p_apellidos_encargado,
        email_encargado = p_email_encargado,
        password_encargado = p_contraseña_encargado,
        numEmpleado_encargado = p_numero_empleado,
        DUI_encargado = p_dui_encargado,
        id_rol_encargado = p_tipo_usuario,
        laboratorios_asignados = p_laboratorios_asignados
    WHERE
        id_encargado = p_id_encargado;
END$$

CREATE PROCEDURE `modificar_laboratorio` (IN `p_id_laboratorio` INT, IN `p_nombre_laboratorio` VARCHAR(50), IN `p_telefono_laboratorio` VARCHAR(9), IN `p_ubicacion_laboratorio` VARCHAR(100), IN `p_denominacion_laboratorio` VARCHAR(200))   BEGIN
    UPDATE laboratorios
    SET 
        nombre_laboratorio = p_nombre_laboratorio,
        telefono_laboratorio = p_telefono_laboratorio,
        ubicacion_laboratorio = p_ubicacion_laboratorio,
        Denominacion_laboratorio = p_denominacion_laboratorio
    WHERE id_laboratorio = p_id_laboratorio;
END$$

CREATE PROCEDURE `ObtenerNombresCiclos` ()   BEGIN
    SELECT DISTINCT `nombre_ciclo` FROM `ciclos`;
END$$

CREATE PROCEDURE `ObtenerRegistrosLaboratorios` (IN `encargado_id` INT)   BEGIN
    DECLARE labs VARCHAR(255);

    -- Obtener los laboratorios asignados al encargado
    SELECT laboratorios_asignados INTO labs
    FROM encargados
    WHERE id_encargado = encargado_id;

    -- Devolver los registros de esos laboratorios donde salida_hora es NULL
    -- o los registros creados por el encargado
    SELECT *
    FROM horas
    WHERE (FIND_IN_SET(nombre_laboratorio_hora, labs) OR id_encargado_hora = encargado_id)
    AND salida_hora IS NULL;
END$$

CREATE PROCEDURE `registros_abiertos` (IN `id_encargado` INT)   BEGIN
    SELECT *
    FROM horas
    WHERE (salida_hora IS NULL OR salida_hora = '')
      AND id_encargado = id_encargado_hora; -- Filtrar por el id_encargado proporcionado
END$$

CREATE PROCEDURE `registros_abiertos2` ()   BEGIN
    SELECT *
    FROM horas
    WHERE (salida_hora IS NULL OR salida_hora = '');
END$$

CREATE PROCEDURE `registros_horas` (IN `p_busqueda` VARCHAR(255), IN `p_ciclo` VARCHAR(255))   BEGIN
    SELECT * FROM horas
    WHERE id_alumno_hora = p_busqueda AND nombre_ciclo_hora = p_ciclo;
END$$

CREATE PROCEDURE `Update_Horas` (IN `p_id_registro` INT, IN `p_carnet` VARCHAR(12), IN `p_hora_entrada` TIMESTAMP, IN `p_actividad` VARCHAR(100), IN `p_laboratorio` INT, IN `p_hora_salida` DATETIME, OUT `p_result` INT)   BEGIN
    DECLARE rows_affected INT;

    UPDATE horas
    SET 
        id_alumno_hora = p_carnet,
        entrada_hora = p_hora_entrada,
        actividad_hora = p_actividad,
        id_laboratorio_hora = p_laboratorio,
        salida_hora = p_hora_salida
    WHERE 
        id_registro_hora = p_id_registro
        AND salida_hora IS NULL;

    SET rows_affected = ROW_COUNT();
    
    IF rows_affected > 0 THEN
        SET p_result = 1;
    ELSE
        SET p_result = 0;
    END IF;
END$$

CREATE PROCEDURE `Update_Horas2` (IN `p_id_registro` INT, IN `p_carnet` VARCHAR(12), IN `p_hora_entrada` TIMESTAMP, IN `p_actividad` VARCHAR(100), IN `p_laboratorio` INT, IN `p_hora_salida` DATETIME)   BEGIN
    UPDATE horas
    SET 
        id_alumno_hora = p_carnet,
        entrada_hora = p_hora_entrada,
        actividad_hora = p_actividad,
        id_laboratorio_hora = p_laboratorio,
        salida_hora = p_hora_salida
    WHERE id_registro_hora = p_id_registro;
END$$

DELIMITER ;

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
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id_alumno`, `nombres_alumno`, `apellidos_alumno`, `carnet_alumno`, `DUI_alumno`, `telefono_alumno`, `correo_alumno`, `contactoEmergencia_alumno`, `telEmergencia_alumno`, `carrera_alumno`) VALUES
(1, 'Mateo Daniel', 'Castillo Cruz', '27-5711-2016', '65845203-8', '7057-6521', '2757112016@mail.utec.edu.sv', 'Ester', '7025-5841', 'Tecnico en Ingenieria de Software'),
(2, 'Maria Luisa', 'Villegas Portillo', '27-5711-2017', '06172011-2', '6824-9685', '2757112017@mail.utec.edu.sv', 'Maria Jose', '7485-8574', 'Técnico en Ingeniería de Redes Computacionales'),
(3, 'Manuel Alejandro', 'Araujo Aparicio', '27-5711-2018', '05182011-5', '7311-1802', '2757112018@mailutec.edu.sv', 'Hector', '7345-8574', 'Licenciatura en Informática'),
(4, 'Joseph Humberto', 'Travez Villalba', '27-5711-2019', '06192757-8', '7425-5241', '2757112019@mail.utec.edu.sv', 'Edwin', '7214-5214', 'Ingeniería en Sistemas y Computación'),
(5, 'Estiben Alexis', 'Calderon Campos', '27-5711-2020', '06202084-8', '7125-1436', '2757112020@mail.utec.edu.sv', 'Lizbeth', '7874-8596', 'Técnico en Ingeniería de Software'),
(6, 'Samuel Martinez', 'Jimenez Maravilla', '27-3210-2022', '06295951-1', '7027-1458', '2732102022@mail.utec.edu.sv', 'Maria Jose', '7185-5874', 'Tecnico en ingenieria de software');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciclos`
--

CREATE TABLE `ciclos` (
  `id_ciclo` int(3) NOT NULL,
  `nombre_ciclo` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `ciclos`
--

INSERT INTO `ciclos` (`id_ciclo`, `nombre_ciclo`) VALUES
(1, 'Ciclo 01-2024'),
(3, 'Ciclo 01-2025'),
(2, 'Ciclo 02-2023'),
(4, 'Ciclo 02-2025');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciclo_actual`
--

CREATE TABLE `ciclo_actual` (
  `ciclo_actual` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `ciclo_actual`
--

INSERT INTO `ciclo_actual` (`ciclo_actual`) VALUES
('Ciclo 02-2025');

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

--
-- Volcado de datos para la tabla `encargados`
--

INSERT INTO `encargados` (`id_encargado`, `nombres_encargado`, `apellidos_encargado`, `email_encargado`, `password_encargado`, `numEmpleado_encargado`, `DUI_encargado`, `id_rol_encargado`, `laboratorios_asignados`) VALUES
(1, 'Juan', 'Perez', 'j.perez@demo.com', '$2y$10$3dBijIM.DupUysj4N5oLHu36b5RX2tJ3CsVHClR/OI1rx4BkWB.Ey', '00001', '1235678-9', 1, ''),
(2, 'Pedro', 'Sanchez', 'p.sanchez@demo.com', '$2y$10$2ifNKrxNhHtRlIB8SiK9/ePmjz3dgcCFF78s2bAiyAnSP3VZQHCI2', '00002', '98765432-1', 2, 'Laboratorio 1,Laboratorio 2'),
(3, 'Henry', 'Cerritos', 'h.cerritos@demo.com', '$2y$10$RZhzuwxutvc13nhFamlYXevJeYPQxJetmKVYFKJLWc0.FA3vv5UBu', '00003', '06524185-8', 2, 'Laboratorio 1,Laboratorio 2,Laboratorio 4,Laboratorio 7,Laboratorio 8');

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
-- Volcado de datos para la tabla `horas`
--

INSERT INTO `horas` (`id_registro_hora`, `entrada_hora`, `salida_hora`, `actividad_hora`, `id_alumno_hora`, `id_encargado_hora`, `id_laboratorio_hora`, `nombre_laboratorio_hora`, `nombre_ciclo_hora`) VALUES
(1, '2024-05-13 12:30:00', '2024-05-13 08:00:00', 'Limpieza de teclados', '27-5711-2016', 2, 1, 'Laboratorio 1', 'Ciclo 01-2024'),
(2, '2024-05-14 12:30:00', '2024-05-14 09:00:00', 'Mantenimiento de computadoras', '27-5711-2016', 2, 1, 'Laboratorio 1', 'Ciclo 01-2024'),
(3, '2024-05-15 14:00:00', '2024-05-15 09:00:00', 'Digitalización de documentos', '27-5711-2016', 2, 1, 'Laboratorio 1', 'Ciclo 01-2024'),
(4, '2024-05-16 13:30:00', '2024-05-16 08:30:00', 'Instalación y configuración de redes', '27-5711-2016', 2, 2, 'Laboratorio 2', 'Ciclo 01-2024'),
(5, '2024-05-17 21:00:00', '2024-05-17 17:00:00', 'Limpieza de hardware', '27-5711-2016', 2, 2, 'Laboratorio 2', 'Ciclo 01-2024'),
(6, '2024-05-18 12:30:00', '2024-05-18 08:00:00', 'Actualización de sistemas operativos', '27-5711-2016', 2, 2, 'Laboratorio 2', 'Ciclo 01-2024'),
(7, '2024-05-04 19:00:00', '2024-05-04 16:00:00', 'Instalación de software', '27-5711-2017', 2, 2, 'Laboratorio 2', 'Ciclo 01-2024'),
(8, '2024-05-11 19:00:00', '2024-05-11 17:00:00', 'Organizar cables y conexiones', '27-5711-2017', 2, 2, 'Laboratorio 2', 'Ciclo 01-2024'),
(9, '2024-05-18 23:00:00', '2024-05-18 18:00:00', 'Cambiar monitores dañados', '27-5711-2017', 2, 2, 'Laboratorio 2', 'Ciclo 01-2024'),
(10, '2024-05-25 21:00:00', '2024-05-25 17:00:00', 'Realizar copias de seguridad de datos', '27-5711-2017', 2, 3, 'Laboratorio 3', 'Ciclo 01-2024'),
(11, '2024-06-01 19:00:00', '2024-06-01 16:00:00', 'Verificar y actualizar antivirus', '27-5711-2017', 2, 3, 'Laboratorio 3', 'Ciclo 01-2024'),
(12, '2024-05-05 13:00:00', '2024-05-05 13:00:00', 'Reemplazar componentes de hardware defectuosos', '27-5711-2018', 3, 2, 'Laboratorio 2', 'Ciclo 01-2024'),
(13, '2024-05-12 13:00:00', '2024-05-12 14:00:00', 'Instalar drivers y controladores', '27-5711-2018', 3, 2, 'Laboratorio 2', 'Ciclo 01-2024'),
(14, '2024-05-19 20:00:00', '2024-05-19 15:00:00', 'Gestionar el inventario de equipos y accesorios', '27-5711-2018', 3, 3, 'Laboratorio 3', 'Ciclo 01-2024'),
(15, '2024-05-26 18:00:00', '2024-05-26 14:00:00', 'Configurar cuentas de usuario y permisos', '27-5711-2018', 3, 3, 'Laboratorio 3', 'Ciclo 01-2024'),
(16, '2023-11-06 12:30:00', '2023-11-06 08:00:00', 'Limpieza de teclados', '27-5711-2019', 3, 2, 'Laboratorio 2', 'Ciclo 02-2023'),
(17, '2023-11-07 13:30:00', '2023-11-07 08:30:00', 'Instalación y configuración de redes', '27-5711-2019', 3, 2, 'Laboratorio 2', 'Ciclo 02-2023'),
(18, '2023-11-11 19:00:00', '2023-11-11 17:00:00', 'Organizar cables y conexiones', '27-5711-2019', 3, 3, 'Laboratorio 3', 'Ciclo 02-2023'),
(19, '2023-12-03 18:00:00', '2023-12-03 14:00:00', 'Configurar cuentas de usuario y permisos', '27-5711-2019', 3, 3, 'Laboratorio 3', 'Ciclo 02-2023'),
(20, '2023-12-09 21:00:00', '2023-12-09 16:00:00', 'Organizar cables y conexiones', '27-5711-2019', 3, 3, 'Laboratorio 3', 'Ciclo 02-2023'),
(21, '2023-12-12 14:00:00', '2023-12-12 09:00:00', 'Digitalización de documentos', '27-5711-2019', 3, 3, 'Laboratorio 3', 'Ciclo 02-2023'),
(22, '2023-12-13 15:00:00', '2023-12-13 10:00:00', 'Mantenimiento preventivo de equipos', '27-5711-2019', 3, 3, 'Laboratorio 3', 'Ciclo 02-2023'),
(23, '2023-08-07 12:30:00', '2023-08-07 08:00:00', 'Limpieza de teclados', '27-5711-2020', 3, 1, 'Laboratorio 1', 'Ciclo 02-2023'),
(24, '2023-09-04 13:30:00', '2023-09-04 08:30:00', 'Instalación y configuración de redes', '27-5711-2020', 3, 1, 'Laboratorio 1', 'Ciclo 02-2023'),
(25, '2023-10-21 19:00:00', '2023-10-21 17:00:00', 'Organizar cables y conexiones', '27-5711-2020', 2, 1, 'Laboratorio 1', 'Ciclo 02-2023'),
(26, '2023-11-11 18:00:00', '2023-11-11 14:00:00', 'Configurar cuentas de usuario y permisos', '27-5711-2020', 2, 1, 'Laboratorio 1', 'Ciclo 02-2023'),
(27, '2023-11-12 21:00:00', '2023-11-12 16:00:00', 'Organizar cables y conexiones', '27-5711-2020', 2, 1, 'Laboratorio 1', 'Ciclo 02-2023'),
(28, '2023-12-19 14:00:00', '2023-12-19 09:00:00', 'Digitalización de documentos', '27-5711-2020', 2, 1, 'Laboratorio 1', 'Ciclo 02-2023'),
(29, '2024-02-05 15:00:00', '2024-02-05 10:00:00', 'Mantenimiento preventivo de equipos', '27-5711-2020', 2, 1, 'Laboratorio 1', 'Ciclo 01-2024'),
(30, '2024-02-06 14:00:00', '2024-02-06 11:00:00', 'Gestionar y actualizar licencias de software', '27-5711-2020', 2, 1, 'Laboratorio 1', 'Ciclo 01-2024'),
(31, '2024-03-09 13:00:00', '2024-03-09 13:00:00', 'Realizar inventario y etiquetado de equipos', '27-5711-2020', 2, 1, 'Laboratorio 1', 'Ciclo 01-2024'),
(32, '2024-04-15 15:00:00', '2024-04-15 12:00:00', 'Instalar sistemas de proyección y audio para presentaciones', '27-5711-2020', 2, 2, 'Laboratorio 2', 'Ciclo 01-2024'),
(33, '2024-05-20 21:00:00', '2024-05-20 17:00:00', 'Configurar dispositivos de almacenamiento externos', '27-5711-2020', 2, 2, 'Laboratorio 2', 'Ciclo 01-2024'),
(34, '2024-06-13 16:00:00', '2024-06-13 12:00:00', 'Ofrecer soporte técnico a los alumnos', '27-5711-2020', 2, 2, 'Laboratorio 2', 'Ciclo 01-2024'),
(35, '2025-01-31 02:43:05', '2025-01-30 20:43:12', 'si', '27-3210-2022', 1, 1, 'Laboratorio 1', 'Ciclo 01-2025');

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

--
-- Volcado de datos para la tabla `laboratorios`
--

INSERT INTO `laboratorios` (`id_laboratorio`, `nombre_laboratorio`, `telefono_laboratorio`, `ubicacion_laboratorio`, `Denominacion_laboratorio`) VALUES
(1, 'Laboratorio 1', '1234-5678', 'Edificio Francisco Morazán', 'Laboratorio 1 de Informática'),
(2, 'Laboratorio 2', '2555-5555', 'Edificio Francisco Morazán', 'Laboratorio 2 de Informática'),
(3, 'Laboratorio 3', '2555-5555', 'Edificio Benito Juárez', 'Laboratorio 3 de Informática'),
(4, 'Laboratorio 4', '2555-5555', 'Edificio Francisco Morazán', 'Laboratorio 4 de Cisco'),
(5, 'Laboratorio 5', '2250-5555', 'Edificio Benito Juárez', 'Laboratorio 5 de Informática'),
(6, 'Laboratorio 6', '2250-5555', 'Edificio Giuseppe Garibaldi', 'Laboratorio 6 de Informática aplicada al Inglés'),
(7, 'Laboratorio 7', '2250-5555', 'Edificio Francisco Morazan', 'Laboratorio 7 de Tecnologías Avanzadas'),
(8, 'Laboratorio 8', '2250-5555', 'Edificio Francisco Morazán', 'Laboratorio 8 de Redes'),
(9, 'Laboratorio 9', '2250-5555', 'Edificio Giuseppe Garibaldi', 'Laboratorio 9 de Arquitectura y Diseño'),
(10, 'Laboratorio 10', '2250-5555', 'Edificio Benito Juárez', 'Laboratorio 10 de Academia de Microsoft'),
(11, 'Laboratorio 11', '2250-5555', 'Edificio Thomas Jefferson', 'Laboratorio 11 de Informática y Mat Lab'),
(12, 'Laboratorio 12', '2250-5555', 'Edificio Simón Bolívar', 'Laboratorio 12 de Tecnología, Multimedia y Animación'),
(13, 'Laboratorio 13', '2250-5555', 'Edificio Thomas Jefferson', 'Laboratorio 13 de Creación de Contenido Digital'),
(14, 'Laboratorio 14', '2250-5555', 'Edificio Simón Bolívar', 'Laboratorio 14 de Tecnología, Multimedia y Animación'),
(15, 'Laboratorio 15', '2250-5555', 'Edificio Simón Bolívar', 'Laboratorio 15 Data Center Automatización y Robótica'),
(16, 'Laboratorio 16', '2555-5252', 'Francisco Morazan', 'Informatica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(2) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`) VALUES
(1, 'Administrador'),
(2, 'Encargado');

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
  MODIFY `id_alumno` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `ciclos`
--
ALTER TABLE `ciclos`
  MODIFY `id_ciclo` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `encargados`
--
ALTER TABLE `encargados`
  MODIFY `id_encargado` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `horas`
--
ALTER TABLE `horas`
  MODIFY `id_registro_hora` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `laboratorios`
--
ALTER TABLE `laboratorios`
  MODIFY `id_laboratorio` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
