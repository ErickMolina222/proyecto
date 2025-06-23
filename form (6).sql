-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-06-2025 a las 01:24:34
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
-- Base de datos: `form`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerConteoPerfiles` ()   BEGIN
    SELECT p.Nombre AS perfil, COUNT(*) AS cantidad
    FROM usuario u
    INNER JOIN perfil p ON u.id_p = p.id_p
    WHERE u.Borrado = '0' AND p.Borrado = '0'
    GROUP BY p.Nombre;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `registrar_usuario_persona` (IN `p_nombre` VARCHAR(100), IN `p_edad` INT, IN `p_correo` VARCHAR(100), IN `p_nick` VARCHAR(50), IN `p_contrasena` VARCHAR(100), IN `p_perfil` INT, IN `p_carrera` INT)   BEGIN
    DECLARE correo_existente INT;
    DECLARE nick_existente INT;
    DECLARE nuevo_id_u INT;

    -- Validar si el correo ya existe
    SELECT COUNT(*) INTO correo_existente
    FROM persona
    WHERE correo = p_correo AND borrado = 0;

    -- Validar si el nick ya existe
    SELECT COUNT(*) INTO nick_existente
    FROM usuario
    WHERE nick = p_nick AND borrado = 0;

    -- Si ya existe el correo o el nick, no continuar
    IF correo_existente > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El correo ya está registrado.';
    ELSEIF nick_existente > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El nick ya está registrado.';
    ELSE
        -- Generar nuevo id_u (asumiendo que puede estar vacía la tabla)
        SELECT IFNULL(MAX(id_u), 0) + 1 INTO nuevo_id_u FROM usuario;

        -- Insertar en tabla usuario
        INSERT INTO usuario (id_u, nick, pwd, id_p, borrado)
        VALUES (nuevo_id_u, p_nick, p_contrasena, p_perfil, 0);

        -- Insertar en tabla persona incluyendo carrera
        INSERT INTO persona (id_persona, nombre, edad, correo, borrado, id_u, id_carrera)
        VALUES (nuevo_id_u, p_nombre, p_edad, p_correo, 0, nuevo_id_u, p_carrera);
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id_b` int(11) NOT NULL,
  `nick` varchar(50) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `accion` varchar(50) NOT NULL,
  `id_u` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`id_b`, `nick`, `fecha`, `hora`, `accion`, `id_u`) VALUES
