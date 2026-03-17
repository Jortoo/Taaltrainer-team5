-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 16, 2026 at 03:29 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `taaltrainer`
--

-- --------------------------------------------------------

--
-- Table structure for table `antwoordopties`
--

CREATE TABLE `antwoordopties` (
  `answer_id` int NOT NULL,
  `question_id` int NOT NULL,
  `answer_text` varchar(100) NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `antwoordopties`
--

INSERT INTO `antwoordopties` (`answer_id`, `question_id`, `answer_text`, `is_correct`) VALUES
(1, 1, 'Hallo', 1),
(2, 1, 'Tot ziens', 0),
(3, 1, 'Dank je', 0),
(4, 1, 'Sorry', 0),
(5, 2, 'Eple', 1),
(6, 2, 'Banan', 0),
(7, 2, 'Jordbær', 0),
(8, 2, 'Appelsin', 0),
(9, 3, 'Dank je', 1),
(10, 3, 'Sorry', 0),
(11, 3, 'Tot ziens', 0),
(12, 3, 'Hallo', 0),
(13, 4, 'Hund', 1),
(14, 4, 'Katt', 0),
(15, 4, 'Fugl', 0),
(16, 4, 'Fisk', 0),
(17, 5, 'Goedemorgen', 1),
(18, 5, 'Goedenacht', 0),
(19, 5, 'Goedemiddag', 0),
(20, 5, 'Tot ziens', 0),
(21, 6, 'Water', 1),
(22, 6, 'Melk', 0),
(23, 6, 'Brood', 0),
(24, 6, 'Sap', 0),
(25, 7, 'Skole', 1),
(26, 7, 'Bok', 0),
(27, 7, 'Hus', 0),
(28, 7, 'Bil', 0),
(29, 8, 'Groot', 1),
(30, 8, 'Klein', 0),
(31, 8, 'Snel', 0),
(32, 8, 'Mooi', 0),
(33, 9, 'Venn', 1),
(34, 9, 'Fiende', 0),
(35, 9, 'Familie', 0),
(36, 9, 'Nabo', 0),
(37, 10, 'Kleuren', 1),
(38, 10, 'Dieren', 0),
(39, 10, 'Getallen', 0),
(40, 10, 'Woorden', 0);

-- --------------------------------------------------------

--
-- Table structure for table `gebruikers`
--

CREATE TABLE `gebruikers` (
  `user_id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `wachtwoord` varchar(255) NOT NULL,
  `datum_registratie` date NOT NULL DEFAULT (curdate()),
  `total_score` int NOT NULL DEFAULT '0',
  `xp` int NOT NULL DEFAULT '0',
  `level` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gebruikers`
--

INSERT INTO `gebruikers` (`user_id`, `username`, `email`, `wachtwoord`, `datum_registratie`, `total_score`, `xp`, `level`) VALUES
(2, '123test', '123test@gmail.com', '$2y$10$fkns7q4lCpagJhM9kPtaLekeqilF5baQkBBR6XOtHc2n0asRE9Umm', '2026-03-16', 5, 50, 2);

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `level_id` int NOT NULL,
  `question_amount` int NOT NULL DEFAULT '5',
  `xp_reward` int NOT NULL DEFAULT '50'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`level_id`, `question_amount`, `xp_reward`) VALUES
(1, 5, 50),
(2, 5, 75),
(3, 5, 100),
(4, 5, 125),
(5, 5, 150);

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `score` int NOT NULL,
  `totaal` int NOT NULL,
  `gespeeld_op` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scores`
--

INSERT INTO `scores` (`id`, `user_id`, `score`, `totaal`, `gespeeld_op`) VALUES
(1, 2, 5, 10, '2026-03-16 16:22:12');

-- --------------------------------------------------------

--
-- Table structure for table `vragen`
--

CREATE TABLE `vragen` (
  `question_id` int NOT NULL,
  `level_id` int NOT NULL DEFAULT '1',
  `difficulty` varchar(20) NOT NULL DEFAULT 'makkelijk',
  `question_text` text NOT NULL,
  `xp_reward` int NOT NULL DEFAULT '10'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vragen`
--

INSERT INTO `vragen` (`question_id`, `level_id`, `difficulty`, `question_text`, `xp_reward`) VALUES
(1, 1, 'makkelijk', 'Wat betekent \'hei\'?', 10),
(2, 1, 'makkelijk', 'Wat is het Noorse woord voor \'appel\'?', 10),
(3, 1, 'makkelijk', 'Wat betekent \'takk\'?', 10),
(4, 1, 'makkelijk', 'Wat is het Noorse woord voor \'hond\'?', 10),
(5, 1, 'makkelijk', 'Wat betekent \'god morgen\'?', 10),
(6, 2, 'gemiddeld', 'Wat betekent \'vann\'?', 15),
(7, 2, 'gemiddeld', 'Wat is het Noorse woord voor \'school\'?', 15),
(8, 2, 'gemiddeld', 'Wat betekent \'stor\'?', 15),
(9, 2, 'gemiddeld', 'Wat is het Noorse woord voor \'vriend\'?', 15),
(10, 2, 'gemiddeld', 'Wat betekent \'farger\'?', 15);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `antwoordopties`
--
ALTER TABLE `antwoordopties`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `gebruikers`
--
ALTER TABLE `gebruikers`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`level_id`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `vragen`
--
ALTER TABLE `vragen`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `level_id` (`level_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `antwoordopties`
--
ALTER TABLE `antwoordopties`
  MODIFY `answer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `gebruikers`
--
ALTER TABLE `gebruikers`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `level_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vragen`
--
ALTER TABLE `vragen`
  MODIFY `question_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `antwoordopties`
--
ALTER TABLE `antwoordopties`
  ADD CONSTRAINT `antwoordopties_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `vragen` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `gebruikers` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `vragen`
--
ALTER TABLE `vragen`
  ADD CONSTRAINT `vragen_ibfk_1` FOREIGN KEY (`level_id`) REFERENCES `levels` (`level_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;