-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.14-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Volcando estructura de base de datos para todomanager
CREATE DATABASE IF NOT EXISTS `todomanager` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `todomanager`;

-- Volcando estructura para tabla todomanager.privilegios
CREATE TABLE IF NOT EXISTS `privilegios` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla todomanager.privilegios: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `privilegios` DISABLE KEYS */;
INSERT INTO `privilegios` (`id`, `nombre`) VALUES
	(1, 'Lectura'),
	(2, 'Escritura'),
	(3, 'Sobre otros usuarios');
/*!40000 ALTER TABLE `privilegios` ENABLE KEYS */;

-- Volcando estructura para tabla todomanager.proyectos
CREATE TABLE IF NOT EXISTS `proyectos` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `usuario_creador` smallint(6) NOT NULL,
  `nombre` varchar(20) CHARACTER SET utf8 NOT NULL,
  `descripcion` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_creador` (`usuario_creador`),
  CONSTRAINT `proyectos_ibfk_1` FOREIGN KEY (`usuario_creador`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla todomanager.proyectos: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `proyectos` DISABLE KEYS */;
/*!40000 ALTER TABLE `proyectos` ENABLE KEYS */;

-- Volcando estructura para tabla todomanager.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) CHARACTER SET utf8 NOT NULL,
  `privilegios` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `privilegios` (`privilegios`),
  CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`privilegios`) REFERENCES `privilegios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla todomanager.roles: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `nombre`, `privilegios`) VALUES
	(1, 'Usuario', 2),
	(2, 'Administrador', 3);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Volcando estructura para tabla todomanager.tareas
CREATE TABLE IF NOT EXISTS `tareas` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) CHARACTER SET utf8 NOT NULL,
  `descripcion` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  `proyecto` smallint(6) NOT NULL,
  `parentID` smallint(6) DEFAULT NULL,
  `estado` bit(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `proyecto` (`proyecto`),
  CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`proyecto`) REFERENCES `proyectos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla todomanager.tareas: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `tareas` DISABLE KEYS */;
/*!40000 ALTER TABLE `tareas` ENABLE KEYS */;

-- Volcando estructura para tabla todomanager.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `email` varchar(40) CHARACTER SET utf8 NOT NULL,
  `nickname` varchar(20) CHARACTER SET utf8 NOT NULL,
  `pwd` varchar(200) CHARACTER SET utf8 NOT NULL,
  `rol` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_rol_usuario` (`rol`),
  CONSTRAINT `fk_rol_usuario` FOREIGN KEY (`rol`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla todomanager.usuarios: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` (`id`, `email`, `nickname`, `pwd`, `rol`) VALUES
	(1, 'admin@admin.com', 'Admin', '21232f297a57a5a743894a0e4a801fc3', 2);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
