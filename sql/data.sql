-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 27 2021 г., 21:13
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
(1, 'Временная'),
(2, 'Тестовая'),
(3, 'Программирование');

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

-- --------------------------------------------------------

--
-- Структура таблицы `post`
--

CREATE TABLE `post` (
  `id_post` int NOT NULL,
  `id_category` int DEFAULT '1',
  `id_creator` int NOT NULL,
  `id_status` int NOT NULL,
  `heading` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `description` text NOT NULL,
  `body` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `post`
--

INSERT INTO `post` (`id_post`, `id_category`, `id_creator`, `id_status`, `heading`, `description`, `body`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 'Первый тестовый пост)', 'Кратко пост просто тестовый и не несет какой либо информативности, но если интересно можете заглянуть посмотреть)', '61794fa2e1b741635340194', '2021-10-27 16:09:54', '2021-10-27 16:09:54'),
(2, 3, 1, 1, 'Можно ли взломать AJAX?', 'Итак, сегодня у меня был очень хороший опыт работы с моими встроенными системами. Какой-то парень &quot;hacked&quot; все и сказал, что это проблема ajax. Вот что он мне сказал:', '617986f0cd1b71635354352', '2021-10-27 20:05:52', '2021-10-27 20:05:52'),
(3, 3, 1, 1, 'Можно ли взломать AJAX 2?', 'Продолжаем хакакать', '617987366a7b31635354422', '2021-10-27 20:07:02', '2021-10-27 20:07:02'),
(4, 2, 1, 1, 'Тестовый пост 1', 'Тестовый пост для массовки', '61798ab7215651635355319', '2021-10-27 20:21:59', '2021-10-27 20:21:59'),
(5, 2, 1, 1, 'Тестовый пост 2', 'Тестовый пост для массовкиТестовый пост для массовкиТестовый пост для массовкиТестовый пост для массовки', '61798aee08d741635355374', '2021-10-27 20:22:54', '2021-10-27 20:22:54'),
(6, 3, 1, 1, '«Взломать» за 60 секунд или карантинное безумие', 'Небольшая история о неадекватном заказчике и нахождении уязвимости на сайте за 1 минуту.', '61798b4e4f6c81635355470', '2021-10-27 20:24:30', '2021-10-27 20:24:30'),
(7, 1, 1, 1, 'JavaScript-разработчик', 'JavaScript – это язык программирования, являющийся в настоящее время практически самым популярным средством для создания сайтов. А свое представительство в сети интернет сегодня желают иметь практически все, от больших транснациональных корпораций и до мелких компаний, вплоть до индивидуальных предпринимателей. И естественно, что многие организации хотят видеть в штате у себя специалистов в области JavaScript. Само собой, таким специалистам готовы платить достойные зарплаты.', '61798ca26b0b41635355810', '2021-10-27 20:30:10', '2021-10-27 20:30:10'),
(8, 1, 2, 1, '10 признаков того, что программирование не для вас', 'Часто на Reddit или Quora я вижу вопросы “Как понять, что я могу стать хорошим программистом?”. Такие вопросы о программировании часто всплывают, когда люди задумываются о переменах в карьере и хотят знать, что для этого нужно.', '6179955d1d9071635358045', '2021-10-27 21:07:25', '2021-10-27 21:07:25');

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
(2, 1),
(2, 3),
(3, 1),
(6, 3),
(7, 1),
(7, 3);

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
(1, 1, 'ceyoror152@forfity.com', 'admin', '$2y$10$7waQibRTQUp20cHiXR2pxeCcoLjutp.Yh82D8M0HsQFCzEIqUe.C6', 'Люблю создавать блоги!', '2021-10-27 16:00:49', NULL),
(2, 1, 'xiyiroh244@koldpak.com', 'tester', '$2y$10$izzc6n7z/dcthdFAHvl8qexcmQ8gmvUz7rWmF2gb7wigpXmfSkwvS', 'Тестер по жизни', '2021-10-27 21:05:01', NULL);

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
  MODIFY `id_category` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `comment`
--
ALTER TABLE `comment`
  MODIFY `id_comment` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `post`
--
ALTER TABLE `post`
  MODIFY `id_post` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
