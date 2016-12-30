/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : hjsx

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2016-12-27 13:57:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `hjsx_product2`
-- ----------------------------
DROP TABLE IF EXISTS `hjsx_product2`;
CREATE TABLE `hjsx_product2` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) DEFAULT NULL,
  `NavigationID` int(11) DEFAULT NULL,
  `Sort` int(11) DEFAULT '0',
  `AddTime` int(11) DEFAULT NULL,
  `ImagePath` varchar(255) DEFAULT NULL,
  `area_text` varchar(2000) DEFAULT NULL,
  `more_text` text,
  `radio_html` varchar(50) DEFAULT NULL,
  `more_select` varchar(255) DEFAULT NULL,
  `drop_down_list` varchar(50) DEFAULT NULL,
  `fileone` varchar(255) DEFAULT NULL,
  `more_image_select` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of hjsx_product2
-- ----------------------------
INSERT INTO `hjsx_product2` VALUES ('1', '1', '9', '1', null, '/static/uploads/images/20161225/e349cc538e2a4d408d720faf2a30ffcb.png', null, null, null, null, null, null, null);
INSERT INTO `hjsx_product2` VALUES ('2', '2', '9', '2', null, '', null, null, null, null, null, null, null);
INSERT INTO `hjsx_product2` VALUES ('3', '3', '9', '3', null, '', null, null, null, null, null, null, null);
INSERT INTO `hjsx_product2` VALUES ('4', '4', '9', '4', null, '', null, null, null, null, null, null, null);
INSERT INTO `hjsx_product2` VALUES ('5', '1', '9', '5', null, '/static/uploads/images/20161225/e349cc538e2a4d408d720faf2a30ffcb.png', '2', '<p>3</p>', '0', '2', '2', '/static/uploads/files/20161124\\fc831a5824143ebabd2e83cfbaa7d3db.mp4', '32');

-- ----------------------------
-- Table structure for `hjsx_products`
-- ----------------------------
DROP TABLE IF EXISTS `hjsx_products`;
CREATE TABLE `hjsx_products` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Sort` int(11) DEFAULT '0',
  `AddTime` int(11) DEFAULT NULL,
  `ceshi5` varchar(255) DEFAULT NULL,
  `ceshi6` varchar(255) DEFAULT NULL,
  `area_text` varchar(2000) DEFAULT NULL,
  `radio_select` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of hjsx_products
-- ----------------------------

-- ----------------------------
-- Table structure for `mc_banner`
-- ----------------------------
DROP TABLE IF EXISTS `mc_banner`;
CREATE TABLE `mc_banner` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ImagePath` varchar(80) NOT NULL COMMENT '图片地址',
  `Sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序编号',
  `Link` varchar(200) DEFAULT NULL COMMENT '链接地址',
  `NavigationID` int(11) DEFAULT NULL COMMENT '所属导航',
  PRIMARY KEY (`ID`),
  KEY `FK_Reference_13` (`NavigationID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mc_banner
-- ----------------------------

-- ----------------------------
-- Table structure for `mc_common_power`
-- ----------------------------
DROP TABLE IF EXISTS `mc_common_power`;
CREATE TABLE `mc_common_power` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `MenuID` int(11) DEFAULT NULL COMMENT '菜单编号',
  `Name` varchar(30) DEFAULT NULL COMMENT '权限名称',
  `Code` varchar(80) DEFAULT NULL COMMENT '权限代码',
  PRIMARY KEY (`ID`),
  KEY `FK_Reference_19` (`MenuID`),
  CONSTRAINT `FK_Reference_19` FOREIGN KEY (`MenuID`) REFERENCES `mc_menu` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mc_common_power
-- ----------------------------

