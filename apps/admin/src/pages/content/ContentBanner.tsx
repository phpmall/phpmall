import { Button, Card, Image, Space, Table, Tag, Typography } from "antd";
import { bannerList } from "../../data/mock";

const { Title } = Typography;

const columns = [
  { title: "ID", dataIndex: "id", key: "id" },
  { title: "标题", dataIndex: "title", key: "title" },
  { title: "位置", dataIndex: "position", key: "position" },
  { title: "排序", dataIndex: "sort", key: "sort" },
  {
    title: "图片",
    dataIndex: "image",
    key: "image",
    render: () => <Image width={80} src="https://via.placeholder.com/120x60" />,
  },
  {
    title: "状态",
    dataIndex: "status",
    key: "status",
    render: (status: string) => <Tag color={status === "启用" ? "green" : "default"}>{status}</Tag>,
  },
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

function ContentBanner() {
  return (
    <div>
      <Title level={4}>Banner 管理</Title>
      <Card>
        <Button type="primary" style={{ marginBottom: 16 }}>新增 Banner</Button>
        <Table columns={columns} dataSource={bannerList} rowKey="id" />
      </Card>
    </div>
  );
}

export default ContentBanner;
