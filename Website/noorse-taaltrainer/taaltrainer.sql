-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 18 mrt 2026 om 13:38
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.0.30

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
-- Tabelstructuur voor tabel `antwoordopties`
--

CREATE TABLE `antwoordopties` (
  `answer_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_text` varchar(100) NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `antwoordopties`
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
(40, 10, 'Woorden', 0),
(41, 11, 'Nee', 1),
(42, 11, 'Ja', 0),
(43, 11, 'Misschien', 0),
(44, 11, 'Altijd', 0),
(45, 12, 'Hus', 1),
(46, 12, 'Bil', 0),
(47, 12, 'Bok', 0),
(48, 12, 'Skole', 0),
(49, 13, 'Ja', 1),
(50, 13, 'Nee', 0),
(51, 13, 'Misschien', 0),
(52, 13, 'Nooit', 0),
(53, 14, 'Katt', 1),
(54, 14, 'Hund', 0),
(55, 14, 'Fugl', 0),
(56, 14, 'Fisk', 0),
(57, 15, 'Goedenacht', 1),
(58, 15, 'Goedemorgen', 0),
(59, 15, 'Tot ziens', 0),
(60, 15, 'Hallo', 0),
(61, 16, 'Fugl', 1),
(62, 16, 'Fisk', 0),
(63, 16, 'Hund', 0),
(64, 16, 'Katt', 0),
(65, 17, 'Auto', 1),
(66, 17, 'Trein', 0),
(67, 17, 'Bus', 0),
(68, 17, 'Fiets', 0),
(69, 18, 'Fisk', 1),
(70, 18, 'Fugl', 0),
(71, 18, 'Hund', 0),
(72, 18, 'Katt', 0),
(73, 19, 'Boek', 1),
(74, 19, 'Pen', 0),
(75, 19, 'Papier', 0),
(76, 19, 'Krant', 0),
(77, 20, 'Melk', 1),
(78, 20, 'Water', 0),
(79, 20, 'Sap', 0),
(80, 20, 'Thee', 0),
(81, 21, 'Hoe gaat het?', 1),
(82, 21, 'Wat is je naam?', 0),
(83, 21, 'Waar woon je?', 0),
(84, 21, 'Hoe oud ben je?', 0),
(85, 22, 'Ik ben goed', 1),
(86, 22, 'Ik heet...', 0),
(87, 22, 'Ik woon in Nederland', 0),
(88, 22, 'Ik ben moe', 0),
(89, 23, 'Wat is je naam?', 1),
(90, 23, 'Hoe gaat het?', 0),
(91, 23, 'Waar woon je?', 0),
(92, 23, 'Spreek je Engels?', 0),
(93, 24, 'Ik heet...', 1),
(94, 24, 'Ik woon...', 0),
(95, 24, 'Ik begrijp...', 0),
(96, 24, 'Ik wil...', 0),
(97, 25, 'Waar woon je?', 1),
(98, 25, 'Hoe heet je?', 0),
(99, 25, 'Hoe gaat het?', 0),
(100, 25, 'Hoe oud ben je?', 0),
(101, 26, 'Ik woon in Nederland', 1),
(102, 26, 'Ik ga naar Nederland', 0),
(103, 26, 'Ik ben Nederlands', 0),
(104, 26, 'Ik werk in Nederland', 0),
(105, 27, 'Hoe oud ben je?', 1),
(106, 27, 'Waar woon je?', 0),
(107, 27, 'Wat is je naam?', 0),
(108, 27, 'Hoe gaat het?', 0),
(109, 28, 'Ik ben 20 jaar', 1),
(110, 28, 'Ik woon 20 jaar', 0),
(111, 28, 'Ik heet 20', 0),
(112, 28, 'Ik werk 20', 0),
(113, 29, 'Spreek je Engels?', 1),
(114, 29, 'Waar woon je?', 0),
(115, 29, 'Wat eet je?', 0),
(116, 29, 'Wat is je naam?', 0),
(117, 30, 'Ja een beetje', 1),
(118, 30, 'Nee nooit', 0),
(119, 30, 'Ja altijd', 0),
(120, 30, 'Ik weet het niet', 0),
(121, 31, 'Lopen', 1),
(122, 31, 'Eten', 0),
(123, 31, 'Lezen', 0),
(124, 31, 'Slapen', 0),
(125, 32, 'Eten', 1),
(126, 32, 'Drinken', 0),
(127, 32, 'Werken', 0),
(128, 32, 'Lopen', 0),
(129, 33, 'Drinken', 1),
(130, 33, 'Eten', 0),
(131, 33, 'Slapen', 0),
(132, 33, 'Lezen', 0),
(133, 34, 'Slapen', 1),
(134, 34, 'Werken', 0),
(135, 34, 'Drinken', 0),
(136, 34, 'Lopen', 0),
(137, 35, 'Werken', 1),
(138, 35, 'Kopen', 0),
(139, 35, 'Rijden', 0),
(140, 35, 'Schrijven', 0),
(141, 36, 'Kopen', 1),
(142, 36, 'Verkopen', 0),
(143, 36, 'Werken', 0),
(144, 36, 'Lezen', 0),
(145, 37, 'Verkopen', 1),
(146, 37, 'Kopen', 0),
(147, 37, 'Rijden', 0),
(148, 37, 'Slapen', 0),
(149, 38, 'Rijden', 1),
(150, 38, 'Lopen', 0),
(151, 38, 'Lezen', 0),
(152, 38, 'Werken', 0),
(153, 39, 'Lezen', 1),
(154, 39, 'Schrijven', 0),
(155, 39, 'Rijden', 0),
(156, 39, 'Kopen', 0),
(157, 40, 'Schrijven', 1),
(158, 40, 'Lezen', 0),
(159, 40, 'Slapen', 0),
(160, 40, 'Lopen', 0),
(161, 41, 'Ik ga naar school', 1),
(162, 41, 'Ik ga naar huis', 0),
(163, 41, 'Ik werk vandaag', 0),
(164, 41, 'Ik ga eten', 0),
(165, 42, 'Ik werk vandaag', 1),
(166, 42, 'Ik slaap vandaag', 0),
(167, 42, 'Ik lees vandaag', 0),
(168, 42, 'Ik woon vandaag', 0),
(169, 43, 'Het is koud', 1),
(170, 43, 'Het is warm', 0),
(171, 43, 'Het is laat', 0),
(172, 43, 'Het is vroeg', 0),
(173, 44, 'Het is warm', 1),
(174, 44, 'Het is koud', 0),
(175, 44, 'Het is donker', 0),
(176, 44, 'Het is nat', 0),
(177, 45, 'Ik begrijp niet', 1),
(178, 45, 'Ik begrijp alles', 0),
(179, 45, 'Ik spreek niet', 0),
(180, 45, 'Ik werk niet', 0),
(181, 46, 'Kun je helpen?', 1),
(182, 46, 'Kun je lezen?', 0),
(183, 46, 'Kun je slapen?', 0),
(184, 46, 'Kun je koken?', 0),
(185, 47, 'Hoe laat is het?', 1),
(186, 47, 'Wat is je naam?', 0),
(187, 47, 'Waar woon je?', 0),
(188, 47, 'Hoe oud ben je?', 0),
(189, 48, 'Ik hou van jou', 1),
(190, 48, 'Ik zie jou', 0),
(191, 48, 'Ik hoor jou', 0),
(192, 48, 'Ik ken jou', 0),
(193, 49, 'Ik wil eten', 1),
(194, 49, 'Ik wil slapen', 0),
(195, 49, 'Ik wil lezen', 0),
(196, 49, 'Ik wil drinken', 0),
(197, 50, 'Tot morgen', 1),
(198, 50, 'Tot straks', 0),
(199, 50, 'Tot vanavond', 0),
(200, 50, 'Tot gisteren', 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gebruikers`
--

CREATE TABLE `gebruikers` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `wachtwoord` varchar(255) NOT NULL,
  `datum_registratie` date NOT NULL DEFAULT curdate(),
  `total_score` int(11) NOT NULL DEFAULT 0,
  `xp` int(11) NOT NULL DEFAULT 0,
  `level` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `gebruikers`
--

INSERT INTO `gebruikers` (`user_id`, `username`, `email`, `wachtwoord`, `datum_registratie`, `total_score`, `xp`, `level`) VALUES
(2, '123test', '123test@gmail.com', '$2y$10$fkns7q4lCpagJhM9kPtaLekeqilF5baQkBBR6XOtHc2n0asRE9Umm', '2026-03-16', 5, 50, 2),
(3, 'test221', 'test221@gmail.com', '$2y$10$NmkiHvCah8eiYS1GkSkY/eGIoibJJuXLUrt7T0uQCE5a1chRTOjDy', '2026-03-17', 0, 380, 4);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `levels`
--

CREATE TABLE `levels` (
  `level_id` int(11) NOT NULL,
  `question_amount` int(11) NOT NULL DEFAULT 5,
  `xp_reward` int(11) NOT NULL DEFAULT 50
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `levels`
--

INSERT INTO `levels` (`level_id`, `question_amount`, `xp_reward`) VALUES
(1, 10, 50),
(2, 10, 75),
(3, 10, 100),
(4, 10, 125),
(5, 10, 150);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `scores`
--

CREATE TABLE `scores` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `score` int(11) NOT NULL,
  `totaal` int(11) NOT NULL,
  `gespeeld_op` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `scores`
--

INSERT INTO `scores` (`id`, `user_id`, `score`, `totaal`, `gespeeld_op`) VALUES
(1, 2, 5, 10, '2026-03-16 16:22:12'),
(2, 3, 9, 10, '2026-03-17 21:29:17'),
(3, 3, 10, 10, '2026-03-17 21:30:02'),
(4, 3, 10, 10, '2026-03-17 21:34:05'),
(5, 3, 10, 10, '2026-03-17 21:38:47'),
(6, 3, 10, 10, '2026-03-17 21:45:05'),
(7, 3, 10, 10, '2026-03-17 21:47:03'),
(8, 3, 10, 10, '2026-03-17 21:49:11'),
(9, 3, 8, 10, '2026-03-18 13:07:32'),
(10, 3, 10, 10, '2026-03-18 13:12:53');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `vragen`
--

CREATE TABLE `vragen` (
  `question_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL DEFAULT 1,
  `difficulty` varchar(20) NOT NULL DEFAULT 'makkelijk',
  `question_text` text NOT NULL,
  `xp_reward` int(11) NOT NULL DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `vragen`
--

INSERT INTO `vragen` (`question_id`, `level_id`, `difficulty`, `question_text`, `xp_reward`) VALUES
(1, 1, 'makkelijk', 'Wat betekent \'hei\'?', 10),
(2, 1, 'makkelijk', 'Wat is het Noorse woord voor \'appel\'?', 10),
(3, 1, 'makkelijk', 'Wat betekent \'takk\'?', 10),
(4, 1, 'makkelijk', 'Wat is het Noorse woord voor \'hond\'?', 10),
(5, 1, 'makkelijk', 'Wat betekent \'god morgen\'?', 10),
(6, 1, 'makkelijk', 'Wat betekent \'vann\'?', 10),
(7, 1, 'makkelijk', 'Wat is het Noorse woord voor \'school\'?', 10),
(8, 1, 'makkelijk', 'Wat betekent \'stor\'?', 10),
(9, 1, 'makkelijk', 'Wat is het Noorse woord voor \'vriend\'?', 10),
(10, 1, 'makkelijk', 'Wat betekent \'farger\'?', 10),
(11, 2, 'makkelijk', 'Wat betekent \'nei\'?', 10),
(12, 2, 'makkelijk', 'Wat is het Noorse woord voor \'huis\'?', 10),
(13, 2, 'makkelijk', 'Wat betekent \'ja\'?', 10),
(14, 2, 'makkelijk', 'Wat is het Noorse woord voor \'kat\'?', 10),
(15, 2, 'makkelijk', 'Wat betekent \'god natt\'?', 10),
(16, 2, 'makkelijk', 'Wat is het Noorse woord voor \'vogel\'?', 10),
(17, 2, 'makkelijk', 'Wat betekent \'bil\'?', 10),
(18, 2, 'makkelijk', 'Wat is het Noorse woord voor \'vis\'?', 10),
(19, 2, 'makkelijk', 'Wat betekent \'bok\'?', 10),
(20, 2, 'makkelijk', 'Wat is het Noorse woord voor \'melk\'?', 10),
(21, 3, 'makkelijk', 'Wat betekent \'Hvordan går det?\'?', 10),
(22, 3, 'makkelijk', 'Wat betekent \'Jeg er bra\'?', 10),
(23, 3, 'makkelijk', 'Wat betekent \'Hva heter du?\'?', 10),
(24, 3, 'makkelijk', 'Wat betekent \'Jeg heter...\'?', 10),
(25, 3, 'makkelijk', 'Wat betekent \'Hvor bor du?\'?', 10),
(26, 3, 'makkelijk', 'Wat betekent \'Jeg bor i Nederland\'?', 10),
(27, 3, 'makkelijk', 'Wat betekent \'Hvor gammel er du?\'?', 10),
(28, 3, 'makkelijk', 'Wat betekent \'Jeg er 20 år\'?', 10),
(29, 3, 'makkelijk', 'Wat betekent \'Snakker du engelsk?\'?', 10),
(30, 3, 'makkelijk', 'Wat betekent \'Ja litt\'?', 10),
(31, 4, 'makkelijk', 'Wat betekent \'gå\'?', 10),
(32, 4, 'makkelijk', 'Wat betekent \'spise\'?', 10),
(33, 4, 'makkelijk', 'Wat betekent \'drikke\'?', 10),
(34, 4, 'makkelijk', 'Wat betekent \'sove\'?', 10),
(35, 4, 'makkelijk', 'Wat betekent \'arbeide\'?', 10),
(36, 4, 'makkelijk', 'Wat betekent \'kjøpe\'?', 10),
(37, 4, 'makkelijk', 'Wat betekent \'selge\'?', 10),
(38, 4, 'makkelijk', 'Wat betekent \'kjøre\'?', 10),
(39, 4, 'makkelijk', 'Wat betekent \'lese\'?', 10),
(40, 4, 'makkelijk', 'Wat betekent \'skrive\'?', 10),
(41, 5, 'makkelijk', 'Wat betekent \'Jeg går på skolen\'?', 10),
(42, 5, 'makkelijk', 'Wat betekent \'Jeg jobber i dag\'?', 10),
(43, 5, 'makkelijk', 'Wat betekent \'Det er kaldt\'?', 10),
(44, 5, 'makkelijk', 'Wat betekent \'Det er varmt\'?', 10),
(45, 5, 'makkelijk', 'Wat betekent \'Jeg forstår ikke\'?', 10),
(46, 5, 'makkelijk', 'Wat betekent \'Kan du hjelpe?\'?', 10),
(47, 5, 'makkelijk', 'Wat betekent \'Hva er klokken?\'?', 10),
(48, 5, 'makkelijk', 'Wat betekent \'Jeg elsker deg\'?', 10),
(49, 5, 'makkelijk', 'Wat betekent \'Jeg vil spise\'?', 10),
(50, 5, 'makkelijk', 'Wat betekent \'Vi ses i morgen\'?', 10);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `antwoordopties`
--
ALTER TABLE `antwoordopties`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexen voor tabel `gebruikers`
--
ALTER TABLE `gebruikers`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexen voor tabel `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`level_id`);

--
-- Indexen voor tabel `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `vragen`
--
ALTER TABLE `vragen`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `level_id` (`level_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `antwoordopties`
--
ALTER TABLE `antwoordopties`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT voor een tabel `gebruikers`
--
ALTER TABLE `gebruikers`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `levels`
--
ALTER TABLE `levels`
  MODIFY `level_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT voor een tabel `vragen`
--
ALTER TABLE `vragen`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `antwoordopties`
--
ALTER TABLE `antwoordopties`
  ADD CONSTRAINT `antwoordopties_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `vragen` (`question_id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `gebruikers` (`user_id`) ON DELETE SET NULL;

--
-- Beperkingen voor tabel `vragen`
--
ALTER TABLE `vragen`
  ADD CONSTRAINT `vragen_ibfk_1` FOREIGN KEY (`level_id`) REFERENCES `levels` (`level_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
