-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-06-2025 a las 05:23:07
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
(115, 'luis', '2025-06-20', '19:05:04', 'INICIO SESION', 1),
(116, 'luis', '2025-06-20', '05:22:47', 'CERRO SESION', 1),
(117, 'darina', '2025-06-20', '21:22:51', 'INICIO SESION', 2),
(118, 'darina', '2025-06-20', '05:22:56', 'CERRO SESION', 2);

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
(2, 'Administrar modulos', '../AdmModulos/ad_modulos.html', '0'),
(3, 'Consultar bitacora', '../AdmBitacora/bitacora.html', '0'),
(4, 'Administrar Productos Academicos', '../AdmProAca/index.html', '0'),
(5, 'Reportes', '../Reportes/index.html', '0'),
(6, 'Evaluar Productos Academicos', '../Evaluacion/index.html', '0');

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
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(4, 1),
(4, 2),
(4, 3),
(5, 2),
(5, 3),
(6, 2),
(6, 3);

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
(5, 'Karen', 25, 'karen@tesch.mx', 0, 5, 3),
(6, 'erick', 43, 'erick@tesch', 0, 6, 4);

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
(6, 'erick', '1234', 4, '0');

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
  MODIFY `id_b` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `id_mod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `perfil`
--
ALTER TABLE `perfil`
  MODIFY `id_p` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
