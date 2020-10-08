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

 Date: 10/08/2020 14:02:12
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for xq_admin_permission
-- ----------------------------
DROP TABLE IF EXISTS `xq_admin_permission`;
CREATE TABLE `xq_admin_permission`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '中文名',
  `en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '英文名',
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '实际权限',
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '类型：api-接口 view-视图',
  `method` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GET' COMMENT '请求方法：仅在 type=api 的时候有效！GET|POST|PUT|PATCH|DELETE ...',
  `is_menu` tinyint(4) NOT NULL DEFAULT 0 COMMENT '仅在 type=view 的时候有效，是否在菜单列表显示：0-否 1-是',
  `is_view` tinyint(4) NOT NULL DEFAULT 0 COMMENT '仅在 type=view 的时候有效，是否是一个视图：0-否 1-是',
  `enable` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否启用：0-否 1-是',
  `p_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'xq_admin_permission.id',
  `s_ico` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '小图标',
  `b_ico` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '大图标',
  `weight` int(11) NOT NULL DEFAULT 0 COMMENT '权重',
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '后台用户-权限表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of xq_admin_permission
-- ----------------------------
INSERT INTO `xq_admin_permission` VALUES (1, '控制台', 'Pannel', '/pannel', '', 'view', 'GET', 1, 1, 1, 0, '20200808/LMeffsYjK3CqKNXjCyKARJqbt46n3RJ5xEmJfHuQ.png', '20200808/LMeffsYjK3CqKNXjCyKARJqbt46n3RJ5xEmJfHuQ.png', 200, '2020-06-07 20:46:36', '2020-08-08 19:08:26');
INSERT INTO `xq_admin_permission` VALUES (2, '权限管理', 'Permission', 'permission', '', 'view', 'GET', 1, 1, 1, 0, '20200611/SnGQCl7dxDdCbnDZ8Cu6JRSwK0fjKcqKb4c8in27.png', '20200611/SnGQCl7dxDdCbnDZ8Cu6JRSwK0fjKcqKb4c8in27.png', 0, '2020-06-07 20:46:36', '2020-06-26 10:24:39');
INSERT INTO `xq_admin_permission` VALUES (3, '角色列表', '', '/role/index', '', 'view', 'GET', 1, 1, 1, 2, '', '', 0, '2020-06-07 20:46:36', '2020-06-14 15:09:53');
INSERT INTO `xq_admin_permission` VALUES (4, '权限列表', '', '/admin_permission/list', '', 'view', 'GET', 1, 1, 1, 2, '', '', 0, '2020-06-07 20:46:36', '2020-06-26 10:24:31');
INSERT INTO `xq_admin_permission` VALUES (5, '用户管理', '', '/user/index', '', 'view', 'GET', 1, 1, 1, 0, '20200619/lJaIb6dJDLbb9JqmNpra6M9AfnwFN9YPvHFQG5jC.png', '20200619/lJaIb6dJDLbb9JqmNpra6M9AfnwFN9YPvHFQG5jC.png', 99, '2020-06-07 20:46:36', '2020-06-19 10:08:44');
INSERT INTO `xq_admin_permission` VALUES (7, '模块管理', '', '/module/index', '', 'view', 'GET', 1, 1, 1, 0, '20200613/I28zlB1gm3S5F8OCcmKiC6KEaX85ZCrgL0QM7wgc.png', '20200613/I28zlB1gm3S5F8OCcmKiC6KEaX85ZCrgL0QM7wgc.png', 93, '2020-06-07 20:46:36', '2020-06-16 22:00:46');
INSERT INTO `xq_admin_permission` VALUES (8, '模块列表', '', '/module/index', '', 'view', 'GET', 1, 1, 0, 7, '', '0', 0, '2020-06-07 20:46:36', '2020-06-14 22:26:18');
INSERT INTO `xq_admin_permission` VALUES (9, '系统管理', 'System', '/system/index', '', 'view', 'GET', 1, 0, 1, 0, '20200623/FyNZxSZURNxiqyrZsN8489YosDqKSX2ZHbjZL1a0.png', '20200623/FyNZxSZURNxiqyrZsN8489YosDqKSX2ZHbjZL1a0.png', 0, '2020-06-12 19:49:29', '2020-07-13 19:38:20');
INSERT INTO `xq_admin_permission` VALUES (11, '个性标签', 'Tag', '/tag/index', '', 'view', 'GET', 1, 1, 1, 0, '20200613/tSKplyTxG6M9FMVZ9Hq7IjBQmmxBBz8UDJutITcI.png', '20200613/tSKplyTxG6M9FMVZ9Hq7IjBQmmxBBz8UDJutITcI.png', 96, '2020-06-13 22:47:07', '2020-06-16 22:00:41');
INSERT INTO `xq_admin_permission` VALUES (12, '内容分类', 'Category', '/category/index', '', 'view', 'GET', 1, 1, 1, 0, '20200614/ulRqdRcnlBi0aiXS77bgt4iAKv3R7nNFvx2tnPbU.png', '20200614/ulRqdRcnlBi0aiXS77bgt4iAKv3R7nNFvx2tnPbU.png', 95, '2020-06-13 23:01:15', '2020-06-16 22:00:37');
INSERT INTO `xq_admin_permission` VALUES (13, '关联主体', 'Subject', '/subject/index', '', 'view', 'GET', 1, 1, 1, 0, '20200614/Yu9PBltXZSYIWyQKIEePcLWYez66t4Bh6WessIMd.png', '20200614/Yu9PBltXZSYIWyQKIEePcLWYez66t4Bh6WessIMd.png', 94, '2020-06-13 23:02:11', '2020-06-16 22:00:32');
INSERT INTO `xq_admin_permission` VALUES (14, '图片专题', 'Image Subject', '/image_subject/index', '', 'view', 'GET', 1, 1, 1, 0, '20200614/bOHFTGPz8raGv5wxpLWTQZQupbIzi6Hlsu4Kar2a.png', '20200614/bOHFTGPz8raGv5wxpLWTQZQupbIzi6Hlsu4Kar2a.png', 98, '2020-06-13 23:04:32', '2020-08-06 10:31:10');
INSERT INTO `xq_admin_permission` VALUES (15, '后台用户', '', '/admin/index', '', 'view', 'GET', 1, 1, 1, 0, '20200619/tbw1fNzsU4epnoNiKpRRgnxZalpwhBCJYXFUWZCv.png', '20200619/tbw1fNzsU4epnoNiKpRRgnxZalpwhBCJYXFUWZCv.png', 100, '2020-06-16 22:22:39', '2020-06-19 10:06:06');
INSERT INTO `xq_admin_permission` VALUES (17, '系统位置', 'Position', '/position/index', '', 'view', 'GET', 1, 1, 1, 9, '', '', 0, '2020-06-24 00:38:07', '2020-06-24 02:42:33');
INSERT INTO `xq_admin_permission` VALUES (18, '定点图片', 'Image At Position', '/image_at_position/index', '', 'view', 'GET', 1, 1, 1, 9, '', '', 0, '2020-06-24 00:38:38', '2020-06-24 02:42:43');
INSERT INTO `xq_admin_permission` VALUES (19, '导航菜单', 'Nav', '/nav/index', '', 'view', 'GET', 1, 1, 1, 9, '', '', 0, '2020-07-13 19:34:58', '2020-07-13 19:35:19');
INSERT INTO `xq_admin_permission` VALUES (20, '视频管理', 'Video Manager', 'video', '', 'view', 'GET', 1, 0, 1, 0, '20200727/sC9wGCJ6wXmfC0nmVBkkRlpXITtzFylncBCpvNlD.png', '20200727/sC9wGCJ6wXmfC0nmVBkkRlpXITtzFylncBCpvNlD.png', 97, '2020-07-27 21:19:58', '2020-08-06 20:41:31');
INSERT INTO `xq_admin_permission` VALUES (21, '视频系列', '', '/video_series/index', '', 'view', 'GET', 1, 1, 1, 20, '', '', 0, '2020-07-27 21:20:50', '2020-08-04 17:02:25');
INSERT INTO `xq_admin_permission` VALUES (22, '视频制作公司', '', '/video_company/index', '', 'view', 'GET', 1, 1, 1, 20, '', '', 0, '2020-07-27 21:21:16', '2020-08-08 09:50:58');
INSERT INTO `xq_admin_permission` VALUES (23, '视频列表', '', '/video/index', '', 'view', 'GET', 1, 1, 1, 20, '', '', 0, '2020-07-27 21:21:54', '2020-08-08 10:29:17');
INSERT INTO `xq_admin_permission` VALUES (24, '视频专题', '', '/video_project/index', '', 'view', 'GET', 1, 1, 1, 20, '', '', 0, '2020-07-29 14:02:38', '2020-08-04 17:10:26');

SET FOREIGN_KEY_CHECKS = 1;
