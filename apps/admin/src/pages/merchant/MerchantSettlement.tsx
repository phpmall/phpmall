import { Button, Card, Space, Table, Typography } from "antd";
import { merchantList } from "../../data/mock";

const { Title } = Typography;

const columns = [
  { title: "商家 ID", dataIndex: "id", key: "id" },
  { title: "商家名称", dataIndex: "name", key: "name" },
  { title: "结算周期", dataIndex: "settleCycle", key: "settleCycle" },
  {
    title: "待结算金额",
    dataIndex: "gmv",
    key: "gmv",
    render: (v: number) => `¥${(v / 100).toFixed(2)}`,
  },
  {
    title: "操作",
    key: "action",
    render: () => (
      <Space>
        <Button type="primary">结算</Button>
        <Button type="link">明细</Button>
      </Space>
    ),
  },
];

function MerchantSettlement() {
  return (
    <div>
      <Title level={4}>结算管理</Title>
      <Card>
        <Table columns={columns} dataSource={merchantList} rowKey="id" />
      </Card>
    </div>
  );
}

export default MerchantSettlement;
