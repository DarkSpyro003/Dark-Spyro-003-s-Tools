SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `projects`
-- ----------------------------
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `ID` mediumint(8) NOT NULL,
  `ProjectTrigger` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`,`ProjectTrigger`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of projects
-- ----------------------------
INSERT INTO projects VALUES ('1', 'arc');
INSERT INTO projects VALUES ('1', 'arcemu');
