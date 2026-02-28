<script type="text/javascript" src="{{ asset('js/utils.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/transport.js') }}"></script>
<table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
    <tr>
        <td bgcolor="#ffffff">
            {{ $lang['country_province'] }}:
            <select name="country" id="selCountries_{{ $sn }}"
                    onchange="region.changed(this, 1, 'selProvinces_{{ $sn }}')" style="border:1px solid #ccc;">
                <option value="0">{{ $lang['please_select'] }}</option>
                @foreach($country_list as $country)
                    <option value="{{ $country['region_id'] }}"
                            @if($choose['country'] == $country['region_id'])selected@endif>{{ $country['region_name'] }}</option>
                @endforeach
            </select>
            <select name="province" id="selProvinces_{{ $sn }}"
                    onchange="region.changed(this, 2, 'selCities_{{ $sn }}')" style="border:1px solid #ccc;">
                <option value="0">{{ $lang['please_select'] }}</option>
                @foreach($province_list[$sn] as $province)
                    <option value="{{ $province['region_id'] }}"
                            @if($choose['province'] == $province['region_id'])selected@endif>{{ $province['region_name'] }}</option>
                @endforeach
            </select>
            <select name="city" id="selCities_{{ $sn }}" onchange="region.changed(this, 3, 'selDistricts_{{ $sn }}')"
                    style="border:1px solid #ccc;">
                <option value="0">{{ $lang['please_select'] }}</option>
                @foreach($city_list[$sn] as $city)
                    <option value="{{ $city['region_id'] }}"
                            @if($choose['city'] == $city['region_id'])selected@endif>{{ $city['region_name'] }}</option>
                @endforeach
            </select>
            <select name="district" id="selDistricts_{{ $sn }}" @if(!$district_list['']$sn)style="display:none"
                    @endif style="border:1px solid #ccc;">
                <option value="0">{{ $lang['please_select'] }}</option>
                @foreach($district_list[$sn] as $district)
                    <option value="{{ $district['region_id'] }}"
                            @if($choose['district'] == $district['region_id'])selected@endif>{{ $district['region_name'] }}</option>
                @endforeach
            </select> <input type="submit" name="Submit" class="bnt_blue_2" value="{{ $lang['search_ship'] }}"/>
            <input type="hidden" name="act" value="viewship"/>
        </td>
    </tr>
</table>

<table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
    <tr>
        <th width="20%" bgcolor="#ffffff">{{ $lang['name'] }}</th>
        <th bgcolor="#ffffff">{{ $lang['describe'] }}</th>
        <th width="40%" bgcolor="#ffffff">{{ $lang['fee'] }}</th>
        <th width="15%" bgcolor="#ffffff">{{ $lang['insure_fee'] }}</th>
    </tr>
    <!-- @foreach($shipping_list as $shipping)
        循环配送方式 -->
        <tr>
            <td valign="top" bgcolor="#ffffff"><strong>{{ $shipping['shipping_name'] }}</strong></td>
            <td valign="top" bgcolor="#ffffff">{{ $shipping['shipping_desc'] }}</td>
            <td valign="top" bgcolor="#ffffff">{{ $shipping['fee'] }}</td>
            <td align="center" valign="top" bgcolor="#ffffff">
                @if($shipping['insure'] != 0)
                    {{ $shipping['insure_formated'] }}
                @else
                    {{ $lang['not_support_insure'] }}
                @endif    </td>
        </tr>
        <!--

    @endforeach 循环配送方式 -->
</table>
