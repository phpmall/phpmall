import { Button, Card, Form, Input, Switch, Typography } from "antd";

const { Title } = Typography;

function SystemConfig() {
  return (
    <div>
      <Title level={4}>参数配置</Title>
      <Card>
        <Form layout="vertical" style={{ maxWidth: 600 }}>
          <Form.Item label="平台名称">
            <Input defaultValue="PHPMall" />
          </Form.Item>
          <Form.Item label="客服电话">
            <Input defaultValue="400-123-4567" />
          </Form.Item>
          <Form.Item label="默认运费（元）">
            <Input defaultValue="10.00" />
          </Form.Item>
          <Form.Item label="是否开启注册">
            <Switch defaultChecked />
          </Form.Item>
          <Form.Item label="是否开启分销">
            <Switch defaultChecked />
          </Form.Item>
          <Form.Item>
            <Button type="primary">保存</Button>
          </Form.Item>
        </Form>
      </Card>
    </div>
  );
}

export default SystemConfig;
