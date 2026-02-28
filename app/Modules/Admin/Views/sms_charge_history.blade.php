@include('admin::pageheader')
<div class="list-div">
    <table cellpadding='3' cellspacing='1'>
        <tr>
            <th>{{ $lang['order_id'] }}</th>
            <th>{{ $lang['money'] }}</th>
            <th>{{ $lang['log_date'] }}</th>
        </tr>
        @foreach($sms_charge_history['charge'] as $sms_charge_history_item)
            <tr>
                <td>{{ $sms_charge_history_item['order_id'] }}</td>
                <td>{{ $sms_charge_history_item['money'] }}</td>
                <td>{{ $sms_charge_history_item['log_date'] }}</td>
            </tr>
        @endforeach
    </table>
</div>

<form method="POST" action="sms.php" name="listForm">
    <table id="page-table">
        <tr>
            <td>&nbsp;</td>
            <td align="right" nowrap="true">
                @include('sms_pages')
            </td>
        </tr>
    </table>
    <input type="hidden" value="get_charge_history" name="act">
    <input type="hidden" value="{{ $start_date }}" name="start_date">
    <input type="hidden" value="{{ $end_date }}" name="end_date">
</form>
@include('admin::pagefooter')
