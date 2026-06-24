import { Button, Card, Space, Table, Tag, Typography } from "antd";
import { refundList } from "../../data/mock";

const { Title } = Typography;

const columns = [
  { title: "退款单号", dataIndex: "id", key: "id" },
  { title: "订单号", dataIndex: "orderId", key: "orderId" },
  { title: "用户", dataIndex: "user", key: "user" },
  {
    title: "金额",
    dataIndex: "amount",
    key: "amount",
    render: (v: number) => `¥${(v / 100).toFixed(2)}`,
  },
  { title: "原因", dataIndex: "reason", key: "reason" },
  {
    title: "状态",
    dataIndex: "status",
    key: "status",
    render: (status: string) => <Tag color={status === "待处理" ? "orange" : "green"}>{status}</Tag>,
  },
  { title: "申请时间", dataIndex: "time", key: "time" },
  {
    title: "操作",
    key: "action",
    render: () => (
      <Space>
        <Button type="primary">同意</Button>
        <Button danger>拒绝</Button>
      </Space>
    ),
  },
];

function OrderRefund() {
  return (
    <div>
      <Title level={4}>售后退款</Title>
      <Card>
        <Table columns={columns} dataSource={refundList} rowKey="id" />
      </Card>
    </div>
  );
}

export default OrderRefund;
