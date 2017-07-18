#SKD101|we-not|1|2017.07.18 23:17:52|1|1

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(20) DEFAULT '',
  `date` varchar(20) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 /*!40101 DEFAULT CHARSET=utf8 */;

INSERT INTO `migrations` VALUES
(1, '01', '2017.07.18, 23:16:28');

