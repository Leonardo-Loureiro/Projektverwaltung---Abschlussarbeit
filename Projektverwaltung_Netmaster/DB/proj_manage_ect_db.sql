-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 19. Apr 2022 um 14:42
-- Server-Version: 10.4.17-MariaDB
-- PHP-Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `proj_manage_ect_db`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `projects`
--

CREATE TABLE `projects` (
  `proj_id` int(10) NOT NULL,
  `p_name` varchar(100) NOT NULL,
  `p_creationdate` datetime NOT NULL DEFAULT current_timestamp(),
  `p_aimdate` datetime NOT NULL,
  `p_comments` varchar(255) DEFAULT NULL,
  `t_lastupdated` date DEFAULT NULL,
  `project_manager` int(10) NOT NULL,
  `status_id` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `projects`
--

INSERT INTO `projects` (`proj_id`, `p_name`, `p_creationdate`, `p_aimdate`, `p_comments`, `t_lastupdated`, `project_manager`, `status_id`) VALUES
(1, 'NetMaster (Schweiz) AG', '2022-04-11 14:11:06', '2022-05-05 12:00:00', NULL, '2022-04-13', 1, 3),
(2, 'Metzgerei Zürich', '2022-04-19 09:35:33', '2022-04-25 09:35:14', NULL, NULL, 1, 2),
(3, 'Polizei Zürich', '2022-04-19 09:36:35', '2022-05-30 10:30:00', NULL, NULL, 4, 5),
(4, 'Blumen Zürich GmbH', '2022-04-19 09:56:56', '2022-05-24 09:56:31', NULL, NULL, 4, 4),
(5, 'Druckerei Basel AG', '2022-04-19 10:00:18', '2022-10-12 09:59:48', NULL, NULL, 1, 3),
(38, 'Marketingmaster', '2022-04-19 10:17:46', '2024-05-30 10:30:00', NULL, NULL, 4, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `project_status`
--

CREATE TABLE `project_status` (
  `pj_status_id` int(10) NOT NULL,
  `pj_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `project_status`
--

INSERT INTO `project_status` (`pj_status_id`, `pj_status`) VALUES
(1, 'Neu'),
(2, 'In Arbeit'),
(3, 'Pause'),
(4, 'Abgebrochen'),
(5, 'Erledigt');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `system_rolls`
--

CREATE TABLE `system_rolls` (
  `id` int(10) NOT NULL,
  `r_rolls` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `system_rolls`
--

INSERT INTO `system_rolls` (`id`, `r_rolls`) VALUES
(1, 'Projektleiter'),
(2, 'Projektmitarbeiter'),
(3, 'Berater');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(10) NOT NULL,
  `t_name` varchar(100) NOT NULL,
  `t_description` varchar(255) NOT NULL,
  `t_creationdate` datetime NOT NULL DEFAULT current_timestamp(),
  `t_lastupdated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `t_aimdate` datetime NOT NULL,
  `t_comments` varchar(255) DEFAULT NULL,
  `project_staff` int(10) NOT NULL,
  `status_id` int(10) NOT NULL DEFAULT 1,
  `project_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Trigger `tasks`
--
DELIMITER $$
CREATE TRIGGER `update_projects_lastupdated` AFTER UPDATE ON `tasks` FOR EACH ROW BEGIN

UPDATE projects SET t_lastupdated=CURRENT_TIMESTAMP where proj_id= New.project_id; 

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `task_status`
--

CREATE TABLE `task_status` (
  `ta_status_id` int(10) NOT NULL,
  `ta_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `task_status`
--

INSERT INTO `task_status` (`ta_status_id`, `ta_status`) VALUES
(1, 'Neu'),
(2, 'To Do'),
(3, 'In Arbeit'),
(4, 'Pause'),
(5, 'Abgebrochen'),
(6, '@Kunde'),
(7, '@Kundenberater'),
(8, 'Erledigt'),
(9, 'Abgeschlossen');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_login`
--

CREATE TABLE `user_login` (
  `user_id` int(10) NOT NULL,
  `l_username` varchar(50) NOT NULL,
  `l_password` varchar(100) NOT NULL,
  `l_roll` int(10) NOT NULL,
  `l_attempts` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `user_login`
--

INSERT INTO `user_login` (`user_id`, `l_username`, `l_password`, `l_roll`, `l_attempts`) VALUES
(1, 'Leonardo', '$2y$10$aTYX2Ve73TgCDyxBnOwiYuZl7tiv.Kd37HAHKMc1D886KIE1p/J/S', 1, 0),
(2, 'shaadhanaa', '$2y$10$q5ddnJYiazXqkMbH.21GuOOR3pnMcwsQWIQlpWyMVvY', 2, 1),
(3, 'Leonard', '$2y$10$6El.o5YNHboLArTKkRpznetvjo/2/XZJYnpDnLlJBtFWXOflNizzm', 3, 0),
(4, 'Jeevanthan', '$2y$10$m1wq0V6342pT54yW1okXeuvLPaOaLOOcQw7uv8yEU4qxvKnRnHxVi', 1, 0),
(5, 'Mathusjan', '$2y$10$DhdmuLxX8lIB48ehQt5sGO1c.RsiotGvEyvGNzLASJliAvRaMHsKG', 2, 0);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`proj_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `project_manager` (`project_manager`);

--
-- Indizes für die Tabelle `project_status`
--
ALTER TABLE `project_status`
  ADD PRIMARY KEY (`pj_status_id`);

--
-- Indizes für die Tabelle `system_rolls`
--
ALTER TABLE `system_rolls`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `project_staff` (`project_staff`),
  ADD KEY `status_id` (`status_id`) USING BTREE,
  ADD KEY `project_id` (`project_id`);

--
-- Indizes für die Tabelle `task_status`
--
ALTER TABLE `task_status`
  ADD PRIMARY KEY (`ta_status_id`);

--
-- Indizes für die Tabelle `user_login`
--
ALTER TABLE `user_login`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `l_roll` (`l_roll`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `projects`
--
ALTER TABLE `projects`
  MODIFY `proj_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT für Tabelle `project_status`
--
ALTER TABLE `project_status`
  MODIFY `pj_status_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `system_rolls`
--
ALTER TABLE `system_rolls`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT für Tabelle `task_status`
--
ALTER TABLE `task_status`
  MODIFY `ta_status_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT für Tabelle `user_login`
--
ALTER TABLE `user_login`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `project_status` (`pj_status_id`);

--
-- Constraints der Tabelle `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `task_status` (`ta_status_id`),
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`proj_id`),
  ADD CONSTRAINT `tasks_ibfk_3` FOREIGN KEY (`project_staff`) REFERENCES `user_login` (`user_id`);

--
-- Constraints der Tabelle `user_login`
--
ALTER TABLE `user_login`
  ADD CONSTRAINT `user_login_ibfk_1` FOREIGN KEY (`l_roll`) REFERENCES `system_rolls` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
