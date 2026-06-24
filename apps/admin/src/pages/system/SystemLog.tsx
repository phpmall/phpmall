import { Card, Table, Typography } from "antd";
import { systemLogs } from "../../data/mock";

const { Title } = Typography;

const columns = [
  { title: "ID", dataIndex: "id", key: "id" },
  { title: "用户", dataIndex: "user", key: "user" },
  { title: "操作", dataIndex: "action", key: "action" },
  { title: "IP", dataIndex: "ip", key: "ip" },
  { title: "时间", dataIndex: "time", key: "time" },
];

function SystemLog() {
  return (
    <div>
      <Title level={4}>操作日志</Title>
      <Card>
        <Table columns={columns} dataSource={systemLogs} rowKey="id" />
      </Card>
    </div>
  );
}

export default SystemLog;
