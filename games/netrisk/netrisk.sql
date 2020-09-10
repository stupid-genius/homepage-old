CREATE TABLE `chat` (
  `id` int(2) NOT NULL auto_increment,
  `game` int(11) NOT NULL default '0' COMMENT 'This determines if the chat is an in-game chat.',
  `title` varchar(255) NOT NULL default '',
  `from` text NOT NULL,
  `to` text NOT NULL,
  `messages` text NOT NULL,
  `admin` varchar(255) NOT NULL default '',
  `date` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
);

CREATE TABLE `config` (
  `id` int(1) NOT NULL default '1',
  `gamename` varchar(30) NOT NULL default 'game',
  `path` varchar(255) NOT NULL default '/netrisk/',
  `version` varchar(40) NOT NULL default '2.0',
  `admin_email` varchar(255) NOT NULL default '',
  UNIQUE KEY `id` (`id`)
);

CREATE TABLE `continents` (
  `id` int(3) unsigned NOT NULL default '0',
  `name` varchar(32) NOT NULL default '',
  `states` varchar(64) NOT NULL default '',
  `bonus` int(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

INSERT INTO `continents` VALUES (1, 'North America', '1,2,3,4,5,6,7,8,9,10', 5);
INSERT INTO `continents` VALUES (2, 'South America', '11,12,13,14', 2);
INSERT INTO `continents` VALUES (3, 'Europe', '21,22,23,24,25,26,27', 5);
INSERT INTO `continents` VALUES (4, 'Asia', '28,29,30,31,32,33,34,35,36,37,38,39', 7);
INSERT INTO `continents` VALUES (5, 'Africa', '15,16,17,18,19,20', 3);
INSERT INTO `continents` VALUES (6, 'Australia', '40,41,42,43', 2);

CREATE TABLE `countries` (
  `id` int(4) unsigned NOT NULL default '0',
  `name` varchar(32) NOT NULL default '',
  `adjacencies` varchar(32) NOT NULL default '',
  `card_type` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

INSERT INTO `countries` VALUES (1, 'Alaska', '2,3,36', 1);
INSERT INTO `countries` VALUES (2, 'NW Territory', '1,3,4,6', 2);
INSERT INTO `countries` VALUES (3, 'Alberta', '1,2,4,6,8', 3);
INSERT INTO `countries` VALUES (4, 'Oikiotoluk', '2,5,6,7', 1);
INSERT INTO `countries` VALUES (5, 'Greenland', '4,7,25', 2);
INSERT INTO `countries` VALUES (6, 'Ontario', '2,3,4,7,8,9', 3);
INSERT INTO `countries` VALUES (7, 'Quebec', '4,5,6,9', 1);
INSERT INTO `countries` VALUES (8, 'Western US', '3,6,9,10', 2);
INSERT INTO `countries` VALUES (9, 'Eastern US', '6,7,8,10', 3);
INSERT INTO `countries` VALUES (10, 'Central America', '8,9,11', 1);
INSERT INTO `countries` VALUES (11, 'Venezuela', '10,12,14', 2);
INSERT INTO `countries` VALUES (12, 'Peru', '11,13,14', 3);
INSERT INTO `countries` VALUES (13, 'Argentina', '12,14', 1);
INSERT INTO `countries` VALUES (14, 'Brazil', '11,12,13,15', 2);
INSERT INTO `countries` VALUES (15, 'North Africa', '14,16,19,20,21,22', 3);
INSERT INTO `countries` VALUES (16, 'Congo', '15,17,19', 1);
INSERT INTO `countries` VALUES (17, 'South Africa', '16,18,19', 2);
INSERT INTO `countries` VALUES (18, 'Madagascar', '17,19', 3);
INSERT INTO `countries` VALUES (19, 'East Africa', '15,16,17,18,20,28', 1);
INSERT INTO `countries` VALUES (20, 'Egypt', '15,19,22,28', 2);
INSERT INTO `countries` VALUES (21, 'Western Europe', '15,22,23,24', 3);
INSERT INTO `countries` VALUES (22, 'Southern Europe', '15,20,21,24,27,28', 1);
INSERT INTO `countries` VALUES (23, 'Great Britain', '21,24,25,26', 2);
INSERT INTO `countries` VALUES (24, 'Northern Europe', '21,22,23,26,27', 3);
INSERT INTO `countries` VALUES (25, 'Iceland', '5,23,26', 1);
INSERT INTO `countries` VALUES (26, 'Scandinavia', '23,24,25,27', 2);
INSERT INTO `countries` VALUES (27, 'Ukraine', '22,24,26,28,29,30', 3);
INSERT INTO `countries` VALUES (28, 'Middle East', '19,20,22,27,29,38', 1);
INSERT INTO `countries` VALUES (29, 'Afghanistan', '27,28,30,37,38', 2);
INSERT INTO `countries` VALUES (30, 'Ural', '27,29,31,37', 3);
INSERT INTO `countries` VALUES (31, 'Siberia', '30,32,33,34,37', 1);
INSERT INTO `countries` VALUES (32, 'Yakutsk', '31,33,36', 2);
INSERT INTO `countries` VALUES (33, 'Irkutsk', '31,32,34,36', 3);
INSERT INTO `countries` VALUES (34, 'Mongolia', '31,33,35,36,37', 1);
INSERT INTO `countries` VALUES (35, 'Japan', '34,36', 2);
INSERT INTO `countries` VALUES (36, 'Kamchatka', '1,32,33,34,35', 3);
INSERT INTO `countries` VALUES (37, 'China', '29,30,31,34,38,39', 1);
INSERT INTO `countries` VALUES (38, 'India', '28,29,37,39', 2);
INSERT INTO `countries` VALUES (39, 'Siam', '37,38,40', 3);
INSERT INTO `countries` VALUES (40, 'Indonesia', '39,41,42', 1);
INSERT INTO `countries` VALUES (41, 'New Guinea', '40,42,43', 2);
INSERT INTO `countries` VALUES (42, 'Western Australia', '40,41,43', 3);
INSERT INTO `countries` VALUES (43, 'Eastern Australia', '41,42', 1);
INSERT INTO `countries` VALUES (44, 'Wildcard', '', 0);
INSERT INTO `countries` VALUES (45, 'Wildcard', '', 0);

CREATE TABLE `game_id_seq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) AUTO_INCREMENT=1 ;

INSERT INTO `game_id_seq` (`id`) VALUES (1);

CREATE TABLE `games` (
  `id` int(10) unsigned NOT NULL default '0',
  `name` varchar(32) NOT NULL default '',
  `mode` varchar(16) NOT NULL default '',
  `type` varchar(16) NOT NULL default '',
  `players` int(3) unsigned NOT NULL default '0',
  `capacity` smallint(6) NOT NULL default '0',
  `kibitz` int(3) unsigned NOT NULL default '0',
  `password` varchar(32) NOT NULL default '0',
  `state` varchar(32) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `timelimit` int(15) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

CREATE TABLE `news` (
  `id` int(3) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `title` text NOT NULL,
  `message` text NOT NULL,
  `icon` text NOT NULL,
  `entry_date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
);

CREATE TABLE `online` (
  `timestamp` int(15) NOT NULL default '0',
  `ip` varchar(40) NOT NULL default '',
  `file` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`timestamp`),
  KEY `ip` (`ip`),
  KEY `file` (`file`)
);

CREATE TABLE `users` (
  `id` int(3) unsigned NOT NULL auto_increment,
  `rank` int(2) NOT NULL default '0',
  `login` varchar(32) NOT NULL default '',
  `pass` varchar(32) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `bio` text NOT NULL,
  `avatar` mediumblob NOT NULL,
  `image_type` varchar(25) NOT NULL default '',
  `image_name` varchar(60) NOT NULL default '',
  `win` varchar(4) NOT NULL default '0',
  `lose` varchar(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login` (`login`)
);


CREATE TABLE `forum` (
  `id` int(5) NOT NULL auto_increment,
  `thread` int(5) NOT NULL,
  `level` int(3) NOT NULL,
  `user` varchar(36) collate latin1_general_ci NOT NULL,
  `user_id` int(5) NOT NULL,
  `title` varchar(255) collate latin1_general_ci NOT NULL,
  `message` text collate latin1_general_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;