-- ----------------------------
-- Table structure for `mc_file_info`
-- ----------------------------
DROP TABLE IF EXISTS `mc_file_info`;
CREATE TABLE `mc_file_info` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Path` varchar(600) NOT NULL COMMENT '文件路径',
  `Title` varchar(200) NOT NULL COMMENT '文件标题',
  `Content` varchar(1000) DEFAULT NULL COMMENT '文件内容',
  `FileType` int(11) NOT NULL COMMENT '文件所属类型',
  `FileExt` varchar(20) DEFAULT NULL COMMENT '文件后缀',
  `AddTime` int(11) DEFAULT NULL COMMENT '上传时间',
  `UpdateTime` int(11) DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`ID`),
  KEY `FK_Reference_5` (`FileType`),
  CONSTRAINT `FK_Reference_5` FOREIGN KEY (`FileType`) REFERENCES `mc_file_type` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mc_file_info
-- ----------------------------
INSERT INTO `mc_file_info` VALUES ('11', '/static/uploads/files/20161124\\fc831a5824143ebabd2e83cfbaa7d3db.mp4', 'fc831a5824143ebabd2e83cfbaa7d3db.mp4', null, '2', 'mp4', '1479996391', '1479996391');

-- ----------------------------
-- Table structure for `mc_file_type`
-- ----------------------------
DROP TABLE IF EXISTS `mc_file_type`;
CREATE TABLE `mc_file_type` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(50) NOT NULL COMMENT '分类名称',
  `ParentID` int(11) DEFAULT NULL COMMENT '所属分类',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mc_file_type
-- ----------------------------
INSERT INTO `mc_file_type` VALUES ('1', '视频文件', '0');
INSERT INTO `mc_file_type` VALUES ('2', 'World文件', '0');
INSERT INTO `mc_file_type` VALUES ('3', 'flv', '1');
INSERT INTO `mc_file_type` VALUES ('4', 'mp4', '1');

-- ----------------------------
-- Table structure for `mc_image_info`
-- ----------------------------
DROP TABLE IF EXISTS `mc_image_info`;
CREATE TABLE `mc_image_info` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(50) DEFAULT NULL COMMENT '标题',
  `Content` varchar(1000) DEFAULT NULL COMMENT '简介',
  `ImagePath` varchar(200) DEFAULT NULL COMMENT '图片路径',
  `ImageType` int(11) DEFAULT NULL COMMENT '图片分类',
  `AddTime` int(11) DEFAULT NULL COMMENT '添加时间',
  `UpdateTime` int(11) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mc_image_info
-- ----------------------------
INSERT INTO `mc_image_info` VALUES ('28', '2.jpg', '', '/static/uploads/images/20161105\\37b140df6e45b489fb3bbc0068cac2ce.jpg', '1', '1478314188', '1478314209');
INSERT INTO `mc_image_info` VALUES ('29', '3.jpg', '', '/static/uploads/images/20161105\\63ee4b4f639507fd08e66a6391f97b42.jpg', '1', '1478314189', '1478314209');
INSERT INTO `mc_image_info` VALUES ('30', '74938dc398ffbb9f0719366a59047222.mp4', null, '/static/uploads/images/20161120\\74938dc398ffbb9f0719366a59047222.mp4', '4', '1479646240', '1479646240');
INSERT INTO `mc_image_info` VALUES ('31', '838ce64b188084468b6e200b105b3d63.mp4', null, '/static/uploads/images/20161120\\838ce64b188084468b6e200b105b3d63.mp4', '4', '1479649039', '1479649039');
INSERT INTO `mc_image_info` VALUES ('32', 'e349cc538e2a4d408d720faf2a30ffcb.png', null, '/static/uploads/images/20161225/e349cc538e2a4d408d720faf2a30ffcb.png', '1', '1482645698', '1482645698');

-- ----------------------------
-- Table structure for `mc_image_type`
-- ----------------------------
DROP TABLE IF EXISTS `mc_image_type`;
CREATE TABLE `mc_image_type` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(50) NOT NULL COMMENT '分类名称',
  `ParentID` int(11) DEFAULT NULL COMMENT '所属分类',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mc_image_type
-- ----------------------------
INSERT INTO `mc_image_type` VALUES ('1', '产品', '0');
INSERT INTO `mc_image_type` VALUES ('2', '商户logo', '0');

-- ----------------------------
-- Table structure for `mc_member`
-- ----------------------------
DROP TABLE IF EXISTS `mc_member`;
CREATE TABLE `mc_member` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `LoginName` varchar(50) DEFAULT NULL,
  `NiceName` varchar(50) DEFAULT NULL,
  `PassWorld` varchar(50) DEFAULT NULL,
  `Status` int(11) DEFAULT NULL COMMENT '状态(0上班中 1.休假中 2离职)',
  `LoginGUID` int(11) DEFAULT NULL,
  `Department` int(11) DEFAULT '0' COMMENT '所属部门',
  `RoleID` int(11) DEFAULT '0' COMMENT '所属角色(-1为超级管理员)',
  PRIMARY KEY (`ID`),
  KEY `FK_Reference_12` (`Department`),
  CONSTRAINT `FK_Reference_12` FOREIGN KEY (`Department`) REFERENCES `mc_member_department` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mc_member
-- ----------------------------
INSERT INTO `mc_member` VALUES ('2', 'luocheng', '罗诚', 'e10adc3949ba59abbe56e057f20f883e', '0', '1482714090', '1', '-1');

-- ----------------------------
-- Table structure for `mc_member_department`
-- ----------------------------
DROP TABLE IF EXISTS `mc_member_department`;
CREATE TABLE `mc_member_department` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(30) DEFAULT NULL COMMENT '部门名称',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mc_member_department
-- ----------------------------
INSERT INTO `mc_member_department` VALUES ('1', '管理员');

-- ----------------------------
-- Table structure for `mc_menu`
-- ----------------------------
DROP TABLE IF EXISTS `mc_menu`;
CREATE TABLE `mc_menu` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(50) NOT NULL COMMENT '菜单名称',
  `ControllerName` varchar(50) DEFAULT NULL COMMENT '控制器',
  `ActionName` varchar(50) DEFAULT NULL COMMENT '操作',
  `ParentID` int(11) DEFAULT '0' COMMENT '所属父栏目',
  `Sort` int(11) DEFAULT '0' COMMENT '排序',
  `UpdateTime` int(11) DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mc_menu
-- ----------------------------
INSERT INTO `mc_menu` VALUES ('1', '系统设置', '', null, '0', '1', '1476258454');
INSERT INTO `mc_menu` VALUES ('2', '后台菜单', 'menu', 'index', '3', '0', '0');
INSERT INTO `mc_menu` VALUES ('3', '基本设置', null, null, '1', '0', '0');
INSERT INTO `mc_menu` VALUES ('4', '模型设置', '', '', '1', '1', '0');
INSERT INTO `mc_menu` VALUES ('8', '权限管理', '', '', '1', '2', '0');
INSERT INTO `mc_menu` VALUES ('9', '角色管理', 'role', 'index', '8', '3', '0');
INSERT INTO `mc_menu` VALUES ('10', '权限设置', 'power', 'index', '8', '5', '1476178309');
INSERT INTO `mc_menu` VALUES ('11', '权限拷贝', 'power', 'powercopy', '8', '6', '1476178309');
INSERT INTO `mc_menu` VALUES ('12', '公共权限设置', 'common_power', 'index', '8', '4', '1476178309');
INSERT INTO `mc_menu` VALUES ('13', '网站管理', '', '', '0', '0', '1476258454');
INSERT INTO `mc_menu` VALUES ('14', '图片管理', '', '', '13', '7', '0');
INSERT INTO `mc_menu` VALUES ('15', '图片分类', 'image_type', 'index', '14', '8', '0');
INSERT INTO `mc_menu` VALUES ('16', '文件管理', '', '', '13', '9', '0');
INSERT INTO `mc_menu` VALUES ('17', '文件分类', 'file_type', 'index', '16', '10', '0');
INSERT INTO `mc_menu` VALUES ('18', '图片信息', 'image_info', 'index', '14', '11', '0');
INSERT INTO `mc_menu` VALUES ('19', '文件信息', 'file_info', 'index', '16', '12', '0');
INSERT INTO `mc_menu` VALUES ('20', '站点设置', '', '', '13', '13', '0');
INSERT INTO `mc_menu` VALUES ('21', '站点设置', 'site', 'index', '20', '14', '0');
INSERT INTO `mc_menu` VALUES ('22', '模型管理', 'model', 'index', '4', '15', '0');
INSERT INTO `mc_menu` VALUES ('23', '导航管理', 'navigation', 'index', '20', '16', '0');
INSERT INTO `mc_menu` VALUES ('24', '内容管理', '', '', '13', '17', '0');
INSERT INTO `mc_menu` VALUES ('25', '内容管理', 'content', 'index', '24', '18', '0');

-- ----------------------------
-- Table structure for `mc_model`
-- ----------------------------
DROP TABLE IF EXISTS `mc_model`;
CREATE TABLE `mc_model` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) DEFAULT NULL COMMENT '模型名称',
  `Code` varchar(50) DEFAULT NULL COMMENT '模型代码',
  `SiteID` int(11) DEFAULT NULL COMMENT '所属站点',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mc_model
-- ----------------------------
INSERT INTO `mc_model` VALUES ('15', '产品某型1', 'products', '1');
INSERT INTO `mc_model` VALUES ('16', '产品模型2', 'product2', '1');

-- ----------------------------
-- Table structure for `mc_model_field`
-- ----------------------------
DROP TABLE IF EXISTS `mc_model_field`;
CREATE TABLE `mc_model_field` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FieldName` varchar(40) NOT NULL COMMENT '字段名称',
  `FieldNameCode` varchar(50) NOT NULL,
  `FieldNameMaxLength` mediumint(9) DEFAULT NULL COMMENT '字段内容最大长度',
  `FieldNameValidType` varchar(80) DEFAULT NULL COMMENT '字段验证类型',
  `FieldNameValidTypeTip` varchar(100) DEFAULT NULL,
  `FieldNameTip` varchar(200) DEFAULT NULL COMMENT '字段提示信息',
  `FieldType` mediumint(9) DEFAULT NULL COMMENT '字段填写类型',
  `FieldWidth` varchar(10) DEFAULT NULL COMMENT '字段显示长度',
  `FieldHeight` varchar(10) DEFAULT NULL COMMENT '字段显示宽度',
  `FieldDefault` varchar(1000) DEFAULT NULL COMMENT '字段默认值',
  `FieldHtml` varchar(1000) DEFAULT NULL COMMENT '字段html',
  `ModelID` int(11) DEFAULT NULL COMMENT '所属模型',
  `Sort` int(11) DEFAULT NULL COMMENT '排序',
  `IsSystem` int(11) DEFAULT '0',
  `FieldClass` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_Reference_16` (`ModelID`),
  CONSTRAINT `FK_Reference_16` FOREIGN KEY (`ModelID`) REFERENCES `mc_model` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mc_model_field
-- ----------------------------
INSERT INTO `mc_model_field` VALUES ('17', '测试5', 'ceshi5', '50', '', '', '字段提示信息', '1', '100px', '20px', '你好世界', '<div class=\"form-group\"><div class=\"label\"><label for=\"readme\">测试5：</label></div><div class=\"field\"><input type=\"text\" class=\"input\" id=\"ceshi5\" name=\"ceshi5\"  字段提示信息 value=\"你好世界\"  maxlength=\"50\" 100px 20px /></div></div>', '15', '3', '0', '');
INSERT INTO `mc_model_field` VALUES ('18', '测试6', 'ceshi6', '0', '', '', '', '1', '', '', '', '<div class=\"form-group\"><div class=\"label\"><label for=\"readme\">测试6：</label></div><div class=\"field\"><input type=\"text\" class=\"input\" id=\"ceshi6\" name=\"ceshi6\"        /></div></div>', '15', '2', '0', '');
INSERT INTO `mc_model_field` VALUES ('19', '编号', 'ID', null, null, null, null, '1', null, null, null, null, '16', '0', '1', null);
INSERT INTO `mc_model_field` VALUES ('20', '标题', 'Title', null, null, null, null, '1', null, null, null, null, '16', '0', '1', null);
INSERT INTO `mc_model_field` VALUES ('21', '所属栏目', 'NavigationID', null, null, null, null, '6', null, null, null, null, '16', '1', '1', null);
INSERT INTO `mc_model_field` VALUES ('22', '排序', 'Sort', null, null, null, null, '1', null, null, null, null, '16', '99', '1', null);
INSERT INTO `mc_model_field` VALUES ('26', '产品图片', 'ImagePath', '0', null, null, '', '8', '', '', '', '<div class=\"form-group\"><div class=\"label\"><label for=\"readme\">产品图片：</label></div><div class=\"field\"><img id=\"ShowImagePath\" width=\"120\" /><input type=\"text\" id=\"ImagePath\" name=\"ImagePath\" /><a class=\"button bg-main\" type=\"button\" onclick=\"javascript:ShowIframe(800,500,\'/manage/image_info/oneimageselectbox/BoxElementID/ShowImagePath/ValueElementID/ImagePath.html\');\">选择图片</a></div></div>', '16', '100', '0', '');
INSERT INTO `mc_model_field` VALUES ('29', '多文本', 'area_text', '0', null, null, '', '2', '', '', '', '<div class=\"form-group\"><div class=\"label\"><label for=\"readme\">多文本：</label></div><div class=\"field\"><textarea id=\"area_text\" name=\"area_text\"       ></textarea></div></div>', '16', '0', '0', '');
INSERT INTO `mc_model_field` VALUES ('30', '富文本1', 'more_text', '0', '', '', '', '3', '', '', '', '<div class=\"form-group\"><div class=\"label\"><label for=\"readme\">富文本1：</label></div><div class=\"field\"><script id=\"more_text\" name=\"more_text\" type=\"text/plain\"></script><script type=\"text/javascript\">var more_textUEdit = UE.getEditor(\'more_text\',{autoHeightEnabled:true});</script></div></div>', '16', '0', '0', '');
INSERT INTO `mc_model_field` VALUES ('31', '单选', 'radio_html', '0', null, null, '', '4', '', '', '男,1|女,0', '<div class=\"form-group\"><div class=\"label\"><label for=\"readme\">单选：</label></div><div class=\"field\"><label><input type=\"radio\" id=\"单选Code0\" name=\"radio_html\" value=\"1\" />&nbsp;男</label>&nbsp;&nbsp;<label><input type=\"radio\" id=\"单选Code1\" name=\"radio_html\" value=\"0\" />&nbsp;女</label>&nbsp;&nbsp;</div></div>', '16', '0', '0', '');
INSERT INTO `mc_model_field` VALUES ('32', '多选', 'more_select', '0', null, null, '', '5', '', '', '项目1,1|项目2,2|项目3,3', '<div class=\"form-group\"><div class=\"label\"><label for=\"readme\">多选：</label></div><div class=\"field\"><label><input type=\"checkbox\" id=\"多选Code0\" name=\"more_select\" value=\"1\" />&nbsp;项目1</label>&nbsp;&nbsp;<label><input type=\"checkbox\" id=\"多选Code1\" name=\"more_select\" value=\"2\" />&nbsp;项目2</label>&nbsp;&nbsp;<label><input type=\"checkbox\" id=\"多选Code2\" name=\"more_select\" value=\"3\" />&nbsp;项目3</label>&nbsp;&nbsp;</div></div>', '16', '0', '0', '');
INSERT INTO `mc_model_field` VALUES ('33', '下拉列表', 'drop_down_list', '0', null, null, '', '6', '', '', '下拉1,1|下拉2,2|下拉3,3', '<div class=\"form-group\"><div class=\"label\"><label for=\"readme\">下拉列表：</label></div><div class=\"field\"><select id=\"drop_down_list\" name=\"drop_down_list\"   ><option value=\"1\">下拉1</option><option value=\"2\">下拉2</option><option value=\"3\">下拉3</option></select></div></div>', '16', '0', '0', '');
INSERT INTO `mc_model_field` VALUES ('34', '文件单选', 'fileone', '0', null, null, '', '7', '', '', '', '<div class=\"form-group\"><div class=\"label\"><label for=\"readme\">文件单选：</label></div><div class=\"field\"><input type=\"text\" id=\"fileone\" name=\"fileone\" /><a class=\"button bg-main\" type=\"button\" onclick=\"javascript:ShowIframe(800,500,\'/manage/file_info/onefileselectbox/ValueElementID/fileone.html\');\">选择文件</a></div></div>', '16', '0', '0', '');
INSERT INTO `mc_model_field` VALUES ('35', '图片多选', 'more_image_select', '0', null, null, '', '9', '', '', '', '<div class=\"form-group\"><div class=\"label\"><label for=\"readme\">图片多选：</label></div><div class=\"field\"><div id=\"Showmore_image_select\" class=\"MoreImageBox clearfix\" ></div><input type=\"hidden\" id=\"more_image_select\" name=\"more_image_select\" class=\"MoreImageSelectValue\" ShowBox=\"Showmore_image_select\" /><a class=\"button bg-main\" type=\"button\" onclick=\"javascript:ShowIframe(800,500,\'/manage/image_info/moreimageselectbox/BoxElementID/Showmore_image_select/ValueElementID/more_image_select.html\');\">选择图片</a></div></div>', '16', '0', '0', '');

-- ----------------------------
-- Table structure for `mc_navigation`
-- ----------------------------
DROP TABLE IF EXISTS `mc_navigation`;
CREATE TABLE `mc_navigation` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(30) NOT NULL COMMENT '菜单名称',
  `DefaultImage` varchar(150) DEFAULT NULL COMMENT '默认显示图片',
  `HoverImage` varchar(150) DEFAULT NULL COMMENT '选中时图片',
  `Controller` varchar(80) DEFAULT NULL COMMENT '展示控制器',
  `Action` varchar(80) DEFAULT NULL COMMENT '展示方法',
  `BackController` varchar(80) DEFAULT NULL COMMENT '后台控制器',
  `BackAction` varchar(80) DEFAULT NULL COMMENT '后台方法',
  `ParentID` int(11) NOT NULL DEFAULT '0' COMMENT '所属菜单',
  `Sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `SiteID` int(11) DEFAULT NULL COMMENT '所属站点',
  `Url` varchar(200) DEFAULT NULL COMMENT '前台跳转地址',
  `Type` tinyint(1) DEFAULT '1' COMMENT '导航类型(0-普通模式,1-cms模式)',
  `ModelID` int(11) DEFAULT '0' COMMENT '所属模型',
  PRIMARY KEY (`ID`),
  KEY `FK_Reference_15` (`ModelID`),
  CONSTRAINT `FK_Reference_15` FOREIGN KEY (`ModelID`) REFERENCES `mc_model` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mc_navigation
