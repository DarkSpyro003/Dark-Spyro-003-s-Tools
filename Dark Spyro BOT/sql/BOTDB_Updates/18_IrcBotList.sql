/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50149
Source Host           : localhost:3306
Source Database       : BOTDB

Target Server Type    : MYSQL
Target Server Version : 50149
File Encoding         : 65001

Date: 2011-01-15 23:50:24
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `IrcBotList`
-- ----------------------------
DROP TABLE IF EXISTS `IrcBotList`;
CREATE TABLE `IrcBotList` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of IrcBotList
-- ----------------------------
INSERT INTO IrcBotList VALUES ('1', 'Docioroius');
