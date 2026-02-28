@include('admin::pageheader')
<div class="list-div">
    <table cellpadding='3' cellspacing='1'>
        <tr>
            <th>{{ $lang['sent_phones'] }}</th>
            <th>{{ $lang['content'] }}</th>
            <th>{{ $lang['charge_num'] }}</th>
            <th>{{ $lang['sent_date'] }}</th>
            <th>{{ $lang['send_status'] }}</th>
        </tr>
        @foreach($sms_send_history['send'] as $sms_send_history_item)
            <tr>
                <td>{{ $sms_send_history_item['phone'] }}</td>
                <td>{{ $sms_send_history_item['content'] }}</td>
                <td>{{ $sms_send_history_item['charge_num'] }}</td>
                <td>{{ $sms_send_history_item['send_date'] }}</td>
                <td>'.($sms_send_history_item['send_status'] === 1 ? '{{ $lang['status']['1'] }}' :
                    '{{ $lang['status']['0'] }}').'
                </td>
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
    <input type="hidden" value="get_send_history" name="act">
    <input type="hidden" value="{{ $start_date }}" name="start_date">
    <input type="hidden" value="{{ $end_date }}" name="end_date">
</form>
@include('admin::pagefooter')