-- ----------------------------
INSERT INTO `mc_navigation` VALUES ('8', '首页', '', '', '', '', '', '', '0', '0', '1', '', null, '16');
INSERT INTO `mc_navigation` VALUES ('9', '产品管理', '', '', '', '', '', '', '8', '0', '1', '', null, '16');
INSERT INTO `mc_navigation` VALUES ('10', '鼠标', '', '', '', '', '', '', '9', '0', '1', '', null, '16');

-- ----------------------------
-- Table structure for `mc_power`
-- ----------------------------
DROP TABLE IF EXISTS `mc_power`;
CREATE TABLE `mc_power` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `RoleID` int(11) NOT NULL COMMENT '角色编号',
  `MenuID` int(11) NOT NULL COMMENT '菜单编号',
  `Code` varchar(30) NOT NULL COMMENT '权限代码(Add,Update,Delete,Select)',
  PRIMARY KEY (`ID`),
  KEY `FK_Reference_2` (`RoleID`),
  KEY `FK_Reference_3` (`MenuID`),
  CONSTRAINT `FK_Reference_2` FOREIGN KEY (`RoleID`) REFERENCES `mc_role` (`ID`),
  CONSTRAINT `FK_Reference_3` FOREIGN KEY (`MenuID`) REFERENCES `mc_menu` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mc_power
