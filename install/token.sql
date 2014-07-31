CREATE TABLE IF NOT EXISTS `token` (
  `uid` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `token` varchar(40) NOT NULL,
  `creation` datetime NOT NULL,
  PRIMARY KEY (`uid`, `name`),
  KEY `token` (`token`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1;
