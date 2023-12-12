# 实体分析

### 用户资料 user

```
id 主键
openid 用户的唯一标识
unionid 只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。
mobile 手机号码
nickname 用户昵称
sex 用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
province 用户个人资料填写的省份
city 普通用户个人资料填写的城市
country 国家，如中国为CN
headimgurl 用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。
access_token 网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同
expires_in access_token接口调用凭证超时时间，单位（秒）
refresh_token 用户刷新access_token
followed 是否关注公众号
created_at 创建时间
updated_at 更新时间
```

### 商家 shop

```
id
user_id 用户ID
name 店铺名称
country 国家
province 省
city 市
district 区
address 地址
balance 可提现余额
balance_type 结算类型
status 状态
created_at 创建时间
updated_at 更新时间
```

### 优惠券 coupon

```
id
shop_id 商家ID
amount 优惠券金额
condition 使用条件 JSON
status 状态
created_at 创建时间
updated_at 更新时间
```

### 支付订单 order

```
id 
user_id 用户ID
shop_id 商店ID
amount 支付金额
coupon_id 优惠券ID
created_at 创建时间
updated_at 支付回调时间
```

### 商家提现 withdraw

```
id 
user_id 用户ID
shop_id 商店ID
amount 提现金额
created_at 创建时间
updated_at 提现时间
```

### 管理员 admin（可选）

可采用微信扫码登录，与user同表

### 角色表 admin_role

```
id
name
status
created_at 创建时间
updated_at 更新时间
```

### 资源表 admin_rule

```
id
parent_id
name 访问资源（routing）
note 描述
status 状态
menu 是否是菜单项目
created_at 创建时间
updated_at 更新时间
```

### 角色权限 admin_privilege

```
id
admin_role_id
admin_rule_id
```

