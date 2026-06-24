import { Button, Card, Space, Table, Typography } from "antd";

const { Title } = Typography;

const data = [
  { id: 1, title: "系统维护通知", type: "系统", time: "2026-06-24 00:00", status: "已发布" },
  { id: 2, title: "618 活动公告", type: "活动", time: "2026-06-18 10:00", status: "已发布" },
];

const columns = [
  { title: "ID", dataIndex: "id", key: "id" },
  { title: "标题", dataIndex: "title", key: "title" },
  { title: "类型", dataIndex: "type", key: "type" },
  { title: "发布时间", dataIndex: "time", key: "time" },
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

function ContentNotice() {
  return (
    <div>
      <Title level={4}>公告管理</Title>
      <Card>
        <Button type="primary" style={{ marginBottom: 16 }}>新增公告</Button>
        <Table columns={columns} dataSource={data} rowKey="id" />
      </Card>
    </div>
  );
}

export default ContentNotice;
