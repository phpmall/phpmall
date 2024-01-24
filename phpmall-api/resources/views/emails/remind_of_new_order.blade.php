亲爱的店长，您好：\n   快来看看吧，又有新订单了。\n    订单号:{{ $order.order_sn }} \n 订单金额:{{ $order.order_amount }}，\n 用户购买商品:@foreach($goods_list as $goods_data)
    {{ $goods_data.goods_name }}(货号:{{ $goods_data.goods_sn }})
@endforeach
\n\n 收货人:{{ $order.consignee }}， \n 收货人地址:{{ $order.address }}，\n 收货人电话:{{ $order.tel }} {{ $order.mobile }}, \n 配送方式:{{ $order.shipping_name }}(费用:{{ $order.shipping_fee }}), \n 付款方式:{{ $order.pay_name }}(费用:{{ $order.pay_fee }})。\n\n               系统提醒\n               {$send_date}
