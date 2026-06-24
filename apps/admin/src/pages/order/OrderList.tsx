import { Button, Card, Form, Input, Select, Space, Table, Tag, Typography } from "antd";
import { orderList } from "../../data/mock";

const { Title } = Typography;

const columns = [
  { title: "订单号", dataIndex: "id", key: "id" },
  { title: "用户", dataIndex: "user", key: "user" },
  { title: "商家", dataIndex: "merchant", key: "merchant" },
  {
    title: "金额",
    dataIndex: "amount",
    key: "amount",
    render: (v: number) => `¥${(v / 100).toFixed(2)}`,
  },
  {
    title: "状态",
    dataIndex: "status",
    key: "status",
    render: (status: string) => {
      const color = status === "已完成" ? "green" : status === "待付款" ? "orange" : "blue";
      return <Tag color={color}>{status}</Tag>;
    },
  },
  { title: "时间", dataIndex: "time", key: "time" },
  {
    title: "操作",
    key: "action",
    render: () => (
      <Space>
        <Button type="link">详情</Button>
        <Button type="link">仲裁</Button>
      </Space>
    ),
  },
];

function OrderList() {
  return (
    <div>
      <Title level={4}>订单列表</Title>
      <Card style={{ marginBottom: 16 }}>
        <Form layout="inline">
          <Form.Item label="订单号">
            <Input placeholder="请输入" />
          </Form.Item>
          <Form.Item label="状态">
            <Select placeholder="请选择" style={{ width: 120 }}>
              <Select.Option value="">全部</Select.Option>
              <Select.Option value="pending">待付款</Select.Option>
              <Select.Option value="shipped">已发货</Select.Option>
              <Select.Option value="completed">已完成</Select.Option>
            </Select>
          </Form.Item>
          <Form.Item>
            <Button type="primary">查询</Button>
          </Form.Item>
        </Form>
      </Card>
      <Table columns={columns} dataSource={orderList} rowKey="id" />
    </div>
  );
}

export default OrderList;
