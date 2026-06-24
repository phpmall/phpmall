import { Card, Table, Tag, Typography } from "antd";

const { Title } = Typography;

const data = [
  { id: 1, distributor: "推广达人 A", orderId: "O20260624002", amount: 560, status: "待结算", time: "2026-06-24 09:45" },
  { id: 2, distributor: "推广达人 B", orderId: "O20260623005", amount: 230, status: "已结算", time: "2026-06-23 15:20" },
];

const columns = [
  { title: "ID", dataIndex: "id", key: "id" },
  { title: "分销员", dataIndex: "distributor", key: "distributor" },
  { title: "订单号", dataIndex: "orderId", key: "orderId" },
  { title: "佣金", dataIndex: "amount", key: "amount", render: (v: number) => `¥${(v / 100).toFixed(2)}` },
  { title: "状态", dataIndex: "status", key: "status", render: (status: string) => <Tag color={status === "已结算" ? "green" : "orange"}>{status}</Tag> },
  { title: "时间", dataIndex: "time", key: "time" },
];

function CommissionList() {
  return (
    <div>
      <Title level={4}>佣金管理</Title>
      <Card>
        <Table columns={columns} dataSource={data} rowKey="id" />
      </Card>
    </div>
  );
}

export default CommissionList;
