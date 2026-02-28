@include('admin::pageheader')
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/listtable.js') }}"></script>
<ul style="padding:0; margin: 0; list-style-type:none; color: #CC0000;">
    <li style="border: 1px solid #CC0000; background: #FFFFCC; padding: 10px; margin-bottom: 5px;">{{ $order_info }}
    </li>
</ul>
<!-- 订单列表 -->
<form method="post" action="order.php?act=operate" name="listForm" onsubmit="return check()">
    <div class="list-div" id="listDiv">

        <table cellpadding="3" cellspacing="1">
            <tr>
                <th>{{ $lang['order_sn'] }}</th>
                <th>{{ $lang['all_status'] }}</th>
                <th>{{ $lang['op_you_can'] }}</th>
                <th>{{ $lang['handler'] }}</th>
            <tr>
            @foreach($order_list as $key => $order)
                <tr>
                    <td valign="top" nowrap="nowrap"><input type="checkbox" name="checkboxes"
                                                            value="{{ $order['order_sn'] }}"/>{{ $order['order_sn'] }}
                    </td>
                    <td align="center" valign="top" nowrap="nowrap">
                        {$lang.os[$order.order_status]},{$lang.ps[$order.pay_status]},{$lang.ss[$order.shipping_status]}
                    </td>
                    <td align="center" valign="top" nowrap="nowrap">{{ $order['operable'] }}</td>
                    <td align="center" valign="top" nowrap="nowrap">
                        <a href="order.php?act=info&order_id={{ $order['order_id'] }}">{{ $lang['detail'] }}</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</form>
<script type="text/javascript">
    function check() {
        var snArray = new Array();
        var eles = document.forms['listForm'].elements;
        for (var i = 0; i < eles.length; i++) {
            if (eles[i].tagName === 'INPUT' && eles[i].type === 'checkbox' && eles[i].checked && eles[i].value != 'on') {
                snArray.push(eles[i].value);
            }
        }
        if (snArray.length === 0) {
            return false;
        } else {
            eles['order_id'].value = snArray.toString();
            return true;
        }
    }
</script>
@include('admin::pagefooter')
