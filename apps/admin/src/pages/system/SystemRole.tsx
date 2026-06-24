import { Button, Card, Space, Table, Typography } from "antd";

const { Title } = Typography;

const data = [
  { id: 1, name: "超级管理员", desc: "全部权限", userCount: 1 },
  { id: 2, name: "运营", desc: "商家/商品/订单/营销", userCount: 3 },
  { id: 3, name: "财务", desc: "财务/对账/提现", userCount: 2 },
  { id: 4, name: "审核员", desc: "商家入驻/商品审核", userCount: 2 },
];

const columns = [
  { title: "角色名", dataIndex: "name", key: "name" },
  { title: "说明", dataIndex: "desc", key: "desc" },
  { title: "用户数", dataIndex: "userCount", key: "userCount" },
  {
    title: "操作",
    key: "action",
    render: () => (
      <Space>
        <Button type="link">权限配置</Button>
        <Button type="link">编辑</Button>
      </Space>
    ),
  },
];

function SystemRole() {
  return (
    <div>
      <Title level={4}>角色权限</Title>
      <Card>
        <Button type="primary" style={{ marginBottom: 16 }}>新增角色</Button>
        <Table columns={columns} dataSource={data} rowKey="id" />
      </Card>
    </div>
  );
}

export default SystemRole;
