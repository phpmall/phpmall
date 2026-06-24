import { useState } from "react";
import { Outlet, useLocation, useNavigate } from "react-router-dom";
import {
  Layout,
  Menu,
  theme,
  Avatar,
  Dropdown,
  Space,
  Typography,
} from "antd";
import type { MenuProps } from "antd";
import {
  DashboardOutlined,
  ShopOutlined,
  ShoppingOutlined,
  FileTextOutlined,
  GiftOutlined,
  ShareAltOutlined,
  DollarCircleOutlined,
  ReadOutlined,
  SettingOutlined,
  UserOutlined,
  LogoutOutlined,
  MenuFoldOutlined,
  MenuUnfoldOutlined,
} from "@ant-design/icons";

const { Header, Sider, Content } = Layout;
const { Title } = Typography;

type MenuItem = Required<MenuProps>["items"][number];

const items: MenuItem[] = [
  { key: "/dashboard", icon: <DashboardOutlined />, label: "数据概览" },
  {
    key: "/merchant",
    icon: <ShopOutlined />,
    label: "商家管理",
    children: [
      { key: "/merchant/list", label: "商家列表" },
      { key: "/merchant/audit", label: "入驻审核" },
      { key: "/merchant/settlement", label: "结算管理" },
    ],
  },
  {
    key: "/product",
    icon: <ShoppingOutlined />,
    label: "商品管理",
    children: [
      { key: "/product/list", label: "商品列表" },
      { key: "/product/category", label: "分类管理" },
      { key: "/product/brand", label: "品牌管理" },
    ],
  },
  {
    key: "/order",
    icon: <FileTextOutlined />,
    label: "订单管理",
    children: [
      { key: "/order/list", label: "订单列表" },
      { key: "/order/refund", label: "售后退款" },
      { key: "/order/delivery", label: "发货管理" },
    ],
  },
  {
    key: "/marketing",
    icon: <GiftOutlined />,
    label: "营销中心",
    children: [
      { key: "/marketing/coupon", label: "优惠券" },
      { key: "/marketing/seckill", label: "秒杀活动" },
      { key: "/marketing/discount", label: "满减满折" },
    ],
  },
  {
    key: "/distribution",
    icon: <ShareAltOutlined />,
    label: "分销管理",
    children: [
      { key: "/distribution/distributor", label: "分销员" },
      { key: "/distribution/commission", label: "佣金管理" },
    ],
  },
  {
    key: "/finance",
    icon: <DollarCircleOutlined />,
    label: "财务管理",
    children: [
      { key: "/finance/overview", label: "资金概览" },
      { key: "/finance/reconciliation", label: "对账管理" },
      { key: "/finance/withdraw", label: "提现审核" },
    ],
  },
  {
    key: "/content",
    icon: <ReadOutlined />,
    label: "内容管理",
    children: [
      { key: "/content/banner", label: "Banner 管理" },
      { key: "/content/article", label: "文章管理" },
      { key: "/content/notice", label: "公告管理" },
    ],
  },
  {
    key: "/system",
    icon: <SettingOutlined />,
    label: "系统设置",
    children: [
      { key: "/system/user", label: "管理员" },
      { key: "/system/role", label: "角色权限" },
      { key: "/system/config", label: "参数配置" },
      { key: "/system/log", label: "操作日志" },
    ],
  },
];

function AdminLayout() {
  const [collapsed, setCollapsed] = useState(false);
  const location = useLocation();
  const navigate = useNavigate();
  const {
    token: { colorBgContainer, borderRadiusLG },
  } = theme.useToken();

  const onClick: MenuProps["onClick"] = (e) => {
    navigate(e.key);
  };

  return (
    <Layout style={{ minHeight: "100vh" }}>
      <Sider trigger={null} collapsible collapsed={collapsed} theme="light">
        <div
          style={{
            height: 64,
            display: "flex",
            alignItems: "center",
            justifyContent: "center",
            borderBottom: "1px solid #f0f0f0",
          }}
        >
          <Title level={collapsed ? 5 : 4} style={{ margin: 0 }}>
            {collapsed ? "Admin" : "PHPMall Admin"}
          </Title>
        </div>
        <Menu
          theme="light"
          mode="inline"
          selectedKeys={[location.pathname]}
          openKeys={location.pathname.split("/").slice(0, 2).join("/") === "/dashboard" ? undefined : [`/${location.pathname.split("/")[1]}`]}
          items={items}
          onClick={onClick}
        />
      </Sider>
      <Layout>
        <Header
          style={{
            padding: "0 24px",
            background: colorBgContainer,
            display: "flex",
            alignItems: "center",
            justifyContent: "space-between",
            borderBottom: "1px solid #f0f0f0",
          }}
        >
          <div
            style={{ cursor: "pointer", fontSize: 18 }}
            onClick={() => setCollapsed(!collapsed)}
          >
            {collapsed ? <MenuUnfoldOutlined /> : <MenuFoldOutlined />}
          </div>
          <Dropdown
            menu={{
              items: [
                { key: "profile", label: "个人中心", icon: <UserOutlined /> },
                { key: "logout", label: "退出登录", icon: <LogoutOutlined /> },
              ],
            }}
          >
            <Space style={{ cursor: "pointer" }}>
              <Avatar icon={<UserOutlined />} />
              <span>平台管理员</span>
            </Space>
          </Dropdown>
        </Header>
        <Content
          style={{
            margin: 24,
            padding: 24,
            background: colorBgContainer,
            borderRadius: borderRadiusLG,
            minHeight: 280,
          }}
        >
          <Outlet />
        </Content>
      </Layout>
    </Layout>
  );
}

export default AdminLayout;
