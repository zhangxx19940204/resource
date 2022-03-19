/*
 Navicat Premium Data Transfer

 Source Server         : resource
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : resource

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 05/12/2021 16:05:27
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uri` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `permission` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_menu
-- ----------------------------
INSERT INTO `admin_menu` VALUES (1, 0, 1, '主页', 'fa-bar-chart', '/', NULL, NULL, '2021-01-09 16:25:19');
INSERT INTO `admin_menu` VALUES (2, 0, 26, 'Admin', 'fa-tasks', '', NULL, NULL, '2021-12-02 17:55:27');
INSERT INTO `admin_menu` VALUES (3, 2, 27, 'Users', 'fa-users', 'auth/users', NULL, NULL, '2021-12-02 17:55:27');
INSERT INTO `admin_menu` VALUES (4, 2, 28, 'Roles', 'fa-user', 'auth/roles', NULL, NULL, '2021-12-02 17:55:27');
INSERT INTO `admin_menu` VALUES (5, 2, 29, 'Permission', 'fa-ban', 'auth/permissions', NULL, NULL, '2021-12-02 17:55:27');
INSERT INTO `admin_menu` VALUES (6, 2, 30, 'Menu', 'fa-bars', 'auth/menu', NULL, NULL, '2021-12-02 17:55:27');
INSERT INTO `admin_menu` VALUES (7, 2, 31, 'Operation log', 'fa-history', 'auth/logs', NULL, NULL, '2021-12-02 17:55:27');
INSERT INTO `admin_menu` VALUES (8, 0, 2, '资源管理', 'fa-area-chart', NULL, '*', '2020-11-21 04:19:28', '2021-02-27 19:14:07');
INSERT INTO `admin_menu` VALUES (9, 8, 3, '资源统计', 'fa-bar-chart', 'res-datas', '*', '2020-11-21 04:20:23', '2021-02-27 19:14:07');
INSERT INTO `admin_menu` VALUES (10, 0, 14, '邮件系统', 'fa-envelope-o', NULL, NULL, '2020-11-24 17:21:35', '2021-12-02 17:55:27');
INSERT INTO `admin_menu` VALUES (11, 10, 15, '邮件数据', 'fa-envelope-o', 'email-datas', '*', '2020-11-24 17:22:22', '2021-12-02 17:55:27');
INSERT INTO `admin_menu` VALUES (12, 10, 16, '邮箱配置', 'fa-cog', 'email-configs', '*', '2020-11-24 17:23:17', '2021-12-02 17:55:27');
INSERT INTO `admin_menu` VALUES (13, 10, 17, '邮件通过', 'fa-angellist', 'email-passes', '*', '2020-11-24 17:23:50', '2021-12-02 17:55:27');
INSERT INTO `admin_menu` VALUES (14, 0, 20, '自动化', 'fa-bars', NULL, '*', '2020-12-13 17:52:51', '2021-12-02 17:55:27');
INSERT INTO `admin_menu` VALUES (15, 14, 23, '分配配置', 'fa-bars', 'res-distribution-configs', '*', '2020-12-13 17:53:08', '2021-12-02 17:55:27');
INSERT INTO `admin_menu` VALUES (16, 14, 25, 'EC用户列表', 'fa-bars', 'ec-users', '*', '2020-12-13 18:08:08', '2021-12-02 17:55:27');
INSERT INTO `admin_menu` VALUES (17, 14, 24, '简短反馈配置', 'fa-bars', 'short-feedback_relatives', '*', '2021-01-01 13:35:34', '2021-12-02 17:55:27');
INSERT INTO `admin_menu` VALUES (18, 8, 4, '资源分配日志', 'fa-bars', 'distribute-logs', '*', '2021-01-04 14:48:40', '2021-02-27 19:14:07');
INSERT INTO `admin_menu` VALUES (19, 10, 19, '邮件来源统计', 'fa-bars', 'mail-froms', '*', '2021-01-04 14:49:43', '2021-12-02 17:55:27');
INSERT INTO `admin_menu` VALUES (20, 10, 18, '邮件所属统计', 'fa-bars', 'mail-belongs', '*', '2021-01-09 17:06:12', '2021-12-02 17:55:27');
INSERT INTO `admin_menu` VALUES (21, 0, 6, '反馈管理', 'fa-arrow-down', NULL, '*', '2021-02-27 19:11:42', '2021-12-02 17:50:56');
INSERT INTO `admin_menu` VALUES (22, 21, 7, '资源反馈', 'fa-bars', 'feedback', '*', '2021-02-27 19:12:27', '2021-12-02 17:50:56');
INSERT INTO `admin_menu` VALUES (23, 21, 8, '来访', 'fa-bars', 'visits', '*', '2021-02-27 19:12:45', '2021-12-02 17:50:56');
INSERT INTO `admin_menu` VALUES (24, 21, 9, '钉钉用户', 'fa-bars', 'ding-talk-users', '*', '2021-02-27 19:13:07', '2021-12-02 17:50:56');
INSERT INTO `admin_menu` VALUES (25, 0, 11, '客服系统', 'fa-comments-o', NULL, NULL, '2021-03-31 14:09:40', '2021-12-02 17:52:40');
INSERT INTO `admin_menu` VALUES (26, 25, 12, '客服记录', 'fa-commenting-o', 'customerService_records', NULL, '2021-03-31 14:11:17', '2021-12-02 17:56:05');
INSERT INTO `admin_menu` VALUES (27, 14, 21, '钉钉EC关系表', 'fa-bars', 'dingtalk-ec-relatives', NULL, '2021-06-29 15:12:38', '2021-12-02 17:55:27');
INSERT INTO `admin_menu` VALUES (28, 8, 5, '资源账户配置', 'fa-bars', 'res-configs', '*', '2021-12-02 17:50:48', '2021-12-02 17:50:56');
INSERT INTO `admin_menu` VALUES (29, 21, 10, '项目列表', 'fa-bars', 'ding-talk-projects', '*', '2021-12-02 17:52:15', '2021-12-02 17:52:40');
INSERT INTO `admin_menu` VALUES (30, 25, 13, '客服系统对接配置', 'fa-bars', 'customerService_configs', '*', '2021-12-02 17:53:22', '2021-12-02 17:56:05');
INSERT INTO `admin_menu` VALUES (31, 14, 22, '自主请假记录', 'fa-bars', 'record_leave_robot_datas', '*', '2021-12-02 17:54:47', '2021-12-02 17:55:27');

-- ----------------------------
-- Table structure for admin_operation_log
-- ----------------------------
DROP TABLE IF EXISTS `admin_operation_log`;
CREATE TABLE `admin_operation_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `path` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `input` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `admin_operation_log_user_id_index`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_operation_log
-- ----------------------------

-- ----------------------------
-- Table structure for admin_permissions
-- ----------------------------
DROP TABLE IF EXISTS `admin_permissions`;
CREATE TABLE `admin_permissions`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `http_method` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `http_path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `admin_permissions_name_unique`(`name`) USING BTREE,
  UNIQUE INDEX `admin_permissions_slug_unique`(`slug`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_permissions
-- ----------------------------
INSERT INTO `admin_permissions` VALUES (1, 'All permission', '*', '', '*', NULL, NULL);
INSERT INTO `admin_permissions` VALUES (2, 'Dashboard', 'dashboard', 'GET', '/', NULL, NULL);
INSERT INTO `admin_permissions` VALUES (3, 'Login', 'auth.login', '', '/auth/login\r\n/auth/logout', NULL, NULL);
INSERT INTO `admin_permissions` VALUES (4, 'User setting', 'auth.setting', 'GET,PUT', '/auth/setting', NULL, NULL);
INSERT INTO `admin_permissions` VALUES (5, 'Auth management', 'auth.management', '', '/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs', NULL, NULL);

-- ----------------------------
-- Table structure for admin_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_menu`;
CREATE TABLE `admin_role_menu`  (
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `admin_role_menu_role_id_menu_id_index`(`role_id`, `menu_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_role_menu
-- ----------------------------
INSERT INTO `admin_role_menu` VALUES (1, 2, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 8, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 9, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 11, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 12, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 13, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 14, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 15, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 16, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 17, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 18, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 19, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 20, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 21, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 22, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 23, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 24, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 28, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 29, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 30, NULL, NULL);
INSERT INTO `admin_role_menu` VALUES (1, 31, NULL, NULL);

-- ----------------------------
-- Table structure for admin_role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_permissions`;
CREATE TABLE `admin_role_permissions`  (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `admin_role_permissions_role_id_permission_id_index`(`role_id`, `permission_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_role_permissions
-- ----------------------------
INSERT INTO `admin_role_permissions` VALUES (1, 1, NULL, NULL);

-- ----------------------------
-- Table structure for admin_role_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_users`;
CREATE TABLE `admin_role_users`  (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `admin_role_users_role_id_user_id_index`(`role_id`, `user_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_role_users
-- ----------------------------
INSERT INTO `admin_role_users` VALUES (1, 1, NULL, NULL);
INSERT INTO `admin_role_users` VALUES (1, 2, NULL, NULL);
INSERT INTO `admin_role_users` VALUES (1, 3, NULL, NULL);
INSERT INTO `admin_role_users` VALUES (1, 4, NULL, NULL);

-- ----------------------------
-- Table structure for admin_roles
-- ----------------------------
DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE `admin_roles`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `admin_roles_name_unique`(`name`) USING BTREE,
  UNIQUE INDEX `admin_roles_slug_unique`(`slug`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_roles
-- ----------------------------
INSERT INTO `admin_roles` VALUES (1, 'Administrator', 'administrator', '2020-11-20 07:35:42', '2020-11-20 07:35:42');

-- ----------------------------
-- Table structure for admin_user_permissions
-- ----------------------------
DROP TABLE IF EXISTS `admin_user_permissions`;
CREATE TABLE `admin_user_permissions`  (
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `admin_user_permissions_user_id_permission_id_index`(`user_id`, `permission_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_user_permissions
-- ----------------------------
INSERT INTO `admin_user_permissions` VALUES (2, 1, NULL, NULL);
INSERT INTO `admin_user_permissions` VALUES (3, 1, NULL, NULL);
INSERT INTO `admin_user_permissions` VALUES (4, 1, NULL, NULL);

-- ----------------------------
-- Table structure for admin_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `admin_users_username_unique`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_users
-- ----------------------------
INSERT INTO `admin_users` VALUES (1, 'admin', '$2y$10$anjMQTsnfbfrL1dYkrc2gOMTGjeRAU1M67GYjAaeQu71aeY8Q2bxa', 'Administrator', NULL, 'F3emBtaURH2g46awTJtbXk9JXdJhBdk65TSSDbkFY16S1oroj7Q2nlUDc2jJ', '2020-11-20 07:35:42', '2020-11-29 15:46:11');

-- ----------------------------
-- Table structure for customerservice_config
-- ----------------------------
DROP TABLE IF EXISTS `customerservice_config`;
CREATE TABLE `customerservice_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `account` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` tinyint(2) NULL DEFAULT NULL,
  `is_syn` tinyint(2) NOT NULL DEFAULT 0 COMMENT '是否同步 1：已同步 0：未同步',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of customerservice_config
-- ----------------------------

-- ----------------------------
-- Table structure for customerservice_record
-- ----------------------------
DROP TABLE IF EXISTS `customerservice_record`;
CREATE TABLE `customerservice_record`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_id` int(11) NOT NULL,
  `data_guest_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `syn_status` tinyint(2) NULL DEFAULT 0 COMMENT '同步状态',
  `customer_weixin` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `customer_mobile` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `customer_remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `customer_se` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '搜索引擎',
  `customer_kw` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `customer_styleName` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `data_customer` json NULL,
  `data_session` json NULL,
  `data_end` json NULL,
  `data_message` json NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of customerservice_record
-- ----------------------------

-- ----------------------------
-- Table structure for dingding_feedback
-- ----------------------------
DROP TABLE IF EXISTS `dingding_feedback`;
CREATE TABLE `dingding_feedback`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `dingding_user_id` int(11) NOT NULL,
  `blong` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `data_date` date NULL DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `feedback_short` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '反馈(简短)',
  `feedback_detail` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '跟进记录（EC跟进详细记录）',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dingding_feedback
-- ----------------------------

-- ----------------------------
-- Table structure for dingding_project
-- ----------------------------
DROP TABLE IF EXISTS `dingding_project`;
CREATE TABLE `dingding_project`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int(2) NOT NULL DEFAULT 1,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dingding_project
-- ----------------------------

-- ----------------------------
-- Table structure for dingding_user
-- ----------------------------
DROP TABLE IF EXISTS `dingding_user`;
CREATE TABLE `dingding_user`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` varchar(24) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `openid` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `unionid` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `mobile` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `position` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `department_id` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `department_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` tinyint(2) NULL DEFAULT NULL COMMENT '判断是否已固定，否则进行查询更新 1：无需更新',
  `active` varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '1' COMMENT '是否启用此用户 1：启用',
  `create_date` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dingding_user
-- ----------------------------

-- ----------------------------
-- Table structure for dingding_visit
-- ----------------------------
DROP TABLE IF EXISTS `dingding_visit`;
CREATE TABLE `dingding_visit`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `dingding_user_id` int(11) NOT NULL,
  `blong` varchar(24) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `create_date` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `update_date` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `visit_month` char(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '月份',
  `visit_date` date NULL DEFAULT NULL COMMENT '来访日期',
  `payment_date` date NULL DEFAULT NULL COMMENT '进款日期',
  `visit_brand` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '品牌',
  `visit_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '客户姓名',
  `visit_sex` varchar(24) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '性别',
  `visit_result` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '来访结果',
  `money_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '进款分类',
  `money_enter` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '入款',
  `pending_closing` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '待收尾款',
  `shop_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '店型',
  `invitee` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '邀约人',
  `negotiation_manager` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '谈判经理',
  `department` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '部门',
  `resource_platform` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '资源平台',
  `include_time` datetime(0) NULL DEFAULT NULL COMMENT '录入时间',
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `age` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `occupational` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '职业',
  `reason_not_signed` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '未签原因',
  `is_partner` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '是否有合伙人',
  `visit_cycle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '来访周期（距离拿资源时间）',
  `signing_cycle` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '签约周期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dingding_visit
-- ----------------------------

-- ----------------------------
-- Table structure for dingtalk_ec_relative
-- ----------------------------
DROP TABLE IF EXISTS `dingtalk_ec_relative`;
CREATE TABLE `dingtalk_ec_relative`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dingtalk_userid` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '钉钉的用户ID',
  `ec_userid` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'EC系统的用户ID',
  `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of dingtalk_ec_relative
-- ----------------------------

-- ----------------------------
-- Table structure for ec_change_log
-- ----------------------------
DROP TABLE IF EXISTS `ec_change_log`;
CREATE TABLE `ec_change_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `crmId` varchar(18) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` tinyint(8) NOT NULL,
  `userId` int(14) NOT NULL,
  `time` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = FIXED;

-- ----------------------------
-- Records of ec_change_log
-- ----------------------------

-- ----------------------------
-- Table structure for ec_customer
-- ----------------------------
DROP TABLE IF EXISTS `ec_customer`;
CREATE TABLE `ec_customer`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `crmId` varchar(18) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` tinyint(8) NOT NULL,
  `userId` int(14) NOT NULL,
  `time` datetime(0) NOT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `follow_records` json NULL,
  `last_follow_record` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `is_active` tinyint(2) UNSIGNED ZEROFILL NULL DEFAULT 00,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ec_customer
-- ----------------------------

-- ----------------------------
-- Table structure for ec_depts
-- ----------------------------
DROP TABLE IF EXISTS `ec_depts`;
CREATE TABLE `ec_depts`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deptId` int(11) NOT NULL,
  `deptName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `parentDeptId` int(11) NOT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ec_depts
-- ----------------------------

-- ----------------------------
-- Table structure for ec_users
-- ----------------------------
DROP TABLE IF EXISTS `ec_users`;
CREATE TABLE `ec_users`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deptId` int(11) NOT NULL,
  `deptName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` tinyint(2) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `userId` int(11) NOT NULL,
  `userName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_at` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ec_users
-- ----------------------------

-- ----------------------------
-- Table structure for email_config
-- ----------------------------
DROP TABLE IF EXISTS `email_config`;
CREATE TABLE `email_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `email_address` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email_password` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `host_port` varchar(48) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `move_folder` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `create_date` timestamp(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `status` tinyint(4) NULL DEFAULT 0,
  `remarks` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `type` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `keywords_sift` json NULL COMMENT '邮件检索关键字匹配',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'user_mail_config_info' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of email_config
-- ----------------------------

-- ----------------------------
-- Table structure for email_config_extra
-- ----------------------------
DROP TABLE IF EXISTS `email_config_extra`;
CREATE TABLE `email_config_extra`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_id` int(11) NOT NULL,
  `column_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `column_value` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of email_config_extra
-- ----------------------------

-- ----------------------------
-- Table structure for email_data
-- ----------------------------
DROP TABLE IF EXISTS `email_data`;
CREATE TABLE `email_data`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(44) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `from` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `data_date` varchar(28) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `from_mail` varchar(68) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '发件人',
  `mail_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `mail_date` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `mail_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `econfig_id` int(11) NOT NULL,
  `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `user_id` int(11) UNSIGNED NOT NULL,
  `is_census` tinyint(2) NULL DEFAULT 0 COMMENT '是否同步 1：已同步 0：未同步',
  `updated_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `econfig_id`(`econfig_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '邮箱数据集合' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of email_data
-- ----------------------------

-- ----------------------------
-- Table structure for email_pass
-- ----------------------------
DROP TABLE IF EXISTS `email_pass`;
CREATE TABLE `email_pass`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email_account` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime(0) NOT NULL,
  `updated_at` datetime(0) NOT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of email_pass
-- ----------------------------

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for mail_belong
-- ----------------------------
DROP TABLE IF EXISTS `mail_belong`;
CREATE TABLE `mail_belong`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '寻找关键字，确定所属',
  `belong` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `user_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of mail_belong
-- ----------------------------

-- ----------------------------
-- Table structure for mail_from
-- ----------------------------
DROP TABLE IF EXISTS `mail_from`;
CREATE TABLE `mail_from`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `from` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `user_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of mail_from
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for record_leave_robot_data
-- ----------------------------
DROP TABLE IF EXISTS `record_leave_robot_data`;
CREATE TABLE `record_leave_robot_data`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dingding_userid` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `key_word` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `res_word` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `updated_at` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of record_leave_robot_data
-- ----------------------------

-- ----------------------------
-- Table structure for res_config
-- ----------------------------
DROP TABLE IF EXISTS `res_config`;
CREATE TABLE `res_config`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NULL DEFAULT NULL,
  `custom_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '自定义的名称',
  `account_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `account` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '账号',
  `account_password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '密码',
  `host_port` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '端口',
  `status` tinyint(2) NULL DEFAULT NULL COMMENT '状态 0：关闭 1：开启',
  `belong` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '所属将来使用，自定义的关键字',
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '类型 （不确定）',
  `remarks` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `updated_at` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `last_para` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of res_config
-- ----------------------------

-- ----------------------------
-- Table structure for res_data
-- ----------------------------
DROP TABLE IF EXISTS `res_data`;
CREATE TABLE `res_data`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `config_id` int(11) NOT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `last_para` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `remarks` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `belong` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `data_json` json NULL,
  `data_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `data_phone` varchar(22) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `data_request_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `crmId` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'EC系统中对应的客户ID',
  `ec_userId` int(13) NULL DEFAULT NULL COMMENT 'EC系统中对应的用户ID',
  `exist_ec_userId` int(13) NULL DEFAULT NULL COMMENT '若报错为，已存在，则稍后查询相应的用户',
  `failureCause` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '若出现错误，可展现错误原因',
  `synchronize_para` json NULL COMMENT '同步到ec系统的参数',
  `synchronize_results` tinyint(2) NULL DEFAULT 0 COMMENT '分配到ec系统的结果 0：失败 1：成功',
  `feedback_status` tinyint(2) NULL DEFAULT 0 COMMENT '反馈的结果 0：未反馈 1：已反馈',
  `feedback_content` json NULL COMMENT '反馈的内容json',
  `short_feedback` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '简短反馈',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of res_data
-- ----------------------------

-- ----------------------------
-- Table structure for res_distribution_config
-- ----------------------------
DROP TABLE IF EXISTS `res_distribution_config`;
CREATE TABLE `res_distribution_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '正在使用的列表',
  `belong` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分配功能的项目所属',
  `recyclable_list` json NULL COMMENT '可循环列表',
  `active_list` json NULL COMMENT '正在使用的列表',
  `except_list` json NULL COMMENT '除外的列表',
  `recyclable` tinyint(2) NULL DEFAULT 0 COMMENT '是否可循环 0：否 1：是',
  `status` tinyint(2) NULL DEFAULT 0 COMMENT '状态值 1：可使用 0：暂停',
  `auto_distribute_status` tinyint(2) NULL DEFAULT 0 COMMENT '是否启用自动分配',
  `auto_distribute_list` json NULL COMMENT '自动分配列表',
  `except_auto_account_list` json NULL COMMENT '除外的账号列表',
  `enable_time` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '营业时间开始',
  `disbale_time` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '营业时间结束',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of res_distribution_config
-- ----------------------------

-- ----------------------------
-- Table structure for res_distribution_log
-- ----------------------------
DROP TABLE IF EXISTS `res_distribution_log`;
CREATE TABLE `res_distribution_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ec_userId` int(11) NULL DEFAULT NULL COMMENT 'EC系统中对应的用户ID',
  `failureCause` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '若出现错误，可展现错误原因',
  `synchronize_para` json NULL COMMENT '同步到ec系统的参数',
  `synchronize_results` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '同步到ec系统的结果 0：失败 1：成功',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `is_auto` tinyint(2) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of res_distribution_log
-- ----------------------------

-- ----------------------------
-- Table structure for res_upload_data
-- ----------------------------
DROP TABLE IF EXISTS `res_upload_data`;
CREATE TABLE `res_upload_data`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NULL DEFAULT NULL,
  `file_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of res_upload_data
-- ----------------------------

-- ----------------------------
-- Table structure for short_feedback_relative
-- ----------------------------
DROP TABLE IF EXISTS `short_feedback_relative`;
CREATE TABLE `short_feedback_relative`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `find_keywords_list` json NULL COMMENT '需要查询的关键字列表',
  `short_feeback` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 20 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of short_feedback_relative
-- ----------------------------
INSERT INTO `short_feedback_relative` VALUES (1, '[\"意向客户\", \"意向\"]', '意向客户', '2021-01-01 11:27:31', '2021-01-01 13:37:35');
INSERT INTO `short_feedback_relative` VALUES (2, '[\"正常咨询\"]', '正常咨询', '2021-01-01 11:27:31', '2021-01-01 13:06:09');
INSERT INTO `short_feedback_relative` VALUES (3, '[\"加微信发资料\"]', '已加微信发资料', '2021-01-01 11:27:31', '2021-01-01 13:42:09');
INSERT INTO `short_feedback_relative` VALUES (4, '[\"在忙\"]', '在忙，加微信', '2021-01-01 11:27:31', '2021-01-01 13:42:19');
INSERT INTO `short_feedback_relative` VALUES (5, '[\"回电\"]', '预约回电', '2021-01-01 11:27:31', '2021-01-01 13:40:16');
INSERT INTO `short_feedback_relative` VALUES (6, '[\"未接\"]', '多次未接（3次及以上）', '2021-01-01 11:27:31', '2021-01-01 13:40:29');
INSERT INTO `short_feedback_relative` VALUES (7, '[\"未接\"]', '未接', '2021-01-01 11:27:31', '2021-01-01 13:40:40');
INSERT INTO `short_feedback_relative` VALUES (8, '[\"接了就挂\"]', '接了就挂', '2021-01-01 11:27:31', '2021-01-01 13:40:53');
INSERT INTO `short_feedback_relative` VALUES (9, '[\"恶搞\"]', '未咨询，被黑', '2021-01-01 11:27:31', '2021-01-01 13:19:10');
INSERT INTO `short_feedback_relative` VALUES (10, '[\"关机\"]', '关机', '2021-01-01 11:27:31', '2021-01-01 13:41:06');
INSERT INTO `short_feedback_relative` VALUES (11, '[\"停机\"]', '停机', '2021-01-01 11:27:31', '2021-01-01 13:41:19');
INSERT INTO `short_feedback_relative` VALUES (12, '[\"空号\"]', '空号', '2021-01-01 11:27:31', '2021-01-01 13:41:33');
INSERT INTO `short_feedback_relative` VALUES (13, '[\"没钱\"]', '没钱，费用接受不了', '2021-01-01 11:27:31', '2021-01-01 13:41:45');
INSERT INTO `short_feedback_relative` VALUES (16, '[\"无意向\"]', '无意向', '2021-01-01 11:27:31', '2021-01-01 13:43:05');
INSERT INTO `short_feedback_relative` VALUES (17, '[\"同行\"]', '同行', '2021-01-01 11:27:31', '2021-01-01 13:43:35');
INSERT INTO `short_feedback_relative` VALUES (18, '[\"买料\"]', '学技术买设备', '2021-01-01 11:27:31', '2021-01-01 13:43:49');
INSERT INTO `short_feedback_relative` VALUES (19, '[\"推广\"]', '推广推销', '2021-01-01 11:27:31', '2021-01-01 13:44:02');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp(0) NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------


-- ----------------------------
-- Table structure for dingding_manage_relative
-- ----------------------------
DROP TABLE IF EXISTS `dingding_manage_relative`;
CREATE TABLE `dingding_manage_relative` (
    `id` int(11) NOT NULL,
    `manager_id` int(11) DEFAULT NULL COMMENT '管理员id',
    `member_id_list` json DEFAULT NULL COMMENT '成员的id列表',
    `status` tinyint(2) DEFAULT '0' COMMENT '状态1：启用 0：禁用',
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of dingding_manage_relative
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;


ALTER TABLE `dingding_user` CHANGE `create_date` `create_date` DATETIME NULL DEFAULT NULL;
