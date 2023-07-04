亲爱的{{ $order.consignee }}\r\n你好！您的订单{{ $order.order_sn }}中{{ $goods.goods_name }} 商品的详细信息如下:\r\n
@foreach($virtual as $card)
    \r\n
    @if($card.card_sn)
        卡号：{{ $card.card_sn }}
    @endif

    @if($card.card_password)
        卡片密码：{{ $card.card_password }}
    @endif

    @if($card.end_date)
        截至日期：{{ $card.end_date }}
    @endif
    \r\n
@endforeach
\r\n再次感谢您对我们的支持。欢迎您的再次光临。\r\n\r\n{$shop_name} \r\n{$send_date}
