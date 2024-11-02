-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 02-11-2024 a las 19:44:02
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `chat_online`
--
CREATE DATABASE IF NOT EXISTS `chat_online` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `chat_online`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_amigos`
--

DROP TABLE IF EXISTS `tbl_amigos`;
CREATE TABLE IF NOT EXISTS `tbl_amigos` (
  `id_amigo` int NOT NULL AUTO_INCREMENT,
  `usuario1` int DEFAULT NULL,
  `usuario2` int DEFAULT NULL,
  `estado` enum('solicitado','rechazado','amigo') DEFAULT NULL,
  PRIMARY KEY (`id_amigo`),
  UNIQUE KEY `relacion_unica_amigos` (`usuario1`,`usuario2`),
  KEY `usuario1_fk_idx` (`usuario1`),
  KEY `usuario2_fk_idx` (`usuario2`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tbl_amigos`
--

INSERT INTO `tbl_amigos` (`id_amigo`, `usuario1`, `usuario2`, `estado`) VALUES
(12, 1, 2, 'amigo'),
(16, 1, 3, 'amigo'),
(18, 1, 5, 'solicitado'),
(19, 2, 5, 'amigo'),
(24, 2, 3, 'rechazado');

--
-- Disparadores `tbl_amigos`
--
DROP TRIGGER IF EXISTS `ordenar_ids_de_menor_a_mayor`;
DELIMITER $$
CREATE TRIGGER `ordenar_ids_de_menor_a_mayor` BEFORE INSERT ON `tbl_amigos` FOR EACH ROW BEGIN
    IF NEW.usuario1 > NEW.usuario2 THEN
        SET @temp = NEW.usuario1;
        SET NEW.usuario1 = NEW.usuario2;
        SET NEW.usuario2 = @temp;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_mensajes`
--

DROP TABLE IF EXISTS `tbl_mensajes`;
CREATE TABLE IF NOT EXISTS `tbl_mensajes` (
  `id_mensaje` int NOT NULL AUTO_INCREMENT,
  `emisor` int DEFAULT NULL,
  `receptor` int DEFAULT NULL,
  `mensaje_chat` varchar(250) DEFAULT NULL,
  `fecha_chat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mensaje`),
  KEY `usuario_emisor_fk_idx` (`emisor`),
  KEY `usuario_receptor_fk_idx` (`receptor`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tbl_mensajes`
--

INSERT INTO `tbl_mensajes` (`id_mensaje`, `emisor`, `receptor`, `mensaje_chat`, `fecha_chat`) VALUES
(1, 1, 2, 'sdgftrh', '2024-10-30 21:23:27'),
(2, 2, 1, 'erhty', '2024-10-30 20:09:01'),
(4, 2, 1, 'sxdcfgt', '2024-10-30 21:27:17'),
(5, 1, 2, 'WEFER', '2024-10-30 21:28:20'),
(6, 1, 2, 'WEFER', '2024-10-30 21:28:50'),
(22, 1, 2, 'ertrtrtg', '2024-11-02 18:49:42'),
(23, 2, 1, 'ergrtg', '2024-11-02 18:49:48'),
(24, 2, 1, 'ergert', '2024-11-02 18:49:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuarios`
--

DROP TABLE IF EXISTS `tbl_usuarios`;
CREATE TABLE IF NOT EXISTS `tbl_usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) DEFAULT NULL,
  `apellidos` varchar(35) DEFAULT NULL,
  `email` varchar(75) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `estado` enum('desconectado','en línea') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tbl_usuarios`
--

INSERT INTO `tbl_usuarios` (`id_usuario`, `nombre`, `apellidos`, `email`, `contrasena`, `estado`) VALUES
(1, 'PolMarc', 'Montero Roca', 'polmarc.monro@gmail.com', '$2y$10$jnbvSB1LuDLrqQ/hWxmBVeqGqMYqq5M4gdDPji5KApX.07IQ52yiS', 'en línea'),
(2, 'Marcolo', 'Colome Cuenca', 'marcolo@gmail.com', '$2y$10$dUeBIuF0dUnNJaxWc6Xww.YpbeK8j.ZdLnUrQQklpEgdChUO.54tS', 'en línea'),
(3, 'Sergi', 'Masip Manchado', 'sergi.masip@gmail.com', '$2y$10$dUeBIuF0dUnNJaxWc6Xww.YpbeK8j.ZdLnUrQQklpEgdChUO.54tS', 'en línea'),
(4, 'Marioto', 'Ruiz Camuñas', 'marioto.ruiz@gmail.com', '$2y$10$dUeBIuF0dUnNJaxWc6Xww.YpbeK8j.ZdLnUrQQklpEgdChUO.54tS', 'en línea'),
(5, 'David', 'Hompanera Campos', 'david.hompanera@gmail.com', '$2y$10$dUeBIuF0dUnNJaxWc6Xww.YpbeK8j.ZdLnUrQQklpEgdChUO.54tS', 'en línea'),
(6, 'Iker', 'Luna Cruz', 'iker.luna@gmail.com', '$2y$10$dUeBIuF0dUnNJaxWc6Xww.YpbeK8j.ZdLnUrQQklpEgdChUO.54tS', 'en línea');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tbl_amigos`
--
ALTER TABLE `tbl_amigos`
  ADD CONSTRAINT `usuario1_fk` FOREIGN KEY (`usuario1`) REFERENCES `tbl_usuarios` (`id_usuario`),
  ADD CONSTRAINT `usuario2_fk` FOREIGN KEY (`usuario2`) REFERENCES `tbl_usuarios` (`id_usuario`);

--
-- Filtros para la tabla `tbl_mensajes`
--
ALTER TABLE `tbl_mensajes`
  ADD CONSTRAINT `usuario_emisor_fk` FOREIGN KEY (`emisor`) REFERENCES `tbl_usuarios` (`id_usuario`),
  ADD CONSTRAINT `usuario_receptor_fk` FOREIGN KEY (`receptor`) REFERENCES `tbl_usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
