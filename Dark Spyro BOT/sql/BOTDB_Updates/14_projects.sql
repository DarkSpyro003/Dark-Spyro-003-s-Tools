SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `projects`
-- ----------------------------
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `ID` mediumint(8) NOT NULL AUTO_INCREMENT,
  `ProjectName` varchar(255) NOT NULL,
  `ProjectAltTrigger` varchar(255) NOT NULL COMMENT 'Alternative trigger (short version) for the project name i.e. arc for arcemu',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of projects
-- ----------------------------
INSERT INTO projects VALUES ('1', 'arcemu', 'arc');

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `project_triggers`
-- ----------------------------
DROP TABLE IF EXISTS `project_triggers`;
CREATE TABLE `project_triggers` (
  `ID` mediumint(8) NOT NULL AUTO_INCREMENT,
  `ProjectID` mediumint(8) NOT NULL,
  `Trigger` varchar(255) NOT NULL,
  `Type` tinyint(1) NOT NULL,
  `ResultID` mediumint(8) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of project_triggers
-- ----------------------------
INSERT INTO project_triggers VALUES ('1', '1', 'rev', '1', '1');
INSERT INTO project_triggers VALUES ('2', '1', 'scriptsrev', '0', '2');
INSERT INTO project_triggers VALUES ('3', '1', 'ticket', '1', '3');
INSERT INTO project_triggers VALUES ('4', '1', 'tickets', '0', '4');
INSERT INTO project_triggers VALUES ('5', '1', 'newticket', '0', '5');
INSERT INTO project_triggers VALUES ('6', '1', 'bugreport', '0', '5');
INSERT INTO project_triggers VALUES ('7', '1', 'reportbug', '0', '5');
INSERT INTO project_triggers VALUES ('8', '1', 'makeaticket', '0', '5');
INSERT INTO project_triggers VALUES ('9', '1', 'trac', '0', '6');
INSERT INTO project_triggers VALUES ('10', '1', 'scripts', '0', '2');
INSERT INTO project_triggers VALUES ('11', '1', 'arcscripts', '0', '2');
INSERT INTO project_triggers VALUES ('12', '1', 'scriptticket', '0', '5');
INSERT INTO project_triggers VALUES ('13', '1', 'scriptbug', '0', '5');
INSERT INTO project_triggers VALUES ('14', '1', 'scriptbugreport', '0', '5');
INSERT INTO project_triggers VALUES ('15', '1', 'reportscriptbug', '0', '5');
INSERT INTO project_triggers VALUES ('16', '1', 'scriptreportbug', '0', '5');
INSERT INTO project_triggers VALUES ('17', '1', 'wiki', '0', '7');
INSERT INTO project_triggers VALUES ('18', '1', 'website', '0', '8');
INSERT INTO project_triggers VALUES ('19', '1', 'forum', '0', '9');
INSERT INTO project_triggers VALUES ('20', '1', 'forums', '0', '9');
INSERT INTO project_triggers VALUES ('21', '1', 'board', '0', '9');
INSERT INTO project_triggers VALUES ('22', '1', 'svn', '0', '10');
INSERT INTO project_triggers VALUES ('23', '1', 'repo', '0', '10');
INSERT INTO project_triggers VALUES ('24', '1', 'repository', '0', '10');
INSERT INTO project_triggers VALUES ('25', '1', 'polite', '0', '11');
INSERT INTO project_triggers VALUES ('26', '1', 'politeness', '0', '11');
INSERT INTO project_triggers VALUES ('27', '1', 'impolite', '0', '11');
INSERT INTO project_triggers VALUES ('28', '1', 'behaviour', '0', '11');
INSERT INTO project_triggers VALUES ('29', '1', 'hello', '0', '11');
INSERT INTO project_triggers VALUES ('30', '1', 'hi', '0', '11');
INSERT INTO project_triggers VALUES ('31', '1', 'arcmanager', '0', '12');
INSERT INTO project_triggers VALUES ('32', '1', 'armory', '0', '12');
INSERT INTO project_triggers VALUES ('33', '1', 'emusite', '0', '12');
INSERT INTO project_triggers VALUES ('34', '1', 'site', '0', '8');
INSERT INTO project_triggers VALUES ('35', '1', 'irc', '0', '13');
INSERT INTO project_triggers VALUES ('36', '1', 'chat', '0', '13');

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `project_results`
-- ----------------------------
DROP TABLE IF EXISTS `project_results`;
CREATE TABLE `project_results` (
  `ID` mediumint(8) NOT NULL AUTO_INCREMENT,
  `Result` text NOT NULL,
  `WrongTypeResult` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of project_results
-- ----------------------------
INSERT INTO project_results VALUES ('1', 'https://sourceforge.net/apps/trac/arcemu/changeset/', 'SVN revisions are numeric only. Did you mean \"svn\"? https://arcemu.svn.sourceforge.net/svnroot/arcemu');
INSERT INTO project_results VALUES ('2', 'Arcscripts is now merged with the main repository. https://arcemu.svn.sourceforge.net/svnroot/arcemu', 'Arcscripts is now merged with the main repository. https://arcemu.svn.sourceforge.net/svnroot/arcemu');
INSERT INTO project_results VALUES ('3', 'https://sourceforge.net/apps/trac/arcemu/ticket/', 'Ticket IDs are numeric only. Did you mean \"tickets\"? https://sourceforge.net/apps/trac/arcemu/report/1');
INSERT INTO project_results VALUES ('4', 'https://sourceforge.net/apps/trac/arcemu/report/1', 'https://sourceforge.net/apps/trac/arcemu/report/1');
INSERT INTO project_results VALUES ('5', 'https://sourceforge.net/apps/trac/arcemu/newticket', 'https://sourceforge.net/apps/trac/arcemu/newticket');
INSERT INTO project_results VALUES ('6', 'https://sourceforge.net/apps/trac/arcemu/', 'https://sourceforge.net/apps/trac/arcemu/');
INSERT INTO project_results VALUES ('7', 'http://www.arcemu.org/wiki/index.php?title=Main_Page', 'http://www.arcemu.org/wiki/index.php?title=Main_Page');
INSERT INTO project_results VALUES ('8', 'http://www.arcemu.org/', 'http://www.arcemu.org/');
INSERT INTO project_results VALUES ('9', 'http://arcemu.org/forums/', 'http://arcemu.org/forums/');
INSERT INTO project_results VALUES ('10', 'https://arcemu.svn.sourceforge.net/svnroot/arcemu', 'https://arcemu.svn.sourceforge.net/svnroot/arcemu');
INSERT INTO project_results VALUES ('11', 'It is considered polite when joining to say \"hello\" or greet us instead of using us like a doormat to answer your questions.', 'It is considered polite when joining to say \"hello\" or greet us instead of using us like a doormat to answer your questions.');
INSERT INTO project_results VALUES ('12', 'http://arcemu.org/forums/index.php?showtopic=19459', 'http://arcemu.org/forums/index.php?showtopic=19459');
INSERT INTO project_results VALUES ('13', 'irc://irc.freenode.net/##arcemu', 'irc://irc.freenode.net/##arcemu');
