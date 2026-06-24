import { Button, Card, Space, Table, Typography } from "antd";

const { Title } = Typography;

const data = [
  { id: "B20260624", merchant: "Apple 官方旗舰店", amount: 500000, platformFee: 5000, settleAmount: 495000, status: "已对账" },
  { id: "B20260623", merchant: "小米之家", amount: 230000, platformFee: 2300, settleAmount: 227700, status: "已对账" },
];

const columns = [
  { title: "账单号", dataIndex: "id", key: "id" },
  { title: "商家", dataIndex: "merchant", key: "merchant" },
  { title: "交易金额", dataIndex: "amount", key: "amount", render: (v: number) => `¥${(v / 100).toFixed(2)}` },
  { title: "平台服务费", dataIndex: "platformFee", key: "platformFee", render: (v: number) => `¥${(v / 100).toFixed(2)}` },
  { title: "应结算", dataIndex: "settleAmount", key: "settleAmount", render: (v: number) => `¥${(v / 100).toFixed(2)}` },
  { title: "状态", dataIndex: "status", key: "status" },
  {
    title: "操作",
    key: "action",
    render: () => (
      <Space>
        <Button type="link">详情</Button>
        <Button type="link">导出</Button>
      </Space>
    ),
  },
];

function FinanceReconciliation() {
  return (
    <div>
      <Title level={4}>对账管理</Title>
      <Card>
        <Table columns={columns} dataSource={data} rowKey="id" />
      </Card>
    </div>
  );
}

export default FinanceReconciliation;
