export const dashboardStats = [
  { title: "今日 GMV", value: 128900, prefix: "¥", suffix: "" },
  { title: "今日订单", value: 342, suffix: "单" },
  { title: "今日新增用户", value: 128, suffix: "人" },
  { title: "入驻商家", value: 56, suffix: "家" },
];

export const recentOrders = [
  { id: "O20260624001", user: "张三", amount: 19900, status: "待发货", merchant: "Apple 官方旗舰店", time: "2026-06-24 10:23" },
  { id: "O20260624002", user: "李四", amount: 5600, status: "已完成", merchant: "小米之家", time: "2026-06-24 09:45" },
  { id: "O20260624003", user: "王五", amount: 128000, status: "待付款", merchant: "华为授权店", time: "2026-06-24 09:12" },
  { id: "O20260624004", user: "赵六", amount: 3200, status: "售后中", merchant: "耐克旗舰店", time: "2026-06-24 08:56" },
  { id: "O20260624005", user: "孙七", amount: 89900, status: "已完成", merchant: "Sony 旗舰店", time: "2026-06-24 08:30" },
];

export const merchantList = [
  { id: 1, name: "Apple 官方旗舰店", contact: "张经理", phone: "13800138000", status: "正常", settleCycle: "T+7", gmv: 12800000 },
  { id: 2, name: "小米之家", contact: "李主管", phone: "13800138001", status: "正常", settleCycle: "T+7", gmv: 5600000 },
  { id: 3, name: "华为授权店", contact: "王老板", phone: "13800138002", status: "冻结", settleCycle: "T+14", gmv: 3200000 },
  { id: 4, name: "耐克旗舰店", contact: "赵运营", phone: "13800138003", status: "审核中", settleCycle: "T+7", gmv: 0 },
];

export const auditList = [
  { id: 1, name: "李宁官方旗舰店", contact: "刘经理", applyTime: "2026-06-23 16:30", status: "待审核" },
  { id: 2, name: "Adidas 旗舰店", contact: "陈主管", applyTime: "2026-06-23 14:20", status: "待审核" },
  { id: 3, name: "优衣库官方店", contact: "周运营", applyTime: "2026-06-22 11:00", status: "已通过" },
];

export const productList = [
  { id: 1, name: "iPhone 16 Pro", category: "手机数码", price: 899900, stock: 120, status: "上架中", merchant: "Apple 官方旗舰店" },
  { id: 2, name: "小米 15", category: "手机数码", price: 399900, stock: 80, status: "上架中", merchant: "小米之家" },
  { id: 3, name: "Nike Air Max", category: "运动鞋服", price: 89900, stock: 0, status: "已下架", merchant: "耐克旗舰店" },
  { id: 4, name: "Sony WH-1000XM6", category: "影音娱乐", price: 249900, stock: 45, status: "审核中", merchant: "Sony 旗舰店" },
];

export const orderList = [
  { id: "O20260624001", user: "张三", amount: 19900, status: "待发货", merchant: "Apple 官方旗舰店", time: "2026-06-24 10:23" },
  { id: "O20260624002", user: "李四", amount: 5600, status: "已完成", merchant: "小米之家", time: "2026-06-24 09:45" },
  { id: "O20260624003", user: "王五", amount: 128000, status: "待付款", merchant: "华为授权店", time: "2026-06-24 09:12" },
  { id: "O20260624004", user: "赵六", amount: 3200, status: "售后中", merchant: "耐克旗舰店", time: "2026-06-24 08:56" },
];

export const refundList = [
  { id: "R20260624001", orderId: "O20260624004", user: "赵六", amount: 3200, reason: "商品质量问题", status: "待处理", time: "2026-06-24 09:10" },
  { id: "R20260624002", orderId: "O20260623008", user: "钱七", amount: 12900, reason: "不想要了", status: "已同意", time: "2026-06-23 18:30" },
];

export const couponList = [
  { id: 1, name: "新用户满100减20", type: "满减券", threshold: 10000, value: 2000, status: "进行中", time: "2026-06-01 ~ 2026-06-30" },
  { id: 2, name: "618 全场8折", type: "折扣券", threshold: 0, value: 20, status: "已结束", time: "2026-06-18 ~ 2026-06-20" },
];

export const distributorList = [
  { id: 1, name: "推广达人 A", phone: "13800138010", totalCommission: 56000, settled: 40000, status: "正常" },
  { id: 2, name: "推广达人 B", phone: "13800138011", totalCommission: 23000, settled: 15000, status: "冻结" },
];

export const financeOverview = [
  { title: "平台总流水", value: 128900000, prefix: "¥" },
  { title: "平台收入", value: 1289000, prefix: "¥" },
  { title: "待结算金额", value: 5600000, prefix: "¥" },
  { title: "提现中金额", value: 320000, prefix: "¥" },
];

export const withdrawList = [
  { id: "W20260624001", merchant: "Apple 官方旗舰店", amount: 500000, status: "待审核", time: "2026-06-24 10:00" },
  { id: "W20260624002", merchant: "小米之家", amount: 230000, status: "已通过", time: "2026-06-24 09:30" },
];

export const systemUsers = [
  { id: 1, username: "admin", nickname: "超级管理员", role: "super_admin", status: "启用" },
  { id: 2, username: "operator1", nickname: "运营小王", role: "operator", status: "启用" },
  { id: 3, username: "auditor1", nickname: "审核小李", role: "auditor", status: "禁用" },
];

export const systemLogs = [
  { id: 1, user: "admin", action: "登录系统", ip: "192.168.1.1", time: "2026-06-24 10:00:00" },
  { id: 2, user: "operator1", action: "审核通过商家【李宁官方旗舰店】", ip: "192.168.1.2", time: "2026-06-24 09:45:00" },
  { id: 3, user: "admin", action: "修改系统参数【运费模板】", ip: "192.168.1.1", time: "2026-06-24 09:30:00" },
];

export const bannerList = [
  { id: 1, title: "618 年中大促", position: "首页顶部", status: "启用", sort: 1 },
  { id: 2, title: "新品首发", position: "首页中部", status: "启用", sort: 2 },
  { id: 3, title: "会员专享", position: "我的页面", status: "禁用", sort: 3 },
];

export const articleList = [
  { id: 1, title: "平台入驻协议", category: "协议", status: "发布", time: "2026-06-01 10:00" },
  { id: 2, title: "退换货政策", category: "帮助", status: "发布", time: "2026-06-02 14:00" },
];
