-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 18 apr 2019 kl 09:51
-- Serverversion: 10.1.37-MariaDB
-- PHP-version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `fundmanager`
--
CREATE DATABASE IF NOT EXISTS `fundmanager` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `fundmanager`;

-- --------------------------------------------------------

--
-- Tabellstruktur `stockinfo`
--

DROP TABLE IF EXISTS `stockinfo`;
CREATE TABLE `stockinfo` (
  `ticker` varchar(6) NOT NULL,
  `open` float NOT NULL,
  `close` float NOT NULL,
  `week` float NOT NULL,
  `month` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur `stocks`
--

DROP TABLE IF EXISTS `stocks`;
CREATE TABLE `stocks` (
  `ticker` varchar(6) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_swedish_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `password` varchar(100) NOT NULL,
  `tradeCapital` double NOT NULL,
  `portfolioCapital` double NOT NULL,
  `isVerified` tinyint(1) NOT NULL,
  `firstCapital` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `users`
--

INSERT INTO `users` (`firstName`, `lastName`, `email`, `password`, `tradeCapital`, `portfolioCapital`, `isVerified`, `firstCapital`) VALUES
('Joakim', 'SjÃ¶quist', 'joakim.sjoquist@hotmail.com', 'test', 25000, 0, 1, 0);

-- --------------------------------------------------------

--
-- Tabellstruktur `usertostock`
--

DROP TABLE IF EXISTS `usertostock`;
CREATE TABLE `usertostock` (
  `email` varchar(100) NOT NULL,
  `ticker` varchar(6) NOT NULL,
  `amount` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `usertostock`
--

INSERT INTO `usertostock` (`email`, `ticker`, `amount`) VALUES
('joakim.sjoquist@hotmail.com', 'FB', 4),
('joakim.sjoquist@hotmail.com', 'AMZN', 3);

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `stockinfo`
--
ALTER TABLE `stockinfo`
  ADD PRIMARY KEY (`ticker`);

--
-- Index för tabell `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`ticker`);

--
-- Index för tabell `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
