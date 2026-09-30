/*
 Navicat Premium Dump SQL

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80406 (8.4.6)
 Source Host           : 127.0.0.1:3306
 Source Schema         : phpmall

 Target Server Type    : MySQL
 Target Server Version : 80406 (8.4.6)
 File Encoding         : 65001

 Date: 30/09/2026 14:59:59
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for carts
-- ----------------------------
DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL COMMENT '用户ID',
  `merchant_id` bigint UNSIGNED NOT NULL COMMENT '商家ID',
  `product_id` bigint UNSIGNED NOT NULL COMMENT '商品ID',
  `sku_id` bigint UNSIGNED NOT NULL COMMENT 'SKU ID',
  `quantity` int UNSIGNED NOT NULL DEFAULT 1 COMMENT '数量',
  `is_selected` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否选中',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `udx_carts_user_id_sku_id`(`user_id` ASC, `sku_id` ASC) USING BTREE,
  INDEX `idx_carts_user_id`(`user_id` ASC) USING BTREE,
  INDEX `idx_carts_user_id_merchant_id`(`user_id` ASC, `merchant_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for coupons
-- ----------------------------
DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'NULL=平台券，有值=商家券',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '优惠券名称',
  `type` tinyint UNSIGNED NOT NULL COMMENT '1=满减券 2=折扣券 3=无门槛券 4=兑换券',
  `scope` tinyint UNSIGNED NOT NULL COMMENT '1=全平台 2=指定分类 3=指定商品 4=指定商家',
  `threshold_amount` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '使用门槛（分），0=无门槛',
  `discount_amount` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '优惠金额（分，type=1,3时）',
  `discount_rate` decimal(5, 4) NULL DEFAULT NULL COMMENT '折扣率（type=2时，0.85=85折）',
  `max_discount_amount` bigint UNSIGNED NULL DEFAULT NULL COMMENT '折扣券最高优惠金额（分）',
  `total_quantity` int UNSIGNED NOT NULL COMMENT '总发放数量',
  `remaining_quantity` int UNSIGNED NOT NULL COMMENT '剩余数量',
  `limit_per_user` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '每人限领',
  `start_time` timestamp NOT NULL COMMENT '生效时间',
  `end_time` timestamp NOT NULL COMMENT '过期时间',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '0=停用 1=启用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_coupons_merchant_id`(`merchant_id` ASC) USING BTREE,
  INDEX `idx_coupons_status_start_time_end_time`(`status` ASC, `start_time` ASC, `end_time` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for inventory
-- ----------------------------
DROP TABLE IF EXISTS `inventory`;
CREATE TABLE `inventory`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` bigint UNSIGNED NOT NULL COMMENT '供应商商户ID',
  `supplier_product_id` bigint UNSIGNED NOT NULL COMMENT '供货商品ID',
  `stock` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '可用库存',
  `locked_stock` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '锁定库存',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `inventory_supplier_product_id_unique`(`supplier_product_id` ASC) USING BTREE,
  INDEX `idx_inventory_merchant_stock`(`merchant_id` ASC, `stock` ASC) USING BTREE,
  INDEX `inventory_merchant_id_index`(`merchant_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for merchant_staffs
-- ----------------------------
DROP TABLE IF EXISTS `merchant_staffs`;
CREATE TABLE `merchant_staffs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` bigint UNSIGNED NOT NULL COMMENT '商家ID',
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登录名',
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'bcrypt 密码哈希',
  `real_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '姓名',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '手机号',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '0=禁用 1=正常',
  `last_login_at` timestamp NULL DEFAULT NULL COMMENT '最后登录时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_merchant_staffs_merchant_id`(`merchant_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for merchants
-- ----------------------------
DROP TABLE IF EXISTS `merchants`;
CREATE TABLE `merchants`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '店铺名称',
  `logo_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '店铺Logo',
  `cover_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '店铺封面',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '店铺简介',
  `contact_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '联系手机',
  `contact_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '联系人',
  `business_license_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '营业执照号',
  `business_license_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '营业执照图片',
  `legal_person_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '法人姓名',
  `legal_person_id_card` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '法人身份证（AES）',
  `settlement_cycle` tinyint UNSIGNED NOT NULL DEFAULT 7 COMMENT '结算周期 T+N 天',
  `settlement_rate` decimal(5, 4) NOT NULL DEFAULT 0.0500 COMMENT '平台抽成比例（5%）',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=待审核 1=正常 2=冻结 3=关闭',
  `audit_status` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=待审核 1=通过 2=拒绝',
  `audit_remark` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '审核备注',
  `frozen_reason` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '冻结原因',
  `frozen_until` timestamp NULL DEFAULT NULL COMMENT '冻结截止时间',
  `total_sales_amount` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '累计销售额（分）',
  `total_order_count` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '累计订单数',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_merchants_status`(`status` ASC) USING BTREE,
  INDEX `idx_merchants_audit_status`(`audit_status` ASC) USING BTREE,
  INDEX `idx_merchants_created_at`(`created_at` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for messages
-- ----------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT '用户ID，NULL=广播消息',
  `type` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '1=系统通知 2=订单通知 3=营销消息 4=活动消息',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '消息标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '消息内容',
  `is_read` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=未读 1=已读',
  `read_at` timestamp NULL DEFAULT NULL COMMENT '阅读时间',
  `link_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '跳转链接',
  `extra_data` json NULL COMMENT '扩展数据',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '0=隐藏 1=显示',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_messages_user_id`(`user_id` ASC) USING BTREE,
  INDEX `idx_messages_user_id_is_read`(`user_id` ASC, `is_read` ASC) USING BTREE,
  INDEX `idx_messages_type`(`type` ASC) USING BTREE,
  INDEX `idx_messages_created_at`(`created_at` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for model_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `permission_id` bigint UNSIGNED NOT NULL COMMENT '权限ID',
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '模型类型',
  `model_id` bigint UNSIGNED NOT NULL COMMENT '模型ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `udx_model_has_permissions`(`permission_id` ASC, `model_type` ASC, `model_id` ASC) USING BTREE,
  INDEX `idx_model_has_permissions_model`(`model_type` ASC, `model_id` ASC) USING BTREE,
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '模型直接权限关联表（spatie 风格）' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for order_items
-- ----------------------------
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL COMMENT '订单ID',
  `product_id` bigint UNSIGNED NOT NULL COMMENT '商品ID',
  `sku_id` bigint UNSIGNED NOT NULL COMMENT 'SKU ID',
  `merchant_id` bigint UNSIGNED NOT NULL COMMENT '商家ID',
  `product_title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品标题（快照）',
  `product_image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品主图（快照）',
  `sku_specs` json NOT NULL COMMENT '规格快照',
  `price` bigint UNSIGNED NOT NULL COMMENT '下单时单价（分）',
  `quantity` int UNSIGNED NOT NULL COMMENT '数量',
  `total_amount` bigint UNSIGNED NOT NULL COMMENT '小计（分）',
  `discount_amount` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '优惠分摊（分）',
  `refund_amount` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '已退款金额（分）',
  `refund_status` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=无 1=申请中 2=已退款 3=拒绝',
  `is_commented` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否已评价',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_order_items_order_id`(`order_id` ASC) USING BTREE,
  INDEX `idx_order_items_product_id`(`product_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for order_refunds
-- ----------------------------
DROP TABLE IF EXISTS `order_refunds`;
CREATE TABLE `order_refunds`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `refund_no` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '退款单号',
  `order_id` bigint UNSIGNED NOT NULL COMMENT '订单ID',
  `order_item_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT '订单商品项ID，可为空（整单退款）',
  `user_id` bigint UNSIGNED NOT NULL COMMENT '用户ID',
  `merchant_id` bigint UNSIGNED NOT NULL COMMENT '商家ID',
  `type` tinyint UNSIGNED NOT NULL COMMENT '1=仅退款 2=退货退款 3=换货',
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '退款原因',
  `reason_type` tinyint UNSIGNED NOT NULL COMMENT '原因分类',
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '补充说明',
  `images` json NULL COMMENT '凭证图片',
  `apply_amount` bigint UNSIGNED NOT NULL COMMENT '申请退款金额（分）',
  `refund_amount` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '实际退款金额（分）',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=待商家处理 1=商家同意 2=商家拒绝 3=退货中 4=平台介入 5=已退款 6=已拒绝 7=用户撤销',
  `merchant_remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '商家处理备注',
  `platform_remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '平台仲裁备注',
  `return_express_company` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '退货快递公司',
  `return_express_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '退货快递单号',
  `return_ship_time` timestamp NULL DEFAULT NULL COMMENT '用户退货发货时间',
  `merchant_receipt_time` timestamp NULL DEFAULT NULL COMMENT '商家收到退货时间',
  `refund_time` timestamp NULL DEFAULT NULL COMMENT '实际退款时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `udx_order_refunds_refund_no`(`refund_no` ASC) USING BTREE,
  INDEX `idx_order_refunds_order_id`(`order_id` ASC) USING BTREE,
  INDEX `idx_order_refunds_user_id`(`user_id` ASC) USING BTREE,
  INDEX `idx_order_refunds_merchant_id_status`(`merchant_id` ASC, `status` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for order_shipments
-- ----------------------------
DROP TABLE IF EXISTS `order_shipments`;
CREATE TABLE `order_shipments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL COMMENT '订单ID',
  `merchant_id` bigint UNSIGNED NOT NULL COMMENT '商家ID',
  `logistics_company` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '物流公司',
  `tracking_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '物流单号',
  `remark` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '发货备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_order_shipments_order_id`(`order_id` ASC) USING BTREE,
  INDEX `idx_order_shipments_merchant_id`(`merchant_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_no` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '订单号',
  `user_id` bigint UNSIGNED NOT NULL COMMENT '用户ID',
  `merchant_id` bigint UNSIGNED NOT NULL COMMENT '商家ID',
  `parent_order_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT '父订单ID（拆单）',
  `order_type` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '1=普通 2=秒杀 3=拼团 4=分销',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 10 COMMENT '10=待付款 20=已支付 30=待发货 40=已发货 50=待收货 60=已收货 70=已完成 80=已取消 90=退款中 100=已退款',
  `pay_status` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=未支付 20=已支付 30=部分退款 100=全额退款',
  `refund_status` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=无退款 10=退款申请中 20=退款中 30=已退款 40=拒绝退款',
  `product_amount` bigint UNSIGNED NOT NULL COMMENT '商品总金额（分）',
  `discount_amount` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '优惠金额（分）',
  `freight_amount` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '运费（分）',
  `pay_amount` bigint UNSIGNED NOT NULL COMMENT '实付金额（分）',
  `pay_method` tinyint UNSIGNED NULL DEFAULT NULL COMMENT '1=微信 2=支付宝 3=余额 4=银联',
  `pay_time` timestamp NULL DEFAULT NULL COMMENT '支付时间',
  `pay_transaction_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '第三方支付流水号',
  `ship_time` timestamp NULL DEFAULT NULL COMMENT '发货时间',
  `receipt_time` timestamp NULL DEFAULT NULL COMMENT '确认收货时间',
  `cancel_time` timestamp NULL DEFAULT NULL COMMENT '取消时间',
  `cancel_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '取消原因',
  `auto_receipt_time` timestamp NULL DEFAULT NULL COMMENT '自动确认收货时间',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '用户备注',
  `source` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '来源 1=PC 2=H5 3=小程序 4=App',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `seller_remark` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '商家备注',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `udx_orders_order_no`(`order_no` ASC) USING BTREE,
  INDEX `idx_orders_user_id_status`(`user_id` ASC, `status` ASC) USING BTREE,
  INDEX `idx_orders_merchant_id_status`(`merchant_id` ASC, `status` ASC) USING BTREE,
  INDEX `idx_orders_status_pay_status`(`status` ASC, `pay_status` ASC) USING BTREE,
  INDEX `idx_orders_created_at`(`created_at` ASC) USING BTREE,
  INDEX `idx_orders_pay_time`(`pay_time` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for payment_refunds
-- ----------------------------
DROP TABLE IF EXISTS `payment_refunds`;
CREATE TABLE `payment_refunds`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `refund_no` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '退款单号',
  `payment_id` bigint UNSIGNED NOT NULL COMMENT '原支付记录ID',
  `order_id` bigint UNSIGNED NOT NULL COMMENT '订单ID',
  `order_refund_id` bigint UNSIGNED NOT NULL COMMENT '关联售后单',
  `amount` bigint UNSIGNED NOT NULL COMMENT '退款金额（分）',
  `channel` tinyint UNSIGNED NOT NULL COMMENT '原支付渠道',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=待退款 1=退款中 2=成功 3=失败',
  `refunded_at` timestamp NULL DEFAULT NULL COMMENT '退款成功时间',
  `channel_refund_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '渠道退款单号',
  `failure_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '失败原因',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `udx_payment_refunds_refund_no`(`refund_no` ASC) USING BTREE,
  INDEX `idx_payment_refunds_payment_id`(`payment_id` ASC) USING BTREE,
  INDEX `idx_payment_refunds_order_id`(`order_id` ASC) USING BTREE,
  INDEX `idx_payment_refunds_status`(`status` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for payments
-- ----------------------------
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_no` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '支付单号',
  `order_id` bigint UNSIGNED NOT NULL COMMENT '订单ID',
  `user_id` bigint UNSIGNED NOT NULL COMMENT '用户ID',
  `amount` bigint UNSIGNED NOT NULL COMMENT '支付金额（分）',
  `channel` tinyint UNSIGNED NOT NULL COMMENT '1=微信 2=支付宝 3=余额 4=银联',
  `channel_app_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '渠道AppID',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=待支付 1=支付中 2=成功 3=失败 4=关闭',
  `paid_at` timestamp NULL DEFAULT NULL COMMENT '支付成功时间',
  `transaction_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '第三方支付流水号',
  `failure_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '失败原因',
  `client_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '支付IP',
  `expired_at` timestamp NOT NULL COMMENT '支付过期时间',
  `notify_raw` json NULL COMMENT '渠道回调原始数据',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `udx_payments_payment_no`(`payment_no` ASC) USING BTREE,
  INDEX `idx_payments_order_id`(`order_id` ASC) USING BTREE,
  INDEX `idx_payments_transaction_id`(`transaction_id` ASC) USING BTREE,
  INDEX `idx_payments_status`(`status` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '权限标识',
  `display_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '权限名称',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '描述',
  `parent_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级ID',
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menu' COMMENT '类型：menu-菜单 button-按钮 api-接口',
  `route` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '路由/接口地址',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '图标',
  `sort` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1-正常 2-禁用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `permissions_name_unique`(`name` ASC) USING BTREE,
  INDEX `permissions_parent_id_index`(`parent_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '权限表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for product_categories
-- ----------------------------
DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE `product_categories`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '父分类ID，0=根',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分类名称',
  `icon_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '图标',
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `is_show` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否显示 0=否 1=是',
  `level` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '层级 1/2/3',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '层级路径',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_product_categories_parent_id`(`parent_id` ASC) USING BTREE,
  INDEX `idx_product_categories_is_show_sort_order`(`is_show` ASC, `sort_order` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for product_reviews
-- ----------------------------
DROP TABLE IF EXISTS `product_reviews`;
CREATE TABLE `product_reviews`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL COMMENT '订单ID',
  `order_item_id` bigint UNSIGNED NOT NULL COMMENT '订单商品项ID',
  `product_id` bigint UNSIGNED NOT NULL COMMENT '商品ID',
  `sku_id` bigint UNSIGNED NOT NULL COMMENT 'SKU ID',
  `user_id` bigint UNSIGNED NOT NULL COMMENT '用户ID',
  `merchant_id` bigint UNSIGNED NOT NULL COMMENT '商家ID',
  `rating` tinyint UNSIGNED NOT NULL COMMENT '1-5星',
  `content` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '评价内容',
  `images` json NULL COMMENT '评价图片',
  `is_anonymous` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否匿名',
  `is_append` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否追评',
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT '追评时指向原评价',
  `merchant_reply` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '商家回复',
  `merchant_reply_at` timestamp NULL DEFAULT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '0=隐藏 1=显示',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_product_reviews_product_id`(`product_id` ASC) USING BTREE,
  INDEX `idx_product_reviews_user_id`(`user_id` ASC) USING BTREE,
  INDEX `idx_product_reviews_order_item_id`(`order_item_id` ASC) USING BTREE,
  INDEX `idx_product_reviews_merchant_id`(`merchant_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for product_skus
-- ----------------------------
DROP TABLE IF EXISTS `product_skus`;
CREATE TABLE `product_skus`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL COMMENT '商品ID',
  `merchant_id` bigint UNSIGNED NOT NULL COMMENT '商家ID',
  `sku_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'SKU编码',
  `sku_specs` json NOT NULL COMMENT '规格组合',
  `price` bigint UNSIGNED NOT NULL COMMENT '售价（分）',
  `market_price` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '市场价（分）',
  `cost_price` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '成本价（分）',
  `stock` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '库存',
  `stock_alarm` int UNSIGNED NOT NULL DEFAULT 10 COMMENT '库存预警值',
  `weight` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '重量（克）',
  `image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'SKU独立图片',
  `sales_count` int UNSIGNED NOT NULL DEFAULT 0 COMMENT 'SKU销量',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '0=禁用 1=启用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_product_skus_product_id`(`product_id` ASC) USING BTREE,
  INDEX `idx_product_skus_merchant_id`(`merchant_id` ASC) USING BTREE,
  INDEX `idx_product_skus_status`(`status` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` bigint UNSIGNED NOT NULL COMMENT '商家ID',
  `category_id` bigint UNSIGNED NOT NULL COMMENT '分类ID',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品标题',
  `subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '副标题',
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '富文本详情',
  `main_image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '主图',
  `images` json NOT NULL COMMENT '相册',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=下架 1=上架',
  `audit_status` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=待审核 1=通过 2=拒绝',
  `audit_remark` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '审核备注',
  `min_price` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '最低售价（分）',
  `max_price` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '最高售价（分）',
  `cost_price` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '成本价（分）',
  `sales_count` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '销量',
  `stock_type` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '1=统一库存 2=SKU独立库存',
  `total_stock` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '总库存',
  `weight` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '重量（克）',
  `freight_template_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT '运费模板ID',
  `attributes` json NULL COMMENT '规格属性定义',
  `seo_title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'SEO标题',
  `seo_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'SEO关键词',
  `seo_description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'SEO描述',
  `is_hot` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否热销',
  `is_new` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否新品',
  `is_recommend` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否推荐',
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序权重',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_products_merchant_id_status`(`merchant_id` ASC, `status` ASC) USING BTREE,
  INDEX `idx_products_category_id_status_audit_status`(`category_id` ASC, `status` ASC, `audit_status` ASC) USING BTREE,
  INDEX `idx_products_audit_status`(`audit_status` ASC) USING BTREE,
  INDEX `idx_products_sort_order_created_at`(`sort_order` ASC, `created_at` ASC) USING BTREE,
  INDEX `idx_products_title`(`title` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for purchase_order_items
-- ----------------------------
DROP TABLE IF EXISTS `purchase_order_items`;
CREATE TABLE `purchase_order_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint UNSIGNED NOT NULL COMMENT '采购订单ID',
  `supplier_product_id` bigint UNSIGNED NOT NULL COMMENT '供货商品ID',
  `product_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品标题快照',
  `sku_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'SKU编码快照',
  `quantity` int UNSIGNED NOT NULL COMMENT '数量',
  `price` bigint UNSIGNED NOT NULL COMMENT '单价（分）',
  `total` bigint UNSIGNED NOT NULL COMMENT '小计（分）',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `purchase_order_items_purchase_order_id_index`(`purchase_order_id` ASC) USING BTREE,
  INDEX `purchase_order_items_supplier_product_id_index`(`supplier_product_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for purchase_orders
-- ----------------------------
DROP TABLE IF EXISTS `purchase_orders`;
CREATE TABLE `purchase_orders`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_no` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '采购单号',
  `merchant_id` bigint UNSIGNED NOT NULL COMMENT '供应商商户ID',
  `buyer_merchant_id` bigint UNSIGNED NOT NULL COMMENT '采购方商户ID',
  `total_amount` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '总金额（分）',
  `status` tinyint NOT NULL DEFAULT 10 COMMENT '10=待确认 20=已确认 30=已发货 40=已完成 50=已拒绝 60=已取消',
  `address` json NOT NULL COMMENT '收货地址快照',
  `logistics_company` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '物流公司',
  `logistics_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '物流单号',
  `shipped_at` timestamp NULL DEFAULT NULL COMMENT '发货时间',
  `completed_at` timestamp NULL DEFAULT NULL COMMENT '完成时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `purchase_orders_order_no_unique`(`order_no` ASC) USING BTREE,
  INDEX `idx_purchase_orders_merchant_status`(`merchant_id` ASC, `status` ASC) USING BTREE,
  INDEX `purchase_orders_merchant_id_index`(`merchant_id` ASC) USING BTREE,
  INDEX `purchase_orders_buyer_merchant_id_index`(`buyer_merchant_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for role_permission
-- ----------------------------
DROP TABLE IF EXISTS `role_permission`;
CREATE TABLE `role_permission`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` bigint UNSIGNED NOT NULL COMMENT '角色ID',
  `permission_id` bigint UNSIGNED NOT NULL COMMENT '权限ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `role_permission_role_id_permission_id_unique`(`role_id` ASC, `permission_id` ASC) USING BTREE,
  INDEX `role_permission_permission_id_foreign`(`permission_id` ASC) USING BTREE,
  CONSTRAINT `role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '角色权限关联表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '角色标识',
  `display_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '角色名称',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '描述',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1-正常 2-禁用',
  `sort` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `roles_name_unique`(`name` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for shops
-- ----------------------------
DROP TABLE IF EXISTS `shops`;
CREATE TABLE `shops`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` bigint UNSIGNED NOT NULL COMMENT '商家ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '店铺名称',
  `logo_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '店铺Logo',
  `cover_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '店铺封面',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '店铺简介',
  `contact_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '联系手机',
  `contact_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '联系人',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=待审核 1=正常 2=冻结 3=关闭',
  `audit_status` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=待审核 1=通过 2=拒绝',
  `audit_remark` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '审核备注',
  `frozen_reason` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '冻结原因',
  `frozen_until` timestamp NULL DEFAULT NULL COMMENT '冻结截止时间',
  `total_sales_amount` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '累计销售额（分）',
  `total_order_count` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '累计订单数',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_shops_merchant_id`(`merchant_id` ASC) USING BTREE,
  INDEX `idx_shops_status`(`status` ASC) USING BTREE,
  INDEX `idx_shops_audit_status`(`audit_status` ASC) USING BTREE,
  INDEX `idx_shops_created_at`(`created_at` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for supplier_contracts
-- ----------------------------
DROP TABLE IF EXISTS `supplier_contracts`;
CREATE TABLE `supplier_contracts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` bigint UNSIGNED NOT NULL COMMENT '供应商商户ID',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '合同标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '合同内容',
  `status` tinyint NOT NULL DEFAULT 0 COMMENT '0=未签署 1=已签署',
  `signed_at` timestamp NULL DEFAULT NULL COMMENT '签署时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `supplier_contracts_merchant_id_index`(`merchant_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for supplier_messages
-- ----------------------------
DROP TABLE IF EXISTS `supplier_messages`;
CREATE TABLE `supplier_messages`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` bigint UNSIGNED NOT NULL COMMENT '供应商商户ID',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '消息标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '消息内容',
  `is_read` tinyint NOT NULL DEFAULT 0 COMMENT '0=未读 1=已读',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_supplier_messages_merchant_read`(`merchant_id` ASC, `is_read` ASC) USING BTREE,
  INDEX `supplier_messages_merchant_id_index`(`merchant_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for supplier_products
-- ----------------------------
DROP TABLE IF EXISTS `supplier_products`;
CREATE TABLE `supplier_products`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` bigint UNSIGNED NOT NULL COMMENT '供应商商户ID',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品标题',
  `sku_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'SKU编码',
  `supply_price` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '供货价（分）',
  `stock` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '库存',
  `status` tinyint NOT NULL DEFAULT 0 COMMENT '0=下架 1=上架',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `udx_supplier_products_merchant_sku`(`merchant_id` ASC, `sku_code` ASC) USING BTREE,
  INDEX `idx_supplier_products_merchant_status`(`merchant_id` ASC, `status` ASC) USING BTREE,
  INDEX `supplier_products_merchant_id_index`(`merchant_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for supplier_profiles
-- ----------------------------
DROP TABLE IF EXISTS `supplier_profiles`;
CREATE TABLE `supplier_profiles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` bigint UNSIGNED NOT NULL COMMENT '关联商户ID',
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '企业名称',
  `business_license_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '营业执照号',
  `business_license_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '营业执照图片',
  `legal_person_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '法人姓名',
  `legal_person_idcard` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '法人身份证',
  `bank_account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '开户名',
  `bank_account_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '银行账号',
  `bank_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '开户行',
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '经营地址',
  `status` tinyint NOT NULL DEFAULT 0 COMMENT '0=待审核 1=通过 2=驳回',
  `reject_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '驳回原因',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `supplier_profiles_merchant_id_unique`(`merchant_id` ASC) USING BTREE,
  INDEX `idx_supplier_profiles_status`(`status` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for supplier_settlements
-- ----------------------------
DROP TABLE IF EXISTS `supplier_settlements`;
CREATE TABLE `supplier_settlements`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` bigint UNSIGNED NOT NULL COMMENT '供应商商户ID',
  `settlement_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '结算单号',
  `amount` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '结算金额（分）',
  `status` tinyint NOT NULL DEFAULT 0 COMMENT '0=待结算 1=已结算',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `supplier_settlements_settlement_no_unique`(`settlement_no` ASC) USING BTREE,
  INDEX `supplier_settlements_merchant_id_index`(`merchant_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for system_regions
-- ----------------------------
DROP TABLE IF EXISTS `system_regions`;
CREATE TABLE `system_regions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '父级地区编码',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '地区名称',
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '地区编码',
  `level` tinyint UNSIGNED NOT NULL COMMENT '地区层级:1省,2市,3区',
  `zip_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮编',
  `has_children` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否有子级',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `udx_system_regions_code`(`code` ASC) USING BTREE,
  INDEX `idx_system_regions_parent_code`(`parent_code` ASC) USING BTREE,
  INDEX `idx_system_regions_level`(`level` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_addresses
-- ----------------------------
DROP TABLE IF EXISTS `user_addresses`;
CREATE TABLE `user_addresses`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL COMMENT '用户ID',
  `contact_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '联系人姓名',
  `contact_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '联系人手机',
  `province` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '省',
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '市',
  `district` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '区/县',
  `detail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '详细地址',
  `zip_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '邮编',
  `is_default` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否默认：1-是 0-否',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_addresses_user_id_index`(`user_id` ASC) USING BTREE,
  CONSTRAINT `user_addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户收货地址表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_coupons
-- ----------------------------
DROP TABLE IF EXISTS `user_coupons`;
CREATE TABLE `user_coupons`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL COMMENT '用户ID',
  `coupon_id` bigint UNSIGNED NOT NULL COMMENT '优惠券ID',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=未使用 1=已使用 2=已过期 3=已作废',
  `used_order_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT '使用订单',
  `used_at` timestamp NULL DEFAULT NULL COMMENT '使用时间',
  `claim_time` timestamp NOT NULL COMMENT '领取时间',
  `expire_time` timestamp NOT NULL COMMENT '过期时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_user_coupons_user_id_status`(`user_id` ASC, `status` ASC) USING BTREE,
  INDEX `idx_user_coupons_coupon_id`(`coupon_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_notifications
-- ----------------------------
DROP TABLE IF EXISTS `user_notifications`;
CREATE TABLE `user_notifications`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL COMMENT '用户ID',
  `notification_id` bigint UNSIGNED NOT NULL COMMENT '通知ID',
  `read_at` timestamp NULL DEFAULT NULL COMMENT '阅读时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `udx_user_notifications_user_notification`(`user_id` ASC, `notification_id` ASC) USING BTREE,
  INDEX `idx_user_notifications_notification_id`(`notification_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user_role
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL COMMENT '用户ID',
  `role_id` bigint UNSIGNED NOT NULL COMMENT '角色ID',
  `user_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user' COMMENT '用户类型：user-买家 merchant-商家 admin-管理员',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `user_role_user_id_role_id_user_type_unique`(`user_id` ASC, `role_id` ASC, `user_type` ASC) USING BTREE,
  INDEX `user_role_role_id_foreign`(`role_id` ASC) USING BTREE,
  CONSTRAINT `user_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `user_role_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户角色关联表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '手机号',
  `phone_verified_at` timestamp NULL DEFAULT NULL COMMENT '手机号验证时间',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1-正常 2-禁用',
  `avatar` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '头像',
  `nickname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '昵称',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE,
  UNIQUE INDEX `users_phone_unique`(`phone` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for warehouses
-- ----------------------------
DROP TABLE IF EXISTS `warehouses`;
CREATE TABLE `warehouses`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_id` bigint UNSIGNED NOT NULL COMMENT '供应商商户ID',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '仓库名称',
  `contact_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '联系人',
  `contact_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '联系电话',
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '仓库地址',
  `status` tinyint NOT NULL DEFAULT 1 COMMENT '0=停用 1=启用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_warehouses_merchant_status`(`merchant_id` ASC, `status` ASC) USING BTREE,
  INDEX `warehouses_merchant_id_index`(`merchant_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