-- ----------------------------
INSERT INTO `mc_power` VALUES ('27', '4', '2', 'select');
INSERT INTO `mc_power` VALUES ('28', '4', '2', 'add');
INSERT INTO `mc_power` VALUES ('29', '4', '2', 'update');
INSERT INTO `mc_power` VALUES ('30', '4', '2', 'delete');
INSERT INTO `mc_power` VALUES ('31', '4', '4', 'select');
INSERT INTO `mc_power` VALUES ('32', '4', '4', 'add');
INSERT INTO `mc_power` VALUES ('33', '4', '4', 'update');
INSERT INTO `mc_power` VALUES ('34', '4', '4', 'delete');
INSERT INTO `mc_power` VALUES ('35', '4', '9', 'select');
INSERT INTO `mc_power` VALUES ('36', '4', '9', 'add');
INSERT INTO `mc_power` VALUES ('37', '4', '9', 'update');
INSERT INTO `mc_power` VALUES ('38', '4', '9', 'delete');
INSERT INTO `mc_power` VALUES ('39', '4', '10', 'select');
INSERT INTO `mc_power` VALUES ('40', '4', '10', 'add');
INSERT INTO `mc_power` VALUES ('41', '4', '10', 'update');
INSERT INTO `mc_power` VALUES ('42', '4', '10', 'delete');
INSERT INTO `mc_power` VALUES ('43', '4', '11', 'select');
INSERT INTO `mc_power` VALUES ('44', '4', '11', 'add');
INSERT INTO `mc_power` VALUES ('45', '4', '11', 'update');
INSERT INTO `mc_power` VALUES ('46', '4', '11', 'delete');
INSERT INTO `mc_power` VALUES ('47', '3', '2', 'select');
INSERT INTO `mc_power` VALUES ('48', '3', '2', 'add');
INSERT INTO `mc_power` VALUES ('49', '3', '2', 'update');
INSERT INTO `mc_power` VALUES ('50', '3', '2', 'delete');
INSERT INTO `mc_power` VALUES ('51', '3', '4', 'select');
INSERT INTO `mc_power` VALUES ('52', '3', '4', 'add');
INSERT INTO `mc_power` VALUES ('53', '3', '4', 'update');
INSERT INTO `mc_power` VALUES ('54', '3', '4', 'delete');
INSERT INTO `mc_power` VALUES ('55', '3', '9', 'select');
INSERT INTO `mc_power` VALUES ('56', '3', '9', 'add');
INSERT INTO `mc_power` VALUES ('57', '3', '9', 'update');
INSERT INTO `mc_power` VALUES ('58', '3', '9', 'delete');
INSERT INTO `mc_power` VALUES ('59', '3', '10', 'select');
INSERT INTO `mc_power` VALUES ('60', '3', '10', 'add');
INSERT INTO `mc_power` VALUES ('61', '3', '10', 'update');
INSERT INTO `mc_power` VALUES ('62', '3', '10', 'delete');
INSERT INTO `mc_power` VALUES ('63', '3', '11', 'select');
INSERT INTO `mc_power` VALUES ('64', '3', '11', 'add');
INSERT INTO `mc_power` VALUES ('65', '3', '11', 'update');
INSERT INTO `mc_power` VALUES ('66', '3', '11', 'delete');

-- ----------------------------
-- Table structure for `mc_role`
-- ----------------------------
DROP TABLE IF EXISTS `mc_role`;
CREATE TABLE `mc_role` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL COMMENT '角色名称',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mc_role
-- ----------------------------
INSERT INTO `mc_role` VALUES ('3', '前台');
INSERT INTO `mc_role` VALUES ('4', '门市');

-- ----------------------------
-- Table structure for `mc_site`
-- ----------------------------
DROP TABLE IF EXISTS `mc_site`;
CREATE TABLE `mc_site` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(30) DEFAULT NULL COMMENT '站点名称',
  `IsDefault` tinyint(4) DEFAULT '0' COMMENT '默认站点',
  `Code` varchar(20) NOT NULL COMMENT '站点代码',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mc_site
-- ----------------------------
INSERT INTO `mc_site` VALUES ('1', '花椒生鲜', '1', 'hjsx');
