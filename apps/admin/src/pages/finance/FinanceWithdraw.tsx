import { Button, Card, Space, Table, Tag, Typography } from "antd";
import { withdrawList } from "../../data/mock";

const { Title } = Typography;

const columns = [
  { title: "提现单号", dataIndex: "id", key: "id" },
  { title: "商家", dataIndex: "merchant", key: "merchant" },
  { title: "金额", dataIndex: "amount", key: "amount", render: (v: number) => `¥${(v / 100).toFixed(2)}` },
  {
    title: "状态",
    dataIndex: "status",
    key: "status",
    render: (status: string) => <Tag color={status === "待审核" ? "orange" : "green"}>{status}</Tag>,
  },
  { title: "申请时间", dataIndex: "time", key: "time" },
  {
    title: "操作",
    key: "action",
    render: (_: unknown, record: { status: string }) =>
      record.status === "待审核" ? (
        <Space>
          <Button type="primary">通过</Button>
          <Button danger>拒绝</Button>
        </Space>
      ) : (
        <Button type="link">详情</Button>
      ),
  },
];

function FinanceWithdraw() {
  return (
    <div>
      <Title level={4}>提现审核</Title>
      <Card>
        <Table columns={columns} dataSource={withdrawList} rowKey="id" />
      </Card>
    </div>
  );
}

export default FinanceWithdraw;
