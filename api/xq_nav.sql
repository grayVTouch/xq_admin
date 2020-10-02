/*
 Navicat Premium Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 80017
 Source Host           : localhost:3306
 Source Schema         : xinqu

 Target Server Type    : MySQL
 Target Server Version : 80017
 File Encoding         : 65001

 Date: 12/09/2020 11:27:46
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for xq_nav
-- ----------------------------
DROP TABLE IF EXISTS `xq_nav`;
CREATE TABLE `xq_nav`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单值',
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '描述',
  `p_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'xq_nav.id',
  `is_menu` tinyint(4) NOT NULL DEFAULT 0 COMMENT '菜单？0-否 1-是',
  `enable` tinyint(4) NOT NULL DEFAULT 0 COMMENT '启用？0-否 1-是',
  `weight` int(11) NOT NULL DEFAULT 0 COMMENT '权重',
  `module_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'xq_module.id',
  `platform` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '平台：app | android | ios | web | mobile',
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '菜单表-区分不同平台' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of xq_nav
-- ----------------------------
INSERT INTO `xq_nav` VALUES (1, '首页', '/index', '', 0, 1, 1, 0, 1, 'web', NULL);
INSERT INTO `xq_nav` VALUES (2, '视频专区', '/video', '', 0, 1, 1, 0, 1, 'web', NULL);
INSERT INTO `xq_nav` VALUES (3, '图片专区', '/image_subject', '', 0, 1, 1, 0, 1, 'web', NULL);
INSERT INTO `xq_nav` VALUES (4, '用户中心', '/user', '', 0, 1, 1, 0, 1, 'web', NULL);
INSERT INTO `xq_nav` VALUES (5, '首页', '/index', '', 0, 1, 1, 0, 2, 'web', NULL);
INSERT INTO `xq_nav` VALUES (6, '视频专区', '/video', '', 0, 1, 1, 0, 2, 'web', NULL);
INSERT INTO `xq_nav` VALUES (7, '图片专区', '/image', '', 0, 1, 1, 0, 2, 'web', NULL);
INSERT INTO `xq_nav` VALUES (8, '用户中心', '/user', '', 0, 1, 1, 0, 2, 'web', NULL);
INSERT INTO `xq_nav` VALUES (9, '图片详情', '/image_subject/:id/show', '', 3, 0, 1, 0, 1, 'web', NULL);
INSERT INTO `xq_nav` VALUES (10, '用户信息', '/user/info', '', 4, 0, 1, 0, 1, 'web', NULL);
INSERT INTO `xq_nav` VALUES (11, '修改密码', '/user/password', '', 4, 0, 1, 0, 1, 'web', NULL);
INSERT INTO `xq_nav` VALUES (12, '历史记录', '/user/history', '', 4, 0, 1, 0, 1, 'web', NULL);
INSERT INTO `xq_nav` VALUES (13, '我的收藏', '/user/favorites', '', 4, 0, 1, 0, 1, 'web', NULL);
INSERT INTO `xq_nav` VALUES (14, '搜索【图片专区】', '/image_subject/search', '', 3, 0, 1, 0, 1, 'web', NULL);
INSERT INTO `xq_nav` VALUES (15, '二次元', '/image_subject/search?category_id=1', '', 3, 1, 1, 0, 1, 'web', NULL);

SET FOREIGN_KEY_CHECKS = 1;
