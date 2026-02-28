@if($invoice_list)
    <style type="text/css">
        .boxCenterList form {
            display: inline;
        }

        .boxCenterList form a {
            color: #404040;
            text-decoration: underline;
        }
    </style>
    <div class="box">
        <div class="box_1">
            <h3><span>{{ $lang['shipping_query'] }}</span></h3>
            <div class="boxCenterList">
                <!-- 发货单查询@foreach($invoice_list as $invoice) -->
                {{ $lang['order_number'] }} {{ $invoice['order_sn'] }}<br/>
                {{ $lang['consignment'] }} {{ $invoice['invoice_no'] }}
                <div class="blank"></div>
                <!-- 结束发货单查询@endforeach-->
            </div>
        </div>
    </div>
    <div class="blank5"></div>
@endif
