import { Button, Card, Space, Table, Typography } from "antd";

const { Title } = Typography;

const data = [
  { id: 1, name: "Apple", logo: "-", category: "手机数码", status: "启用" },
  { id: 2, name: "Nike", logo: "-", category: "运动鞋服", status: "启用" },
  { id: 3, name: "Sony", logo: "-", category: "影音娱乐", status: "禁用" },
];

const columns = [
  { title: "品牌名称", dataIndex: "name", key: "name" },
  { title: "LOGO", dataIndex: "logo", key: "logo" },
  { title: "所属分类", dataIndex: "category", key: "category" },
  { title: "状态", dataIndex: "status", key: "status" },
  {
    title: "操作",
    key: "action",
    render: () => (
      <Space>
        <Button type="link">编辑</Button>
        <Button type="link" danger>删除</Button>
      </Space>
    ),
  },
];

function ProductBrand() {
  return (
    <div>
      <Title level={4}>品牌管理</Title>
      <Card>
        <Button type="primary" style={{ marginBottom: 16 }}>新增品牌</Button>
        <Table columns={columns} dataSource={data} rowKey="id" />
      </Card>
    </div>
  );
}

export default ProductBrand;
