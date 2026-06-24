import { Button, Card, Form, Input, Select, Space, Table, Tag, Typography } from "antd";
import { merchantList } from "../../data/mock";

const { Title } = Typography;

const columns = [
  { title: "ID", dataIndex: "id", key: "id" },
  { title: "商家名称", dataIndex: "name", key: "name" },
  { title: "联系人", dataIndex: "contact", key: "contact" },
  { title: "联系电话", dataIndex: "phone", key: "phone" },
  {
    title: "状态",
    dataIndex: "status",
    key: "status",
    render: (status: string) => {
      const color = status === "正常" ? "green" : status === "冻结" ? "red" : "orange";
      return <Tag color={color}>{status}</Tag>;
    },
  },
  { title: "结算周期", dataIndex: "settleCycle", key: "settleCycle" },
  {
    title: "GMV",
    dataIndex: "gmv",
    key: "gmv",
    render: (v: number) => `¥${(v / 100).toFixed(2)}`,
  },
  {
    title: "操作",
    key: "action",
    render: () => (
      <Space>
        <Button type="link">查看</Button>
        <Button type="link">编辑</Button>
      </Space>
    ),
  },
];

function MerchantList() {
  return (
    <div>
      <Title level={4}>商家列表</Title>
      <Card style={{ marginBottom: 16 }}>
        <Form layout="inline">
          <Form.Item label="商家名称">
            <Input placeholder="请输入" />
          </Form.Item>
          <Form.Item label="状态">
            <Select placeholder="请选择" style={{ width: 120 }}>
              <Select.Option value="">全部</Select.Option>
              <Select.Option value="normal">正常</Select.Option>
              <Select.Option value="frozen">冻结</Select.Option>
            </Select>
          </Form.Item>
          <Form.Item>
            <Button type="primary">查询</Button>
          </Form.Item>
        </Form>
      </Card>
      <Table columns={columns} dataSource={merchantList} rowKey="id" />
    </div>
  );
}

export default MerchantList;
