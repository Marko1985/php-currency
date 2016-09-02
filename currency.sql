-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Servidor: localhost:3306
-- Tiempo de generación: 02-09-2016 a las 10:32:48
-- Versión del servidor: 5.6.31-log
-- Versión de PHP: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `rayvolt_v1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `currency`
--

CREATE TABLE IF NOT EXISTS `currency` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `currency_name` varchar(100) NOT NULL,
  `international` varchar(3) NOT NULL,
  `currency_symbol` varchar(2) NOT NULL,
  `position` tinyint(1) NOT NULL DEFAULT '0',
  `value` float(16,6) NOT NULL,
  `master` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `international` (`international`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `currency`
--

INSERT INTO `currency` (`id`, `currency_name`, `international`, `currency_symbol`, `position`, `value`, `master`) VALUES
(1, 'Dollar', 'USD', '$', 1, 1.000000, 1),
(2, 'Euro', 'EUR', '€', 0, 0.896220, 0),
(3, 'Pound', 'GBP', '£', 1, 0.771200, 0),
(5, 'Canadian Dollar', 'CAD', 'C$', 1, 1.297500, 0),
(7, 'Switzerland ', 'CHF', 'ch', 1, 1.000000, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
