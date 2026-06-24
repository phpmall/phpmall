import { Card, Col, Row, Statistic, Table, Typography } from "antd";
import {
  DashboardOutlined,
  ShoppingCartOutlined,
  UserOutlined,
  ShopOutlined,
} from "@ant-design/icons";
import { dashboardStats, recentOrders } from "../data/mock";

const { Title } = Typography;

const statIcons = [
  <DashboardOutlined key={0} />,
  <ShoppingCartOutlined key={1} />,
  <UserOutlined key={2} />,
  <ShopOutlined key={3} />,
];

const columns = [
  { title: "订单号", dataIndex: "id", key: "id" },
  { title: "用户", dataIndex: "user", key: "user" },
  { title: "商家", dataIndex: "merchant", key: "merchant" },
  { title: "金额", dataIndex: "amount", key: "amount", render: (v: number) => `¥${(v / 100).toFixed(2)}` },
  { title: "状态", dataIndex: "status", key: "status" },
  { title: "时间", dataIndex: "time", key: "time" },
];

function Dashboard() {
  return (
    <div>
      <Title level={4}>数据概览</Title>
      <Row gutter={[16, 16]}>
        {dashboardStats.map((stat, index) => (
          <Col xs={24} sm={12} lg={6} key={stat.title}>
            <Card>
              <Statistic
                title={stat.title}
                value={stat.value}
                prefix={stat.prefix}
                suffix={stat.suffix}
                valueStyle={{ color: "#1677ff" }}
              />
              <div style={{ marginTop: 8, color: "#999" }}>{statIcons[index]}</div>
            </Card>
          </Col>
        ))}
      </Row>

      <Card title="最近订单" style={{ marginTop: 24 }}>
        <Table
          columns={columns}
          dataSource={recentOrders}
          rowKey="id"
          pagination={{ pageSize: 5 }}
        />
      </Card>

      <Row gutter={[16, 16]} style={{ marginTop: 24 }}>
        <Col xs={24} lg={12}>
          <Card title="GMV 趋势">趋势图表占位</Card>
        </Col>
        <Col xs={24} lg={12}>
          <Card title="订单来源">来源图表占位</Card>
        </Col>
      </Row>
    </div>
  );
}

export default Dashboard;
