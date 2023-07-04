亲爱的{{ $order.consignee }}。你好！</br></br>\n\n您的订单{{ $order.order_sn }}已于{$send_time}按照您预定的配送方式给您发货了。
</br>\n</br>\n
@if($order.invoice_no)
    发货单号是{{ $order.invoice_no }}。</br>
@endif
\n</br>\n在您收到货物之后请点击下面的链接确认您已经收到货物：</br>\n<a href=\"{$confirm_url}\" target=\"_blank\">{$confirm_url}</a></br></br>\n如果您还没有收到货物可以点击以下链接给我们留言：</br></br>\n<a href=\"{$send_msg_url}\" target=\"_blank\">{$send_msg_url}</a></br>\n<br>\n再次感谢您对我们的支持。欢迎您的再次光临。 <br>\n<br>\n{$shop_name} </br>\n{$send_date}
