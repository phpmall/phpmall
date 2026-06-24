import { Card, Col, Row, Statistic, Typography } from "antd";
import { financeOverview } from "../../data/mock";

const { Title } = Typography;

function FinanceOverview() {
  return (
    <div>
      <Title level={4}>资金概览</Title>
      <Row gutter={[16, 16]}>
        {financeOverview.map((item) => (
          <Col xs={24} sm={12} lg={6} key={item.title}>
            <Card>
              <Statistic
                title={item.title}
                value={item.value}
                prefix={item.prefix}
                valueStyle={{ color: "#1677ff" }}
              />
            </Card>
          </Col>
        ))}
      </Row>
      <Card title="收支趋势" style={{ marginTop: 24 }}>趋势图表占位</Card>
    </div>
  );
}

export default FinanceOverview;
