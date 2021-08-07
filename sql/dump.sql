-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 07 2021 г., 21:20
-- Версия сервера: 8.0.19
-- Версия PHP: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `diplom_blog`
--

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `message`
--

CREATE TABLE `message` (
  `id` int NOT NULL,
  `id_sender` int NOT NULL,
  `id_recipient` int NOT NULL,
  `body` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `post`
--

CREATE TABLE `post` (
  `id` int NOT NULL,
  `id_creator` int NOT NULL,
  `heading` varchar(30) NOT NULL,
  `body` text NOT NULL,
  `id_status` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `post`
--

INSERT INTO `post` (`id`, `id_creator`, `heading`, `body`, `id_status`, `created_at`, `updated_at`) VALUES
(1, 8, 'tetssetsdf', 'jsonData/posts/610ec6ebe76d21628358379.json', 1, '2021-08-07 20:46:19', '2021-08-07 20:46:19'),
(2, 8, 'tetssetsdfsdf', 'jsonData/posts/610ec6f0cff9d1628358384.json', 1, '2021-08-07 20:46:24', '2021-08-07 20:46:24'),
(3, 8, 'sdfsdfsdgsdg', 'jsonData/posts/610ec6f40bc441628358388.json', 1, '2021-08-07 20:46:28', '2021-08-07 20:46:28'),
(4, 8, 'dsfgdsgdsg', 'jsonData/posts/610ec6f6d73dc1628358390.json', 1, '2021-08-07 20:46:30', '2021-08-07 20:46:30'),
(5, 8, 'dsfg dsfg ds', 'jsonData/posts/610ec6f9cc26f1628358393.json', 1, '2021-08-07 20:46:33', '2021-08-07 20:46:33'),
(6, 8, 'f 324 4f3 3f dz   sd fs f sd', 'jsonData/posts/610ec703398231628358403.json', 1, '2021-08-07 20:46:43', '2021-08-07 20:46:43');

-- --------------------------------------------------------

--
-- Структура таблицы `post_category`
--

CREATE TABLE `post_category` (
  `id_post` int NOT NULL,
  `id_category` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `post_status`
--

CREATE TABLE `post_status` (
  `id` int NOT NULL,
  `name` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `post_status`
--

INSERT INTO `post_status` (`id`, `name`) VALUES
(1, 'Создан');

-- --------------------------------------------------------

--
-- Структура таблицы `post_tag`
--

CREATE TABLE `post_tag` (
  `id_post` int NOT NULL,
  `id_tag` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tag`
--

CREATE TABLE `tag` (
  `id` int NOT NULL,
  `name` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `verify_email` bit(1) NOT NULL DEFAULT b'0',
  `login` varchar(20) NOT NULL,
  `password` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `about` text NOT NULL,
  `id_role` int NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `email`, `verify_email`, `login`, `password`, `about`, `id_role`, `created_at`) VALUES
(8, 'admin@das.ru', b'0', 'admin', '$2y$10$IiXSs0..CQ.l.aATusZHzecj2JbQVx5xV/Eix4HOXRLyxugjT1eem', 'dasss', 1, '2021-08-05 21:17:27'),
(9, '2@dfs', b'0', '1231234224324242', '$2y$10$qW4ZW4pd0URquCeLjNlb/eUHOO5ahf90YDlro3JYFpTmzylXKsWeW', '234', 1, '2021-08-05 21:53:48'),
(10, '123242342', b'0', '123123', '$2y$10$bFQ/AN5RLN65VFeKmB.Qb.x9rTb60KOOETXDXumGjckPlI3hK7tge', '123', 1, '2021-08-05 21:54:05'),
(11, '42343222@dfs', b'0', 'ffffffffff', '$2y$10$O/tWN9AeMEGqn28zGeRb8OD1n8FNz74ovd7QIIAs39EpDm6JeccQq', 'sdf', 1, '2021-08-05 21:56:47'),
(12, 'admin@das.ru12', b'0', '12321423242342342423', '$2y$10$6VDjCBfxtzIluUUEuaeYU.SAUUqhWTrc1.gzZmkS3DN6hgVDm4i9i', '123', 1, '2021-08-05 21:59:01'),
(13, '2342342', b'0', '123124234', '$2y$10$eRnWNn6wMNqXSREYHPG.KOQptm4spOM7xQyZRoFgqA/atwwvyiFJK', '2342', 1, '2021-08-07 13:12:46'),
(14, 'проапро', b'0', 'рпвпорапро', '$2y$10$ICPmZU81LFsSNyOiJva5Uu361/iNlpgLbDcamLymQ/PJoBzr6JvUm', 'пропао', 1, '2021-08-07 13:14:50'),
(15, 'testwset', b'0', 'testestset', '$2y$10$Qk24.vGRME981PxR1O2gpeduv/IFSaGcOkujnIpTrUMxBboBNb9fa', 'sf', 1, '2021-08-07 13:16:13'),
(16, 'testest', b'0', 'testset', '$2y$10$FjJwB/hG.COpJAC7ZjMEouRmqQhBLVOzB/CcgQvYhs38GU.GyU7FK', 'sdf', 1, '2021-08-07 13:17:22');

-- --------------------------------------------------------

--
-- Структура таблицы `user_role`
--

CREATE TABLE `user_role` (
  `id` int NOT NULL,
  `name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_role`
--

INSERT INTO `user_role` (`id`, `name`) VALUES
(1, 'user'),
(2, 'moderator'),
(3, 'admin');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_sender` (`id_sender`,`id_recipient`),
  ADD KEY `id_recipient` (`id_recipient`);

--
-- Индексы таблицы `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_creator` (`id_creator`),
  ADD KEY `id_status` (`id_status`);

--
-- Индексы таблицы `post_category`
--
ALTER TABLE `post_category`
  ADD KEY `id_post` (`id_post`,`id_category`),
  ADD KEY `id_category` (`id_category`);

--
-- Индексы таблицы `post_status`
--
ALTER TABLE `post_status`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `post_tag`
--
ALTER TABLE `post_tag`
  ADD KEY `id_post` (`id_post`,`id_tag`),
  ADD KEY `id_tag` (`id_tag`);

--
-- Индексы таблицы `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_role` (`id_role`);

--
-- Индексы таблицы `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `message`
--
ALTER TABLE `message`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `post`
--
ALTER TABLE `post`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `post_status`
--
ALTER TABLE `post_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`id_sender`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`id_recipient`) REFERENCES `user` (`id`);

--
-- Ограничения внешнего ключа таблицы `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`id_status`) REFERENCES `post_status` (`id`);

--
-- Ограничения внешнего ключа таблицы `post_category`
--
ALTER TABLE `post_category`
  ADD CONSTRAINT `post_category_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `post_category_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `post` (`id`);

--
-- Ограничения внешнего ключа таблицы `post_tag`
--
ALTER TABLE `post_tag`
  ADD CONSTRAINT `post_tag_ibfk_1` FOREIGN KEY (`id_tag`) REFERENCES `tag` (`id`),
  ADD CONSTRAINT `post_tag_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `post` (`id`);

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `user_role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
