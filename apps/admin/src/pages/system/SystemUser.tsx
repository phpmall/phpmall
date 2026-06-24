import { Button, Card, Space, Table, Tag, Typography } from "antd";
import { systemUsers } from "../../data/mock";

const { Title } = Typography;

const columns = [
  { title: "ID", dataIndex: "id", key: "id" },
  { title: "用户名", dataIndex: "username", key: "username" },
  { title: "昵称", dataIndex: "nickname", key: "nickname" },
  { title: "角色", dataIndex: "role", key: "role" },
  {
    title: "状态",
    dataIndex: "status",
    key: "status",
    render: (status: string) => <Tag color={status === "启用" ? "green" : "red"}>{status}</Tag>,
  },
  {
    title: "操作",
    key: "action",
    render: () => (
      <Space>
        <Button type="link">编辑</Button>
        <Button type="link" danger>禁用</Button>
      </Space>
    ),
  },
];

function SystemUser() {
  return (
    <div>
      <Title level={4}>管理员</Title>
      <Card>
        <Button type="primary" style={{ marginBottom: 16 }}>新增管理员</Button>
        <Table columns={columns} dataSource={systemUsers} rowKey="id" />
      </Card>
    </div>
  );
}

export default SystemUser;
