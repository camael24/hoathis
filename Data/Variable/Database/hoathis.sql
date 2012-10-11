-- phpMyAdmin SQL Dump
-- version 4.0.0-dev
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 11, 2012 at 04:44 PM
-- Server version: 5.5.27-1~dotdeb.0
-- PHP Version: 5.3.17-1~dotdeb.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hoathis`
--
CREATE DATABASE `hoathis` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `hoathis`;

-- --------------------------------------------------------

--
-- Table structure for table `library`
--

CREATE TABLE IF NOT EXISTS `library` (
  `idLibrary` int(11) NOT NULL AUTO_INCREMENT,
  `refUser` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `home` varchar(255) NOT NULL,
  `release` varchar(255) NOT NULL,
  `documentation` varchar(255) NOT NULL,
  `issues` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `valid` int(11) NOT NULL,
  PRIMARY KEY (`idLibrary`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `library`
--

INSERT INTO `library` (`idLibrary`, `refUser`, `name`, `description`, `home`, `release`, `documentation`, `issues`, `time`, `valid`) VALUES
(1, 2, 'Hi', 'Julien is most beautiful than hywan', 'http://ark.im', 'j', 'j', 'j', '2012-10-11 14:18:47', 1),
(2, 2, 'Hiiiiii', 'iiiiiiii', 'ii', 'ii', 'i', 'i', '2012-10-11 14:18:12', 0),
(3, 2, 'qsdqsd', 'qsdqsdqsd', 'qsdqsdqs', 'qsdqsd', 'qsdqsdqsd', 'qsdqsdqsdq', '2012-10-11 14:18:28', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `rang` int(11) NOT NULL,
  PRIMARY KEY (`idUser`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`idUser`, `name`, `password`, `email`, `rang`) VALUES
(1, 'fee', 'bzzzzdd', 'eeeee', 0),
(2, 'foo', '0beec7b5ea3f0fdbc95d0dd47f3c5bc275da8a33', 'barooooo@hhh', 1),
(3, 'Hello', 'dd3bb71657fca7eb66a287d629420ebc1875cd86', 'foobar', 1),
(4, 'camael', '4438ce731657057ba02736526d2018bfac7d4971', 'thehawk@hoa.io', 2);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
