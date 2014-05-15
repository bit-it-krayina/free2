-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 15, 2014 at 02:14 AM
-- Server version: 5.5.6
-- PHP Version: 5.2.17-0.dotdeb.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yas`
--

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `resource_id` int(11) DEFAULT NULL,
  `vote_id` int(11) DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `intro_text` longtext COLLATE utf8_unicode_ci,
  `full_text` longtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `allow_comments` tinyint(1) DEFAULT NULL,
  `view_count` int(11) NOT NULL,
  `layout` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_23A0E6672DCDAFC` (`vote_id`),
  KEY `IDX_23A0E66727ACA70` (`parent_id`),
  KEY `IDX_23A0E6682F1BAF4` (`language_id`),
  KEY `IDX_23A0E66A76ED395` (`user_id`),
  KEY `IDX_23A0E6689329D25` (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `article`
--


-- --------------------------------------------------------

--
-- Table structure for table `articles_categories`
--

CREATE TABLE IF NOT EXISTS `articles_categories` (
  `article_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`article_id`,`category_id`),
  KEY `IDX_DE004A0E7294869C` (`article_id`),
  KEY `IDX_DE004A0E12469DE2` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `articles_categories`
--


-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_64C19C1A76ED395` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `user_id`, `name`) VALUES
(1, NULL, 'Programming');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `text` longtext COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526C82F1BAF4` (`language_id`),
  KEY `IDX_9474526CA76ED395` (`user_id`),
  KEY `IDX_9474526C7294869C` (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `comment`
--


-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`friend_id`),
  KEY `IDX_21EE7069A76ED395` (`user_id`),
  KEY `IDX_21EE70696A5458E8` (`friend_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `friends`
--


-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `abbreviation` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `name`, `abbreviation`) VALUES
(1, 'English', 'en'),
(2, 'Français', 'fr'),
(3, 'Deutsch', 'de'),
(4, 'Español', 'es'),
(5, 'Italiano', 'it'),
(6, 'Български', 'bg'),
(7, 'Русский', 'ru');

-- --------------------------------------------------------

--
-- Table structure for table `privilege`
--

