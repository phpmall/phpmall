import { Button, Card, Space, Table, Tag, Typography } from "antd";
import { auditList } from "../../data/mock";

const { Title } = Typography;

const columns = [
  { title: "ID", dataIndex: "id", key: "id" },
  { title: "商家名称", dataIndex: "name", key: "name" },
  { title: "联系人", dataIndex: "contact", key: "contact" },
  { title: "申请时间", dataIndex: "applyTime", key: "applyTime" },
  {
    title: "状态",
    dataIndex: "status",
    key: "status",
    render: (status: string) => <Tag color={status === "待审核" ? "orange" : "green"}>{status}</Tag>,
  },
  {
    title: "操作",
    key: "action",
    render: (_: unknown, record: { status: string }) =>
      record.status === "待审核" ? (
        <Space>
          <Button type="primary">通过</Button>
          <Button danger>拒绝</Button>
        </Space>
      ) : (
        <Button type="link">详情</Button>
      ),
  },
];

function MerchantAudit() {
  return (
    <div>
      <Title level={4}>入驻审核</Title>
      <Card>
        <Table columns={columns} dataSource={auditList} rowKey="id" />
      </Card>
    </div>
  );
}

export default MerchantAudit;
