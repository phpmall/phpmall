import { Button, Card, Form, Input, Select, Space, Table, Tag, Typography } from "antd";
import { productList } from "../../data/mock";

const { Title } = Typography;

const columns = [
  { title: "ID", dataIndex: "id", key: "id" },
  { title: "商品名称", dataIndex: "name", key: "name" },
  { title: "分类", dataIndex: "category", key: "category" },
  { title: "商家", dataIndex: "merchant", key: "merchant" },
  {
    title: "价格",
    dataIndex: "price",
    key: "price",
    render: (v: number) => `¥${(v / 100).toFixed(2)}`,
  },
  { title: "库存", dataIndex: "stock", key: "stock" },
  {
    title: "状态",
    dataIndex: "status",
    key: "status",
    render: (status: string) => {
      const color = status === "上架中" ? "green" : status === "已下架" ? "red" : "orange";
      return <Tag color={color}>{status}</Tag>;
    },
  },
  {
    title: "操作",
    key: "action",
    render: () => (
      <Space>
        <Button type="link">查看</Button>
        <Button type="link">下架</Button>
      </Space>
    ),
  },
];

function ProductList() {
  return (
    <div>
      <Title level={4}>商品列表</Title>
      <Card style={{ marginBottom: 16 }}>
        <Form layout="inline">
          <Form.Item label="商品名称">
            <Input placeholder="请输入" />
          </Form.Item>
          <Form.Item label="状态">
            <Select placeholder="请选择" style={{ width: 120 }}>
              <Select.Option value="">全部</Select.Option>
              <Select.Option value="on">上架中</Select.Option>
              <Select.Option value="off">已下架</Select.Option>
            </Select>
          </Form.Item>
          <Form.Item>
            <Button type="primary">查询</Button>
          </Form.Item>
        </Form>
      </Card>
      <Table columns={columns} dataSource={productList} rowKey="id" />
    </div>
  );
}

export default ProductList;
