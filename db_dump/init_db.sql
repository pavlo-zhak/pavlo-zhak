-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: mysql
-- Час створення: Лип 29 2020 р., 18:33
-- Версія сервера: 5.7.22
-- Версія PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `test_task`
--

-- --------------------------------------------------------

--
-- Структура таблиці `analytics`
--

CREATE TABLE `analytics` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `object` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `object_id` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `analytics`
--

INSERT INTO `analytics` (`id`, `user_id`, `object`, `action`, `object_id`, `amount`, `time_created`, `time_updated`) VALUES
(1, 1, 'wallet', 'replenishment', NULL, 75, '2020-07-29 18:31:16', '2020-07-29 18:31:16'),
(2, 1, 'boosterpack', 'buy', 2, 1, '2020-07-29 18:31:20', '2020-07-29 18:31:20');

-- --------------------------------------------------------

--
-- Структура таблиці `boosterpack`
--

CREATE TABLE `boosterpack` (
  `id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bank` decimal(10,2) NOT NULL DEFAULT '0.00',
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `boosterpack`
--

INSERT INTO `boosterpack` (`id`, `price`, `bank`, `time_created`, `time_updated`) VALUES
(1, '5.00', '0.00', '2020-03-30 00:17:28', '2020-07-29 17:15:33'),
(2, '20.00', '-17.00', '2020-03-30 00:17:28', '2020-07-29 18:31:20'),
(3, '50.00', '0.00', '2020-03-30 00:17:28', '2020-07-29 17:16:18');

-- --------------------------------------------------------

--
-- Структура таблиці `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `assign_id` int(10) UNSIGNED NOT NULL,
  `reply_id` int(11) DEFAULT NULL,
  `text` text NOT NULL,
  `likes` int(11) DEFAULT NULL,
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `comment`
--

INSERT INTO `comment` (`id`, `user_id`, `assign_id`, `reply_id`, `text`, `likes`, `time_created`, `time_updated`) VALUES
(1, 1, 1, NULL, 'Ну чо ассигн проверим', 4, '2020-03-27 21:39:44', '2020-07-29 13:16:00'),
(2, 1, 1, NULL, 'Второй коммент', 2, '2020-03-27 21:39:55', '2020-07-29 13:15:57'),
(3, 2, 1, NULL, 'Второй коммент от второго человека', 1, '2020-03-27 21:40:22', '2020-07-29 13:15:55'),
(4, 1, 2, NULL, 'Test comment for second post', 2, '2020-07-29 11:52:15', '2020-07-29 13:15:39'),
(5, 1, 2, NULL, 'Check comment workflow', 1, '2020-07-29 11:53:39', '2020-07-29 13:15:41'),
(6, 1, 1, NULL, '+1 комент', NULL, '2020-07-29 17:32:36', '2020-07-29 17:32:36');

-- --------------------------------------------------------

--
-- Структура таблиці `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `text` text NOT NULL,
  `img` varchar(1024) DEFAULT NULL,
  `likes` int(11) DEFAULT NULL,
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `post`
--

INSERT INTO `post` (`id`, `user_id`, `text`, `img`, `likes`, `time_created`, `time_updated`) VALUES
(1, 1, 'Тестовый постик 1', '/images/posts/1.png', 11, '2018-08-30 13:31:14', '2020-07-29 13:14:36'),
(2, 1, 'Печальный пост', '/images/posts/2.png', 30, '2018-10-11 01:33:27', '2020-07-29 13:15:50');

-- --------------------------------------------------------

--
-- Структура таблиці `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(60) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `personaname` varchar(50) NOT NULL DEFAULT '',
  `avatarfull` varchar(150) NOT NULL DEFAULT '',
  `rights` tinyint(4) NOT NULL DEFAULT '0',
  `likes_balance` int(11) DEFAULT '0',
  `wallet_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `wallet_total_refilled` decimal(10,2) NOT NULL DEFAULT '0.00',
  `wallet_total_withdrawn` decimal(10,2) NOT NULL DEFAULT '0.00',
  `time_created` datetime NOT NULL,
  `time_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `personaname`, `avatarfull`, `rights`, `likes_balance`, `wallet_balance`, `wallet_total_refilled`, `wallet_total_withdrawn`, `time_created`, `time_updated`) VALUES
(1, 'admin@niceadminmail.pl', '12345', 'AdminProGod', 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/96/967871835afdb29f131325125d4395d55386c07a_full.jpg', 0, 91, '265.00', '500.00', '235.00', '2019-07-26 01:53:54', '2020-07-29 18:31:20'),
(2, 'simpleuser@niceadminmail.pl', '123', 'simpleuser', 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/86/86a0c845038332896455a566a1f805660a13609b_full.jpg', 0, 0, '0.00', '0.00', '0.00', '2019-07-26 01:53:54', '2020-07-29 09:14:35');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `analytics`
--
ALTER TABLE `analytics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user_id`),
  ADD KEY `object_id` (`object_id`);

--
-- Індекси таблиці `boosterpack`
--
ALTER TABLE `boosterpack`
  ADD PRIMARY KEY (`id`);

--
-- Індекси таблиці `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Індекси таблиці `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Індекси таблиці `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `time_created` (`time_created`),
  ADD KEY `time_updated` (`time_updated`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `analytics`
--
ALTER TABLE `analytics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблиці `boosterpack`
--
ALTER TABLE `boosterpack`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблиці `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблиці `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблиці `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
