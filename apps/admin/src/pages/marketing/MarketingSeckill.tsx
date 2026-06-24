import { Button, Card, Space, Table, Typography } from "antd";

const { Title } = Typography;

const data = [
  { id: 1, name: "618 手机秒杀", startTime: "2026-06-18 10:00", endTime: "2026-06-18 12:00", status: "已结束" },
  { id: 2, name: "周末数码专场", startTime: "2026-06-28 10:00", endTime: "2026-06-28 22:00", status: "未开始" },
];

const columns = [
  { title: "活动名称", dataIndex: "name", key: "name" },
  { title: "开始时间", dataIndex: "startTime", key: "startTime" },
  { title: "结束时间", dataIndex: "endTime", key: "endTime" },
  { title: "状态", dataIndex: "status", key: "status" },
  {
    title: "操作",
    key: "action",
    render: () => (
      <Space>
        <Button type="link">编辑</Button>
        <Button type="link">商品</Button>
      </Space>
    ),
  },
];

function MarketingSeckill() {
  return (
    <div>
      <Title level={4}>秒杀活动</Title>
      <Card>
        <Button type="primary" style={{ marginBottom: 16 }}>新增秒杀</Button>
        <Table columns={columns} dataSource={data} rowKey="id" />
      </Card>
    </div>
  );
}

export default MarketingSeckill;
