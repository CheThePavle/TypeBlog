-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 27 2021 г., 15:56
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
-- База данных: `diplom`
--

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id_category` int NOT NULL,
  `name_category` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id_category`, `name_category`) VALUES
(1, 'Программирование'),
(2, 'Тестовый');

-- --------------------------------------------------------

--
-- Структура таблицы `comment`
--

CREATE TABLE `comment` (
  `id_comment` int NOT NULL,
  `id_post` int NOT NULL,
  `id_creator` int NOT NULL,
  `text` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `comment`
--

INSERT INTO `comment` (`id_comment`, `id_post`, `id_creator`, `text`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Классный пост, попробую сломать!)', '2021-10-24 12:18:45', '2021-10-24 12:18:45'),
(2, 1, 1, 'Не получилось(', '2021-10-24 12:19:33', '2021-10-24 12:19:33');

-- --------------------------------------------------------

--
-- Структура таблицы `post`
--

CREATE TABLE `post` (
  `id_post` int NOT NULL,
  `id_category` int DEFAULT NULL,
  `id_creator` int NOT NULL,
  `id_status` int NOT NULL,
  `heading` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `body` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `post`
--

INSERT INTO `post` (`id_post`, `id_category`, `id_creator`, `id_status`, `heading`, `description`, `body`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 'Как сломать Ajax?', 'Все таки возможно это сделать? Читайте ниже.', '610fcbb438a691628425140', '2021-10-24 11:46:42', '2021-10-24 11:46:42'),
(2, 1, 3, 1, 'Как сломать PHP?', 'Пытаемся это сделать вместе!)', '610fdd725e7e31628429682', '2021-10-24 11:48:16', '2021-10-24 11:48:16'),
(3, 2, 4, 1, 'sfsfsfsfs', 'sdfsfsdfsfsfsfsfsfsdfs', '61754578bf7861635075448', '2021-10-24 14:37:28', '2021-10-24 14:37:28'),
(4, 2, 4, 1, 'sdfsfsfasfsfsa', 'asdfasfs afd assf saf a', '617546f93b14a1635075833', '2021-10-24 14:43:53', '2021-10-24 14:43:53'),
(5, 2, 4, 1, '123134535534535', '4353f ds sg dg sd gsd ds', '6175473becca71635075899', '2021-10-24 14:44:59', '2021-10-24 14:44:59'),
(6, 2, 4, 1, 'TETETTETET', 'ds fs d s ds s  s s fs', '61754efc06b3c1635077884', '2021-10-24 15:18:04', '2021-10-24 15:18:04');

-- --------------------------------------------------------

--
-- Структура таблицы `post_status`
--

CREATE TABLE `post_status` (
  `id_status` int NOT NULL,
  `code` varchar(12) NOT NULL,
  `name` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `post_status`
--

INSERT INTO `post_status` (`id_status`, `code`, `name`) VALUES
(1, 'sent', 'Отправлен'),
(2, 'published', 'Опубликован'),
(3, 'rejected', 'Отклонен');

-- --------------------------------------------------------

--
-- Структура таблицы `post_tag`
--

CREATE TABLE `post_tag` (
  `id_post` int NOT NULL,
  `id_tag` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `post_tag`
--

INSERT INTO `post_tag` (`id_post`, `id_tag`) VALUES
(1, 1),
(1, 3),
(2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `tag`
--

CREATE TABLE `tag` (
  `id_tag` int NOT NULL,
  `name_tag` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tag`
--

INSERT INTO `tag` (`id_tag`, `name_tag`) VALUES
(1, 'Ajax'),
(2, 'PHP'),
(3, 'JS');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `id_role` int NOT NULL DEFAULT '1',
  `email` varchar(100) NOT NULL,
  `login` varchar(20) NOT NULL,
  `password` varchar(60) NOT NULL,
  `about` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id_user`, `id_role`, `email`, `login`, `password`, `about`, `created_at`, `updated_at`) VALUES
(1, 3, 'admin@ad.ru', 'admin', '$2y$10$qS3P/.A.wdGX3eZ3xqW64u/4rjJq//us9qQw6OmNqyhaq3sikmq3W', 'Хороший админ)', '2021-10-24 11:33:22', '2021-10-24 11:33:22'),
(2, 2, 'moderator@rospotrebnadzor.ru', 'moder', '$2y$10$MKRgkvAtktgglMMxxJSMLeBHm4kEyJEcAhouhoQwx23jFlSSzMQji', 'Строгий модератор', '2021-10-24 11:34:52', '2021-10-24 11:34:52'),
(3, 1, 'ivan@mail.ru', 'ivan228', '$2y$10$EJKx7cLLUZI5t6k/KHfI7.29K.XSprrDqa0bouyNkTAeRHde5oYwC', 'Первый пользователь этого блога, люблю делиться информацией в интернете!', '2021-10-24 11:35:50', '2021-10-24 11:35:50'),
(4, 1, 'ceyoror152@forfity.com', 'testtest', '$2y$10$K1huOBq8cQslX5Bme5rqsOmgGmTN8Upv5ceSS52SDBsSDy/W4LOc6', 'fdsf', '2021-10-24 11:52:28', NULL),
(5, 1, 'wahalof358@koldpak.com', 'tester', '$2y$10$WSbIgKksih5xFvqjX7.MbecI3JGfAA22OkbjH2CWKFbtWxzs5OebS', 'Ни о чем типок', '2021-10-27 10:50:59', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user_role`
--

CREATE TABLE `user_role` (
  `id_role` int NOT NULL,
  `code` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_role`
--

INSERT INTO `user_role` (`id_role`, `code`, `name`) VALUES
(1, 'user', 'Пользователь'),
(2, 'moder', 'Модератор'),
(3, 'admin', 'Администратор');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id_category`);

--
-- Индексы таблицы `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id_comment`),
  ADD KEY `id_post` (`id_post`,`id_creator`),
  ADD KEY `id_creator` (`id_creator`);

--
-- Индексы таблицы `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `id_category` (`id_category`,`id_creator`,`id_status`),
  ADD KEY `id_status` (`id_status`),
  ADD KEY `id_creator` (`id_creator`);

--
-- Индексы таблицы `post_status`
--
ALTER TABLE `post_status`
  ADD PRIMARY KEY (`id_status`);

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
  ADD PRIMARY KEY (`id_tag`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `id_role` (`id_role`);

--
-- Индексы таблицы `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id_role`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id_category` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `comment`
--
ALTER TABLE `comment`
  MODIFY `id_comment` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `post`
--
ALTER TABLE `post`
  MODIFY `id_post` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `post_status`
--
ALTER TABLE `post_status`
  MODIFY `id_status` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `tag`
--
ALTER TABLE `tag`
  MODIFY `id_tag` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id_role` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`id_creator`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`);

--
-- Ограничения внешнего ключа таблицы `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `category` (`id_category`),
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`id_status`) REFERENCES `post_status` (`id_status`),
  ADD CONSTRAINT `post_ibfk_3` FOREIGN KEY (`id_creator`) REFERENCES `user` (`id_user`);

--
-- Ограничения внешнего ключа таблицы `post_tag`
--
ALTER TABLE `post_tag`
  ADD CONSTRAINT `post_tag_ibfk_1` FOREIGN KEY (`id_tag`) REFERENCES `tag` (`id_tag`),
  ADD CONSTRAINT `post_tag_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`);

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `user_role` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
