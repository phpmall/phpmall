import { Button, Card, Space, Table, Typography } from "antd";

const { Title } = Typography;

const data = [
  { id: "O20260624001", user: "张三", merchant: "Apple 官方旗舰店", status: "待发货", time: "2026-06-24 10:23" },
  { id: "O20260624006", user: "周八", merchant: "小米之家", status: "待发货", time: "2026-06-24 07:30" },
];

const columns = [
  { title: "订单号", dataIndex: "id", key: "id" },
  { title: "用户", dataIndex: "user", key: "user" },
  { title: "商家", dataIndex: "merchant", key: "merchant" },
  { title: "状态", dataIndex: "status", key: "status" },
  { title: "下单时间", dataIndex: "time", key: "time" },
  {
    title: "操作",
    key: "action",
    render: () => (
      <Space>
        <Button type="primary">发货</Button>
        <Button type="link">详情</Button>
      </Space>
    ),
  },
];

function OrderDelivery() {
  return (
    <div>
      <Title level={4}>发货管理</Title>
      <Card>
        <Table columns={columns} dataSource={data} rowKey="id" />
      </Card>
    </div>
  );
}

export default OrderDelivery;
