import { Button, Card, Space, Table, Typography } from "antd";

const { Title } = Typography;

const data = [
  { id: 1, name: "手机数码", level: 1, sort: 1, children: [
    { id: 11, name: "手机", level: 2, sort: 1 },
    { id: 12, name: "耳机", level: 2, sort: 2 },
  ]},
  { id: 2, name: "运动鞋服", level: 1, sort: 2, children: [
    { id: 21, name: "跑步鞋", level: 2, sort: 1 },
  ]},
];

const columns = [
  { title: "分类名称", dataIndex: "name", key: "name" },
  { title: "层级", dataIndex: "level", key: "level" },
  { title: "排序", dataIndex: "sort", key: "sort" },
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

function ProductCategory() {
  return (
    <div>
      <Title level={4}>分类管理</Title>
      <Card>
        <Button type="primary" style={{ marginBottom: 16 }}>新增分类</Button>
        <Table columns={columns} dataSource={data} rowKey="id" />
      </Card>
    </div>
  );
}

export default ProductCategory;