CREATE TABLE IF NOT EXISTS `privilege` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `permission_allow` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_87209A8789329D25` (`resource_id`),
  KEY `IDX_87209A87D60322AC` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=31 ;

--
-- Dumping data for table `privilege`
--

INSERT INTO `privilege` (`id`, `resource_id`, `role_id`, `name`, `permission_allow`) VALUES
(1, 5, 1, 'index', 1),
(2, 7, 1, 'index', 1),
(3, 7, 2, 'changePassword', 1),
(4, 7, 2, 'editProfile', 1),
(5, 7, 2, 'changeEmail', 1),
(6, 7, 1, 'forgottenPassword', 1),
(7, 7, 1, 'confirmEmail', 1),
(8, 7, 1, 'registrationSuccess', 1),
(9, 6, 1, 'login', 1),
(10, 6, 2, 'logout', 1),
(11, 6, 1, 'index', 1),
(12, 8, 1, NULL, 1),
(13, 9, 1, 'view', 1),
(14, 9, 2, 'vote', 1),
(15, 9, 3, 'index', 1),
(16, 9, 3, 'add', 1),
(17, 9, 3, 'edit', 1),
(18, 9, 3, 'delete', 1),
(19, 10, 3, NULL, 1),
(20, 11, 2, NULL, 1),
(21, 12, 3, NULL, 1),
(22, 13, 2, NULL, 1),
(23, 14, 2, 'uri', 1),
(24, 15, 1, 'index', 1),
(25, 1, 1, 'view', 1),
(26, 2, 1, 'view', 1),
(27, 3, 2, 'view', 1),
(28, 4, 3, 'view', 1),
(29, 6, 2, 'login', 0),
(30, 7, 2, 'index', 0);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `description` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `functions` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `done` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `topic_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `functions`, `done`, `topic_id`) VALUES
(1, 'Проект: Відкритий бюджет [#42]', 'ідея полягає у створенні сервісу, за допомогою якого можна було б відслідковувати бюджет країни з повною деталізацією до твого будинку.', '    повні інформаційні звіти державних установ: починаючи з головного бюджету країни, закінчуючи витратами місцевого жеку;      співставлення в часі - планували/витратили;      можливість формування онлайн запитів по закону про відкриту інформацію, та ведення історії наданих даних по цих звітах;      рейтинг найбільш прозорих міст, селищ та чиновників;      зрозуміла інфографіка та статті, які б роз''яснювали пересічному громадянину бюджетотворчій процес та його право впливати на розподіл своїх податків.', 'існує команда девелоперів та бізнес-аналітиків, що почала працювати над проектом: http://www.it-krayina.org.ua/openbudget/', 420190),
(2, 'Проект: Пряма демократія. [#21]', 'створення інструменту прямої демократії, а саме - можливості для кожного громадянина України впливати практично в реальному часі на законотворчий процесс та процесс реформування.', '    жорстка реєстрація користувачів (паспорт, ІНН тощо);      список питань, згідно якому, в форматі референдуму "так/ні", просто висловлює думку кожний пересічний громадянин (за наявності бажання впливати на процес). Список потрібно оновлювати, наприклад - раз на тиждень.      можливість обговорення непопулярних рішень; доведення у форматі суспільного далогу до населення необхідності таких рішень;      теоретично - можливість ветування суспільством окремих рішень. Наприклад, якщо рішення ветує більшість, держструктури забовязані в означений термін представити альтернативу законопроекту, детальне роз''яснення та аргументацію щодо доцільності кожного окремого пункту;      можливість інтегрування в таку систему розділу з просування кандидатів у владні органи через пряме голусування, такі "народні обранці", які найбільш підтримувані протягом певного довиборчого періоду станоляться автоматичними кандидатами на наступних виборах.', '    існує багато аналогічних систем в інших країніах;      декількома командами вже ведеться розробка прототипу системи.', 420188);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B6F7494EB6F7494E` (`question`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`id`, `question`) VALUES
(3, 'In what city did your father born?'),
(2, 'In what city did your mother born?'),
(4, 'In what city or town was your first job?'),
(1, 'What was your childhood phone number?');

-- --------------------------------------------------------

--
-- Table structure for table `resource`
--

CREATE TABLE IF NOT EXISTS `resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Dumping data for table `resource`
--

INSERT INTO `resource` (`id`, `name`) VALUES
(1, 'all'),
(2, 'Public Resource'),
(3, 'Private Resource'),
(4, 'Admin Resource'),
(5, 'Application\\Controller\\Index'),
(6, 'CsnUser\\Controller\\Index'),
(7, 'CsnUser\\Controller\\Registration'),
(8, 'CsnCms\\Controller\\Index'),
(9, 'CsnCms\\Controller\\Article'),
(10, 'CsnCms\\Controller\\Translation'),
(11, 'CsnCms\\Controller\\Comment'),
(12, 'CsnCms\\Controller\\Category'),
(13, 'CsnFileManager\\Controller\\Index'),
(14, 'Zend'),
(15, 'Application\\Controller\\Index');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL,
  `name` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'guest'),
(2, 'volunteer'),
(3, 'admin'),
(4, 'project manager'),
(5, 'coordinator');

-- --------------------------------------------------------

--
-- Table structure for table `roles_parents`
--