(1, 'abel', '2025-06-22', '06:11:57', 'CERRO SESION', 8),
(2, 'pedro', '2025-06-22', '22:12:03', 'INICIO SESION', 4),
(3, 'pedro', '2025-06-22', '06:13:20', 'CERRO SESION', 4),
(4, 'emily', '2025-06-22', '22:14:00', 'INICIO SESION', 9),
(5, 'emily', '2025-06-22', '06:25:48', 'CERRO SESION', 9),
(6, 'pedro', '2025-06-22', '22:26:11', 'INICIO SESION', 4),
(7, 'pedro', '2025-06-22', '06:26:39', 'CERRO SESION', 4),
(8, 'emily', '2025-06-22', '22:26:45', 'INICIO SESION', 9),
(9, 'emily', '2025-06-22', '06:27:04', 'CERRO SESION', 9),
(10, 'abel', '2025-06-22', '22:27:08', 'INICIO SESION', 8),
(11, 'abel', '2025-06-22', '06:31:09', 'CERRO SESION', 8),
(12, 'luis', '2025-06-22', '22:32:36', 'INICIO SESION', 1),
(13, 'luis', '2025-06-22', '06:36:46', 'CERRO SESION', 1),
(14, 'darina', '2025-06-22', '22:36:49', 'INICIO SESION', 2),
(15, 'darina', '2025-06-22', '06:50:30', 'CERRO SESION', 2),
(16, 'luis', '2025-06-22', '22:51:13', 'INICIO SESION', 1),
(17, 'luis', '2025-06-22', '06:51:23', 'CERRO SESION', 1),
(18, 'pedro', '2025-06-22', '22:51:28', 'INICIO SESION', 4),
(19, 'luis', '2025-06-22', '15:18:34', 'INICIO SESION', 1),
(20, 'luis', '2025-06-22', '23:20:16', 'CERRO SESION', 1),
(21, 'abel', '2025-06-22', '15:20:25', 'INICIO SESION', 8),
(22, 'abel', '2025-06-23', '00:32:16', 'CERRO SESION', 8),
(23, 'pedro', '2025-06-23', '16:32:29', 'INICIO SESION', 4),
(24, 'pedro', '2025-06-23', '00:33:09', 'CERRO SESION', 4),
(25, 'abel', '2025-06-23', '16:33:16', 'INICIO SESION', 8),
(26, 'abel', '2025-06-23', '00:53:07', 'CERRO SESION', 8),
(27, 'darina', '2025-06-23', '16:53:12', 'INICIO SESION', 2),
(28, 'darina', '2025-06-23', '16:53:16', 'INGRESO A ADMINMODULOS', 2),
(29, 'darina', '2025-06-23', '16:54:21', 'INGRESO A ADMINMODULOS', 2),
(30, 'darina', '2025-06-23', '16:54:21', 'INGRESO A ADMINMODULOS', 2),
(31, 'darina', '2025-06-23', '16:54:34', 'INGRESO A ADMINMODULOS', 2),
(32, 'darina', '2025-06-23', '16:54:34', 'INGRESO A ADMINMODULOS', 2),
(33, 'darina', '2025-06-23', '16:54:42', 'INGRESO A ADMINMODULOS', 2),
(34, 'darina', '2025-06-23', '16:54:42', 'INGRESO A ADMINMODULOS', 2),
(35, 'darina', '2025-06-23', '00:54:46', 'CERRO SESION', 2),
(36, 'abel', '2025-06-23', '16:54:51', 'INICIO SESION', 8),
(37, 'abel', '2025-06-23', '00:54:58', 'CERRO SESION', 8),
(38, 'darina', '2025-06-23', '16:55:32', 'INICIO SESION', 2),
(39, 'darina', '2025-06-23', '00:55:34', 'INGRESO A ADMINUSUARIOS', 2),
(40, 'darina', '2025-06-23', '00:55:40', 'CERRO SESION', 2),
(41, 'erick', '2025-06-23', '16:55:44', 'INICIO SESION', 6),
(42, 'erick', '2025-06-23', '00:55:57', 'CERRO SESION', 6),
(43, 'emily', '2025-06-23', '16:56:23', 'INICIO SESION', 9),
(44, 'emily', '2025-06-23', '00:57:01', 'CERRO SESION', 9),
(45, 'darina', '2025-06-23', '17:00:44', 'INICIO SESION', 2),
(46, 'darina', '2025-06-23', '17:02:01', 'CONSULTO BITACORA', 2),
(47, 'darina', '2025-06-23', '17:03:13', 'INGRESO A ADMINMODULOS', 2),
(48, 'darina', '2025-06-23', '17:03:18', 'INGRESO A ADMINMODULOS', 2),
(49, 'darina', '2025-06-23', '01:03:26', 'CERRO SESION', 2),
(50, 'abel', '2025-06-23', '17:03:29', 'INICIO SESION', 8),
(51, 'abel', '2025-06-23', '01:03:37', 'CERRO SESION', 8),
(52, 'luis', '2025-06-23', '17:03:40', 'INICIO SESION', 1),
(53, 'luis', '2025-06-23', '01:04:05', 'CERRO SESION', 1),
(54, 'pedro', '2025-06-23', '17:04:08', 'INICIO SESION', 4),
(55, 'pedro', '2025-06-23', '01:07:46', 'CERRO SESION', 4),
(56, 'leoncio', '2025-06-23', '17:20:33', 'INICIO SESION', 14),
(57, 'leoncio', '2025-06-23', '01:24:26', 'CERRO SESION', 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrera`
--

CREATE TABLE `carrera` (
  `id_ca` int(4) NOT NULL,
  `nombreCa` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrera`
--

INSERT INTO `carrera` (`id_ca`, `nombreCa`) VALUES
(1, 'Ingenieria en Sistemas'),
(2, 'Ingenieria Industrial'),
(3, 'Ingenieria Informatica'),
(4, 'Ingenieria Electronica'),
(5, 'Ingenieria Electromecanica'),
(6, 'Ingenieria en Administracion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `id_mod` int(11) NOT NULL,
  `Nombre` varchar(40) NOT NULL,
  `URL` varchar(70) DEFAULT NULL,
  `Borrado` char(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`id_mod`, `Nombre`, `URL`, `Borrado`) VALUES
(1, 'Administrar Usuario', '../AdmUsuarios/con_AdminU.php', '0'),
(2, 'Administrar modulos', '../AdmModulos/ad_modulos.php', '0'),
(3, 'Consultar bitacora', '../AdmBitacora/bitacora.html', '0'),
(4, 'Administrar Productos Academicos', '../AdmProAca/index.php', '0'),
(5, 'Reportes', '../Reportes/index.html', '0'),
(6, 'Evaluar Productos Academicos', '../Evaluacion/index.html', '0'),
(7, 'Graficar', '../Main/crearPdf.php', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mod_perfil`
--

CREATE TABLE `mod_perfil` (
  `id_mod` int(11) NOT NULL,
  `id_p` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mod_perfil`
--

INSERT INTO `mod_perfil` (`id_mod`, `id_p`) VALUES
(1, 2),
(2, 2),
(3, 2),
(4, 1),
(4, 2),
(5, 2),
(5, 3),
(5, 4),
(6, 2),
(6, 3),
(7, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE `perfil` (
  `id_p` int(11) NOT NULL,
  `Nombre` varchar(25) NOT NULL,
  `Descripcion` varchar(70) DEFAULT NULL,
  `Borrado` char(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`id_p`, `Nombre`, `Descripcion`, `Borrado`) VALUES
(1, 'Docente', 'Perfil del docente', '0'),
(2, 'Administrador', 'Perfil del administrador de la empresa', '0'),
(3, 'Jef@ de carrera', 'Perfil de la jefa o jefe de carrera', '0'),
(4, 'Director', 'Perfil del difector', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id_persona` int(4) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `edad` int(3) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `borrado` int(2) NOT NULL,
  `id_u` int(4) NOT NULL,
  `id_carrera` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_persona`, `nombre`, `edad`, `correo`, `borrado`, `id_u`, `id_carrera`) VALUES
(1, 'luis', 34, 'luis@tesch.edu.mx', 0, 1, 1),
(2, 'Darina', 78, 'darina@tesch', 0, 2, 1),
(3, 'Axel', 34, 'axel@tesch', 0, 3, 2),
(4, 'Pedro', 34, 'pedro@tesch', 0, 4, 3),
(5, 'Karen', 25, 'karen@tesch.mx', 0, 5, 4),
(6, 'erick', 43, 'erick@tesch', 0, 6, 5),
(7, 'Brian', 32, 'brian@tesch.edu.mx', 0, 7, 6),
(8, 'abel', 43, 'abel@tesch.mx', 0, 8, 1),
(9, 'Emily', 34, 'emily@tesch.edu.mx', 0, 9, 2),
(10, 'David', 54, 'david@tesch.edu.mx', 0, 10, 3),
(11, 'Gabriel', 34, 'gabriel@tesch.edu.mx', 0, 11, 4),
(12, 'Juan', 65, 'juan@tesch.edu.mx', 0, 12, 5),
(13, 'Jose', 34, 'jose@tesch.edu.mx', 0, 13, 6),
(14, 'Leonardo', 56, 'leo@tesch.edu.mx', 0, 14, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productoaca`
--

CREATE TABLE `productoaca` (
  `id_pa` int(4) NOT NULL,
  `Estatus` varchar(20) DEFAULT NULL,
  `titulo` varchar(30) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_termino` date DEFAULT NULL,
  `calificacion` float DEFAULT NULL,
  `DocumentoProvatorio` varchar(50) DEFAULT NULL,
  `urlConsulta` varchar(50) DEFAULT NULL,
  `borrado` int(1) DEFAULT NULL,
  `id_usuario` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_u` int(11) NOT NULL,
  `Nick` varchar(20) NOT NULL,
  `Pwd` varchar(8) NOT NULL,
  `id_p` int(11) DEFAULT NULL,
  `Borrado` char(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_u`, `Nick`, `Pwd`, `id_p`, `Borrado`) VALUES
(1, 'luis', '1234', 1, '0'),
(2, 'darina', '1234', 2, '0'),
(3, 'axel', '1234', 1, '0'),
(4, 'pedro', '1234', 1, '0'),
(5, 'karen', '1234', 1, '0'),
(6, 'erick', '1234', 1, '0'),
(7, 'brian', '1234', 1, '0'),
(8, 'abel', '1234', 3, '0'),
(9, 'emily', '1234', 3, '0'),
(10, 'david', '1234', 3, '0'),
(11, 'gabriel', '1234', 3, '0'),
(12, 'juan', '1234', 3, '0'),
(13, 'jose', '1234', 3, '0'),
(14, 'leoncio', '1234', 4, '0');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id_b`),
  ADD KEY `id_u` (`id_u`);

--
-- Indices de la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD PRIMARY KEY (`id_ca`);

--
-- Indices de la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`id_mod`);

--
-- Indices de la tabla `mod_perfil`
--
ALTER TABLE `mod_perfil`
  ADD PRIMARY KEY (`id_mod`,`id_p`),
  ADD KEY `id_p` (`id_p`);

--
-- Indices de la tabla `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`id_p`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id_persona`),
  ADD KEY `fk_usuario_persona` (`id_u`),
  ADD KEY `fk_persona_carrera` (`id_carrera`);

--
-- Indices de la tabla `productoaca`
--
ALTER TABLE `productoaca`
  ADD PRIMARY KEY (`id_pa`),
  ADD KEY `fk_producto_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_u`),
  ADD UNIQUE KEY `Nick` (`Nick`),
  ADD KEY `id_p` (`id_p`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id_b` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `id_mod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `perfil`
--
ALTER TABLE `perfil`
  MODIFY `id_p` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `productoaca`
--
ALTER TABLE `productoaca`
  MODIFY `id_pa` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`id_u`) REFERENCES `usuario` (`id_u`) ON DELETE SET NULL;

--
-- Filtros para la tabla `mod_perfil`
--
ALTER TABLE `mod_perfil`
  ADD CONSTRAINT `mod_perfil_ibfk_1` FOREIGN KEY (`id_mod`) REFERENCES `modulo` (`id_mod`) ON DELETE CASCADE,
  ADD CONSTRAINT `mod_perfil_ibfk_2` FOREIGN KEY (`id_p`) REFERENCES `perfil` (`id_p`) ON DELETE CASCADE;

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `fk_persona_carrera` FOREIGN KEY (`id_carrera`) REFERENCES `carrera` (`id_ca`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuario_persona` FOREIGN KEY (`id_u`) REFERENCES `usuario` (`id_u`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productoaca`
--
ALTER TABLE `productoaca`
  ADD CONSTRAINT `fk_producto_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_u`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_p`) REFERENCES `perfil` (`id_p`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
