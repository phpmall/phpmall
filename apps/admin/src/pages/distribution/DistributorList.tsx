import { Button, Card, Space, Table, Tag, Typography } from "antd";
import { distributorList } from "../../data/mock";

const { Title } = Typography;

const columns = [
  { title: "ID", dataIndex: "id", key: "id" },
  { title: "分销员", dataIndex: "name", key: "name" },
  { title: "手机号", dataIndex: "phone", key: "phone" },
  { title: "累计佣金", dataIndex: "totalCommission", key: "totalCommission", render: (v: number) => `¥${(v / 100).toFixed(2)}` },
  { title: "已结算", dataIndex: "settled", key: "settled", render: (v: number) => `¥${(v / 100).toFixed(2)}` },
  {
    title: "状态",
    dataIndex: "status",
    key: "status",
    render: (status: string) => <Tag color={status === "正常" ? "green" : "red"}>{status}</Tag>,
  },
  {
    title: "操作",
    key: "action",
    render: () => (
      <Space>
        <Button type="link">详情</Button>
        <Button type="link" danger>冻结</Button>
      </Space>
    ),
  },
];

function DistributorList() {
  return (
    <div>
      <Title level={4}>分销员</Title>
      <Card>
        <Table columns={columns} dataSource={distributorList} rowKey="id" />
      </Card>
    </div>
  );
}

export default DistributorList;
