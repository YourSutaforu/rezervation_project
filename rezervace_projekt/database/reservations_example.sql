-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Дек 29 2025 г., 23:14
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `reservations`
--

-- --------------------------------------------------------

--
-- Структура таблицы `room_res`
--

CREATE TABLE `room_res` (
  `room_name` mediumtext NOT NULL,
  `person_name` mediumtext NOT NULL,
  `person_id` int(7) NOT NULL,
  `res_time_start` tinytext NOT NULL,
  `res_time_end` tinytext NOT NULL,
  `res_date` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `room_res`
--

INSERT INTO `room_res` (`room_name`, `person_name`, `person_id`, `res_time_start`, `res_time_end`, `res_date`) VALUES
('velka mistnost 80^2', 'uzivatel_1', 1234567, '11:20', '13:35', '30.12.2025'),
('normální mistnost 50^2', 'uzivatel_1', 1234567, '11:20', '13:35', '30.12.2025'),
('malá mistnost 20^2', 'uzivatel_1', 1234567, '11:20', '13:35', '30.12.2025'),
('malá mistnost 20^2', 'uzivatel_2', 1234566, '11:20', '12:03', '31.12.2025'),
('velka mistnost 80^2', 'uzivatel_2', 1234565, '11:20', '13:03', '31.12.2025'),
('normální mistnost 50^2', 'uzivatel_2', 1234564, '11:20', '13:03', '31.12.2025');

-- --------------------------------------------------------

--
-- Структура таблицы `user_list`
--

CREATE TABLE `user_list` (
  `person_name` mediumtext NOT NULL,
  `person_password` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `user_list`
--

INSERT INTO `user_list` (`person_name`, `person_password`) VALUES
('uzivatel_1', '$2y$10$5Nv8WW3GHHOnu6Ime4e32ejUuxLSaUglBNzXBEmZ3MKxnLobMFNfm'),
('uzivatel_2', '$2y$10$PQADCcuq6RcVq69ATc4j9en2Y6ajYKEFE8Nm.jHkQBBcGlXdwsIBa');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
