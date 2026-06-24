import { Button, Card, Space, Table, Typography } from "antd";

const { Title } = Typography;

const data = [
  { id: 1, name: "满200减30", type: "满减", rule: "满 200 减 30", status: "启用" },
  { id: 2, name: "满2件9折", type: "满折", rule: "满 2 件 9 折", status: "启用" },
];

const columns = [
  { title: "活动名称", dataIndex: "name", key: "name" },
  { title: "类型", dataIndex: "type", key: "type" },
  { title: "规则", dataIndex: "rule", key: "rule" },
  { title: "状态", dataIndex: "status", key: "status" },
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

function MarketingDiscount() {
  return (
    <div>
      <Title level={4}>满减满折</Title>
      <Card>
        <Button type="primary" style={{ marginBottom: 16 }}>新增活动</Button>
        <Table columns={columns} dataSource={data} rowKey="id" />
      </Card>
    </div>
  );
}

export default MarketingDiscount;
