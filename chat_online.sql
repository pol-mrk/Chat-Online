-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 24-10-2024 a las 17:51:30
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
  `id_amigo` int NOT NULL,
  `usuario1` int DEFAULT NULL,
  `usuario2` int DEFAULT NULL,
  `estado` enum('solicitado','rechazado','amigo') DEFAULT NULL,
  PRIMARY KEY (`id_amigo`),
  KEY `usuario1_fk_idx` (`usuario1`),
  KEY `usuario2_fk_idx` (`usuario2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tbl_amigos`
--

INSERT INTO `tbl_amigos` (`id_amigo`, `usuario1`, `usuario2`, `estado`) VALUES
(1, 1, 2, 'solicitado'),
(2, 0, 1, 'amigo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_mensajes`
--

DROP TABLE IF EXISTS `tbl_mensajes`;
CREATE TABLE IF NOT EXISTS `tbl_mensajes` (
  `id_mensaje` int NOT NULL,
  `emisor` int DEFAULT NULL,
  `receptor` int DEFAULT NULL,
  `mensaje_chat` varchar(250) DEFAULT NULL,
  `fecha_chat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mensaje`),
  KEY `usuario_emisor_fk_idx` (`emisor`),
  KEY `usuario_receptor_fk_idx` (`receptor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tbl_mensajes`
--

INSERT INTO `tbl_mensajes` (`id_mensaje`, `emisor`, `receptor`, `mensaje_chat`, `fecha_chat`) VALUES
(1, 1, 2, 'nigganigganigganigga', '2024-10-24 17:49:57'),
(2, 2, 1, 'diddy party mmmm', '2024-10-24 17:49:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuarios`
--

DROP TABLE IF EXISTS `tbl_usuarios`;
CREATE TABLE IF NOT EXISTS `tbl_usuarios` (
  `id_usuario` int NOT NULL,
  `nombre` varchar(25) DEFAULT NULL,
  `apellidos` varchar(35) DEFAULT NULL,
  `email` varchar(75) DEFAULT NULL,
  `contrasena` varchar(16) DEFAULT NULL,
  `reptr_contrasena` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tbl_usuarios`
--

INSERT INTO `tbl_usuarios` (`id_usuario`, `nombre`, `apellidos`, `email`, `contrasena`, `reptr_contrasena`) VALUES
(0, 'Sergi', 'Masip Manchado', 'sergi.masip@gmail.com', '$2a$10$ZCfBx3yZa', '$2a$10$ZCfBx3yZa'),
(1, 'Pol Marc', 'Montero Roca', 'polmarc.monro@gmail.com', '$2a$10$ZCfBx3yZa', '$2a$10$ZCfBx3yZa'),
(2, 'Marc', 'Colomé', 'marc.colome@gmail.com', '$2a$10$ZCfBx3yZa', '$2a$10$ZCfBx3yZa');

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
