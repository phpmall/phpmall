import { Button, Card, Space, Table, Tag, Typography } from "antd";
import { couponList } from "../../data/mock";

const { Title } = Typography;

const columns = [
  { title: "ID", dataIndex: "id", key: "id" },
  { title: "券名称", dataIndex: "name", key: "name" },
  { title: "类型", dataIndex: "type", key: "type" },
  { title: "使用门槛", dataIndex: "threshold", key: "threshold", render: (v: number) => `¥${(v / 100).toFixed(2)}` },
  { title: "优惠", dataIndex: "value", key: "value" },
  {
    title: "状态",
    dataIndex: "status",
    key: "status",
    render: (status: string) => <Tag color={status === "进行中" ? "green" : "default"}>{status}</Tag>,
  },
  { title: "有效期", dataIndex: "time", key: "time" },
  {
    title: "操作",
    key: "action",
    render: () => (
      <Space>
        <Button type="link">编辑</Button>
        <Button type="link" danger>停用</Button>
      </Space>
    ),
  },
];

function MarketingCoupon() {
  return (
    <div>
      <Title level={4}>优惠券</Title>
      <Card>
        <Button type="primary" style={{ marginBottom: 16 }}>新增优惠券</Button>
        <Table columns={columns} dataSource={couponList} rowKey="id" />
      </Card>
    </div>
  );
}

export default MarketingCoupon;
