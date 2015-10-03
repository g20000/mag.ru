-- phpMyAdmin SQL Dump
-- version 4.3.10
-- http://www.phpmyadmin.net
--
-- Хост: localhost:3306
-- Время создания: Авг 04 2015 г., 18:28
-- Версия сервера: 5.5.42
-- Версия PHP: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `dpanel`
--

-- --------------------------------------------------------

--
-- Структура таблицы `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `text` longtext NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  `hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `chat_hashes`
--

CREATE TABLE `chat_hashes` (
  `id` int(11) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `drops2shippers`
--

CREATE TABLE `drops2shippers` (
  `id` int(11) NOT NULL,
  `drop_id` int(11) NOT NULL,
  `shipper_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` longtext NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `options`
--

CREATE TABLE `options` (
  `id` int(11) NOT NULL,
  `option` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `group_hash` varchar(255) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `drop_id` int(11) NOT NULL,
  `shipper_id` int(11) NOT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `labler_id` int(11) DEFAULT NULL,
  `action` longtext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `packages`
--

INSERT INTO `packages` (`id`, `group_hash`, `shop_id`, `drop_id`, `shipper_id`, `buyer_id`, `labler_id`, `action`) VALUES(1, '', 2, 2, 3, NULL, 4, '');

-- --------------------------------------------------------

--
-- Структура таблицы `pkg_admin_approve`
--

CREATE TABLE `pkg_admin_approve` (
  `id` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `pkg_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pkg_color`
--

CREATE TABLE `pkg_color` (
  `id` int(11) NOT NULL,
  `pkg_id` int(11) NOT NULL,
  `color` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pkg_description`
--

CREATE TABLE `pkg_description` (
  `id` int(11) NOT NULL,
  `pkg_id` int(11) NOT NULL,
  `item` text NOT NULL,
  `price` float NOT NULL,
  `currency` varchar(10) NOT NULL,
  `holder` varchar(255) NOT NULL,
  `sendtodropname` tinyint(1) NOT NULL DEFAULT '1',
  `moneydivide` enum('percent','fiftyfifty','forward','tobuyer') NOT NULL,
  `receivedate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pkg_notes`
--

CREATE TABLE `pkg_notes` (
  `id` int(11) NOT NULL,
  `pkg_id` int(11) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `note` longtext NOT NULL,
  `type` set('private','public','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pkg_statuses`
--

CREATE TABLE `pkg_statuses` (
  `id` int(11) NOT NULL,
  `pkg_id` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `status_text` text NOT NULL COMMENT 'new,accepted,todrop,ondrop,labeled,tubuyer,onbuyer,compleate'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `rank`
--

CREATE TABLE `rank` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `rankname` varchar(255) NOT NULL,
  `rankrights` longtext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `rank`
--

INSERT INTO `rank` (`id`, `title`, `rankname`, `rankrights`) VALUES(1, 'support', 'support', 'a:5:{s:14:"delete_threads";b:0;s:12:"edit_threads";b:0;s:15:"delete_comments";b:0;s:13:"edit_comments";b:0;s:9:"ban_users";b:0;}');
INSERT INTO `rank` (`id`, `title`, `rankname`, `rankrights`) VALUES(2, 'shipper', 'shipper', 'a:5:{s:14:"delete_threads";b:0;s:12:"edit_threads";b:0;s:15:"delete_comments";b:0;s:13:"edit_comments";b:0;s:9:"ban_users";b:0;}');
INSERT INTO `rank` (`id`, `title`, `rankname`, `rankrights`) VALUES(3, 'drop', 'drop', 'a:5:{s:14:"delete_threads";b:0;s:12:"edit_threads";b:0;s:15:"delete_comments";b:0;s:13:"edit_comments";b:0;s:9:"ban_users";b:0;}');
INSERT INTO `rank` (`id`, `title`, `rankname`, `rankrights`) VALUES(4, 'admin', 'admin', 'a:5:{s:14:"delete_threads";b:1;s:12:"edit_threads";b:1;s:15:"delete_comments";b:1;s:13:"edit_comments";b:1;s:9:"ban_users";b:1;}');
INSERT INTO `rank` (`id`, `title`, `rankname`, `rankrights`) VALUES(5, 'buyer', 'buyer', 'a:5:{s:14:"delete_threads";b:0;s:12:"edit_threads";b:0;s:15:"delete_comments";b:0;s:13:"edit_comments";b:0;s:9:"ban_users";b:0;}');
INSERT INTO `rank` (`id`, `title`, `rankname`, `rankrights`) VALUES(6, 'labler', 'labler', 'a:5:{s:14:"delete_threads";b:0;s:12:"edit_threads";b:0;s:15:"delete_comments";b:0;s:13:"edit_comments";b:0;s:9:"ban_users";b:0;}');

-- --------------------------------------------------------

--
-- Структура таблицы `shops`
--

CREATE TABLE `shops` (
  `id` int(11) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `shop_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `trackers`
--

CREATE TABLE `trackers` (
  `id` int(11) NOT NULL,
  `pkg_id` int(11) NOT NULL,
  `track_type` varchar(255) NOT NULL,
  `track_num` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `uploads`
--

CREATE TABLE `uploads` (
  `id` int(11) NOT NULL,
  `pkg_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `status_text` varchar(255) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'ник',
  `email` varchar(255) NOT NULL COMMENT 'мыло',
  `xmpp` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `state` varchar(30) NOT NULL,
  `city` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zip` varchar(15) NOT NULL,
  `cell` varchar(30) NOT NULL,
  `home` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'пасс',
  `color` varchar(7) NOT NULL COMMENT 'цвет',
  `status` enum('fresh','active','offline','free','troubles') NOT NULL,
  `rank` int(11) DEFAULT NULL,
  `deposit` int(11) NOT NULL COMMENT 'сколько денег',
  `registration_time` datetime NOT NULL COMMENT 'когда зареган',
  `last_time` datetime NOT NULL COMMENT 'когда последний раз был',
  `about` longtext NOT NULL COMMENT 'о себе',
  `sid` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `xmpp`, `first_name`, `middle_name`, `last_name`, `country`, `state`, `city`, `address`, `zip`, `cell`, `home`, `password`, `color`, `status`, `rank`, `deposit`, `registration_time`, `last_time`, `about`, `sid`) VALUES(1, 'admin', 'info@dpanel.ru', 'info@dpanel.ru', '', '', '', '', '', '', '', '', '', '', '76d80224611fc919a5d54f0ff9fba446', '#ffffff', '', 4, 0, '2014-10-12 00:00:00', '2015-08-04 16:22:59', '', 'b14e53e8a08b082ec42e4163137572a1');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `chat_hashes`
--
ALTER TABLE `chat_hashes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `drops2shippers`
--
ALTER TABLE `drops2shippers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pkg_admin_approve`
--
ALTER TABLE `pkg_admin_approve`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pkg_color`
--
ALTER TABLE `pkg_color`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pkg_description`
--
ALTER TABLE `pkg_description`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pkg_notes`
--
ALTER TABLE `pkg_notes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pkg_statuses`
--
ALTER TABLE `pkg_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `rank`
--
ALTER TABLE `rank`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `trackers`
--
ALTER TABLE `trackers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `chat_hashes`
--
ALTER TABLE `chat_hashes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `drops2shippers`
--
ALTER TABLE `drops2shippers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `options`
--
ALTER TABLE `options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `pkg_admin_approve`
--
ALTER TABLE `pkg_admin_approve`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `pkg_color`
--
ALTER TABLE `pkg_color`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `pkg_description`
--
ALTER TABLE `pkg_description`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `pkg_notes`
--
ALTER TABLE `pkg_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `pkg_statuses`
--
ALTER TABLE `pkg_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `rank`
--
ALTER TABLE `rank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `trackers`
--
ALTER TABLE `trackers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
