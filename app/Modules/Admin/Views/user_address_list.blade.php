@include('admin::pageheader')
<div class="list-div">
    <table width="100%" cellpadding="3" cellspacing="1">
        <tr>
            <th>{{ $lang['consignee'] }}</th>
            <th>{{ $lang['address'] }}</th>
            <th>{{ $lang['link'] }}</th>
            <th>{{ $lang['other'] }}</th>
        </tr>
        @forelse($address as $Key => $val)
            <tr>
                <td>{{ $val['consignee'] }}</td>
                <td>{{ $val['country_name'] }}&nbsp;&nbsp;{{ $val['province_name'] }}&nbsp;&nbsp;{{ $val['city_name'] }}
                    &nbsp;&nbsp;{{ $val['district_name'] }}<br/>
                    {{ $val['address'] }}@if($val['zipcode'])
                        [{{ $val['zipcode'] }}]
                    @endif</td>
                <td>{{ $lang['tel'] }}：{{ $val['tel'] }}<br/>{{ $lang['mobile'] }}：{{ $val['mobile'] }}
                    <br/>email: {{ $val['email'] }}</td>
                <td>{{ $lang['best_time'] }}:{{ $val['best_time'] }}<br/>{{ $lang['sign_building'] }}
                    :{{ $val['sign_building'] }}</td>
            </tr>
        @empty
            <tr>
                <td class="no-records" colspan="4">{{ $lang['no_records'] }}</td>
            </tr>
        @endforelse
    </table>
</div>
@include('admin::pagefooter')