CREATE TABLE IF NOT EXISTS `roles_parents` (
  `role_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  PRIMARY KEY (`role_id`,`parent_id`),
  KEY `IDX_C70E6B91D60322AC` (`role_id`),
  KEY `IDX_C70E6B91727ACA70` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles_parents`
--

INSERT INTO `roles_parents` (`role_id`, `parent_id`) VALUES
(2, 1),
(3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE IF NOT EXISTS `state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_A393D2FBA393D2FB` (`state`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`id`, `state`) VALUES
(1, 'Disabled'),
(2, 'Enabled');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `answer` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registration_date` datetime DEFAULT NULL,
  `registration_token` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_confirmed` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  KEY `IDX_8D93D649D60322AC` (`role_id`),
  KEY `IDX_8D93D64982F1BAF4` (`language_id`),
  KEY `IDX_8D93D6495D83CC1` (`state_id`),
  KEY `IDX_8D93D6491E27F6BF` (`question_id`),
  KEY `search_idx` (`username`,`first_name`,`last_name`,`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `role_id`, `language_id`, `state_id`, `question_id`, `username`, `first_name`, `last_name`, `email`, `password`, `answer`, `picture`, `registration_date`, `registration_token`, `email_confirmed`) VALUES
(1, 3, 1, 2, 3, 'admin', 'test', 'test', 'imychkova@gmail.com', '$2y$10$Of/HbsCWhZEEzMU4A9TFR.ZqyCXWl5Rdw6Pm4kbmBTTZlHx8iB7VK', 'testtest', NULL, '2014-05-13 15:34:36', '56c87345d56bd242f498b14219655eb5', 1),
(2, 2, 1, 2, 3, 'tester', 'tester', 'mice', 'mice@devoffice.com', '$2y$10$2ei/cvXyHHkAEA5s0q3THuO6Kq.xnx8Y9sFKtIvOZkgr4YNEGmBRO', 'testtest', NULL, '2014-05-13 15:38:09', '92bd40079b63f35f4da18613862fbe6b', 1),
(3, 3, 1, 2, 3, 'admin1', 'test', 'test', 'mice-1@mice.devoffice.com', '$2y$10$KaV3ahP.V/I3vEnQ0mWT8O7YkBbv7dsnbJMZxiPDUj10Ao.gk9cNW', 'testtest', NULL, '2014-05-13 19:06:44', 'bb33af45f693110cbc2abe696d81c1ea', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'testuser', 'e16b2ab8d12314bf4efbd6203906ea6c'),
(2, 'new1', '202cb962ac59075b964b07152d234b70');

-- --------------------------------------------------------

--
-- Table structure for table `vote`
--

CREATE TABLE IF NOT EXISTS `vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `likes_count` int(11) NOT NULL,
  `dislikes_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `vote`
--


-- --------------------------------------------------------

--
-- Table structure for table `votes_users`
--

CREATE TABLE IF NOT EXISTS `votes_users` (
  `vote_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`vote_id`,`user_id`),
  KEY `IDX_9A4168C772DCDAFC` (`vote_id`),
  KEY `IDX_9A4168C7A76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `votes_users`
--


-- --------------------------------------------------------

--
-- Table structure for table `yas_lesson_order`
--

CREATE TABLE IF NOT EXISTS `yas_lesson_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `order_description` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `state_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `yas_lesson_order`
--

INSERT INTO `yas_lesson_order` (`id`, `teacher_id`, `customer_name`, `customer_email`, `order_description`, `created`, `state_id`) VALUES
(2, 1, 'Irina Mychkova', 'imychkova@gmail.com', 'First Order', '2013-12-10 12:13:18', 1),
(4, 1, 'Irina Mychkova', 'imychkova@gmail.com', 'S O', '2013-12-10 12:16:17', 1),
(5, 1, 'Irina Mychkova', 'imychkova@gmail.com', 'Third Order', '2013-12-10 12:21:19', 1),
(6, 1, 'Irina Mychkova', 'imychkova@gmail.com', 'F', '2013-12-10 12:26:25', 1),
(7, 1, 'name', 'email', 'ggggg', '2013-12-10 12:32:52', 1),
(8, 1, 'Irina Mychkova', 'imychkova@gmail.com', 'First SMTP message', '2013-12-14 08:35:03', 1),
(9, 1, 'Irina Mychkova', 'imychkova@gmail.com', 'f', '2013-12-14 08:58:40', 1),
(10, 1, 'Irina Mychkova', 'imychkova@gmail.com', 'f', '2013-12-14 09:00:18', 1),
(11, 1, 'name', 'imychkova@gmail.com', 'eeee', '2013-12-14 09:03:06', 1),
(12, 1, 'Irina Mychkova', 'imychkova@gmail.com', 'ddddd', '2013-12-14 09:06:00', 1),
(13, 1, 'Irina Mychkova', 'imychkova@gmail.com', 'Добрый день, я хочу заказать несколько уроков.', '2013-12-14 09:09:44', 1),
(14, 1, 'Irina Mychkova', 'imychkova@gmail.com', 'Хочу заказать урок', '2013-12-14 09:12:11', 1),
(15, 1, 'name', 'imychkova@gmail.com', 'добрый день, мне понравился ваш сайт, я хочу заказать урок', '2013-12-14 09:13:33', 1),
(16, 1, 'Ирина ', 'imychkova@gmail.com', 'Форма вакансий', '2013-12-22 10:49:27', 1),
(17, 1, 'Ирина ', 'imychkova@gmail.com', 'Форма вакансий', '2013-12-22 10:55:27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `yas_order_state`
--

CREATE TABLE IF NOT EXISTS `yas_order_state` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`(255))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `yas_order_state`
--

INSERT INTO `yas_order_state` (`id`, `name`) VALUES
(1, 'new'),
(2, 'closed'),
(3, 'in progress'),
(4, 'rejected');

-- --------------------------------------------------------

--
-- Table structure for table `yas_review`
--

CREATE TABLE IF NOT EXISTS `yas_review` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reviewer` varchar(255) NOT NULL,
  `review_content` varchar(255) NOT NULL,
  `review_date` datetime NOT NULL,
  `moderated` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `yas_review`
--

INSERT INTO `yas_review` (`id`, `reviewer`, `review_content`, `review_date`, `moderated`) VALUES
(1, 'T. Luba', 'I am satisfied.', '2013-12-05 16:54:33', 1),
(2, 'T. Luba', 'I am not satisfied.', '2013-12-07 16:54:33', 0);

-- --------------------------------------------------------

--
-- Table structure for table `yas_teacher`
--

CREATE TABLE IF NOT EXISTS `yas_teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `imageUrl` varchar(255) NOT NULL,
  `urlName` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `yas_teacher`
--

INSERT INTO `yas_teacher` (`id`, `name`, `description`, `imageUrl`, `urlName`) VALUES
(1, 'Ярослава Солтивская', 'Преподаватель ВНЗ с 5-летним стажем. Учитель немецкого языка', 'img/yas/Soltivska_Foto.jpg', 'yaroslava-soltivskaja'),
(2, 'Зоя Новикова', 'Преподаватель ВНЗ с 50-летним стажем', '', 'zoja-novikova');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `FK_23A0E66727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `FK_23A0E6672DCDAFC` FOREIGN KEY (`vote_id`) REFERENCES `vote` (`id`),
  ADD CONSTRAINT `FK_23A0E6682F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`),
  ADD CONSTRAINT `FK_23A0E6689329D25` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`id`),
  ADD CONSTRAINT `FK_23A0E66A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `articles_categories`
--
ALTER TABLE `articles_categories`
  ADD CONSTRAINT `FK_DE004A0E12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_DE004A0E7294869C` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`);

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `FK_64C19C1A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C7294869C` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `FK_9474526C82F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`),
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `FK_21EE70696A5458E8` FOREIGN KEY (`friend_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_21EE7069A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `privilege`
--
ALTER TABLE `privilege`
  ADD CONSTRAINT `FK_87209A8789329D25` FOREIGN KEY (`resource_id`) REFERENCES `resource` (`id`),
  ADD CONSTRAINT `FK_87209A87D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Constraints for table `roles_parents`
--
ALTER TABLE `roles_parents`
  ADD CONSTRAINT `FK_C70E6B91727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `role` (`id`),
  ADD CONSTRAINT `FK_C70E6B91D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D6491E27F6BF` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`),
  ADD CONSTRAINT `FK_8D93D6495D83CC1` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`),
  ADD CONSTRAINT `FK_8D93D64982F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`),
  ADD CONSTRAINT `FK_8D93D649D60322AC` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Constraints for table `votes_users`
--
ALTER TABLE `votes_users`
  ADD CONSTRAINT `FK_9A4168C772DCDAFC` FOREIGN KEY (`vote_id`) REFERENCES `vote` (`id`),
  ADD CONSTRAINT `FK_9A4168C7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
