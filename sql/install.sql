CREATE TABLE IF NOT EXISTS `%s` (  `title` varchar(255) NOT NULL,  `url` varchar(255) NOT NULL,  `pos` int(11) NOT NULL,  `lang` char(2) NOT NULL,  `id` int(11) NOT NULL AUTO_INCREMENT, KEY (`id`))  DEFAULT CHARSET=utf8;
			
CREATE TABLE IF NOT EXISTS `%s` (  `title` varchar(255) NOT NULL DEFAULT 'Chaton CMS',  `desc` text NOT NULL,  `logo` varchar(255) NOT NULL,  `lang` char(2) NOT NULL DEFAULT 'en', `auth` varchar(255) NOT NULL,  `version` varchar(12) NOT NULL,  `multilingual` tinyint(4) NOT NULL DEFAULT '0',  `theme` varchar(255) NOT NULL, `languages` blob, `homepage` VARCHAR( 4 ) NOT NULL DEFAULT 'news',  `news_title` VARCHAR( 50 ) DEFAULT NULL, `salt` CHAR( 23 ) NOT NULL, `perpage` INT NOT NULL DEFAULT '5' )  DEFAULT CHARSET=utf8;
			
CREATE TABLE IF NOT EXISTS `%s` (  `title` varchar(255) NOT NULL,  `id` int(11) NOT NULL AUTO_INCREMENT,  `date` date NOT NULL,  `content` longtext NOT NULL,  `lang` char(2) NOT NULL DEFAULT 'en',  KEY `id` (`id`))   DEFAULT CHARSET=utf8;
			
CREATE TABLE IF NOT EXISTS `%s` (  `title` varchar(255) NOT NULL,  `content` longtext NOT NULL,  `pos` int(11) NOT NULL,  `id` int(11) NOT NULL AUTO_INCREMENT,  `lang` char(2) NOT NULL DEFAULT 'en', `show` BOOLEAN NOT NULL DEFAULT '1',  KEY (`id`))   DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `%s` (  `name` varchar(255) NOT NULL,  `enabled` tinyint(4) NOT NULL DEFAULT '0' ,  `installed` tinyint(4) NOT NULL DEFAULT '0')  DEFAULT CHARSET=utf8;
			
CREATE TABLE IF NOT EXISTS `%s` ( `param` varchar(100) NOT NULL, `value` longtext DEFAULT NULL, `lang` char(2) NOT NULL DEFAULT 'en' ) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `%s` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 ;
