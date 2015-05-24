CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `category2highscore` (
  `category_id` int(11) NOT NULL,
  `highscore_id` int(11) NOT NULL,
  PRIMARY KEY (`category_id`,`highscore_id`),
  KEY `highscore_id` (`highscore_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `highscore` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playername` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `score` int(11) NOT NULL,
  `time` date NOT NULL,
  `duration` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `playername` (`playername`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=31 ;

CREATE TABLE IF NOT EXISTS `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `correct_answer` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `wrong_answer1` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `wrong_answer2` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `wrong_answer3` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `number_of_correct` int(11) NOT NULL DEFAULT '0',
  `number_of_wrong` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_question_category` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=27 ;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(62) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

INSERT INTO `user` (`id`, `name`, `password`) VALUES
(1, 'root', 'root21232f297a57a5a743894a0e4a801fc3');

ALTER TABLE `category2highscore`
  ADD CONSTRAINT `category2highscore_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `category2highscore_ibfk_2` FOREIGN KEY (`highscore_id`) REFERENCES `highscore` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `question`
  ADD CONSTRAINT `fk_question_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;