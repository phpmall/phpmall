import { Button, Card, Space, Table, Tag, Typography } from "antd";
import { articleList } from "../../data/mock";

const { Title } = Typography;

const columns = [
  { title: "ID", dataIndex: "id", key: "id" },
  { title: "标题", dataIndex: "title", key: "title" },
  { title: "分类", dataIndex: "category", key: "category" },
  {
    title: "状态",
    dataIndex: "status",
    key: "status",
    render: (status: string) => <Tag color={status === "发布" ? "green" : "default"}>{status}</Tag>,
  },
  { title: "发布时间", dataIndex: "time", key: "time" },
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

function ContentArticle() {
  return (
    <div>
      <Title level={4}>文章管理</Title>
      <Card>
        <Button type="primary" style={{ marginBottom: 16 }}>新增文章</Button>
        <Table columns={columns} dataSource={articleList} rowKey="id" />
      </Card>
    </div>
  );
}

export default ContentArticle;
