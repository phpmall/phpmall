<tr>
    <td class="label" valign="top">
        @if($var['desc'])
            <a href="javascript:showNotice('notice{{ $var['code'] }}');" title="{{ $lang['form_notice'] }}"><img
                    src="{{ asset('static/admin/images/notice.gif') }}" width="16" height="16" border="0"
                    alt="{{ $lang['form_notice'] }}"/></a>
        @endif
        {{ $var['name'] }}:
    </td>
    <td>
        @if($var['type'] === "text")
            <input name="value[{{ $var['id'] }}]" type="text" value="{{ $var['value'] }}" size="40"/>

        @elseif($var['type'] === "password")
            <input name="value[{{ $var['id'] }}]" type="password" value="{{ $var['value'] }}" size="40"/>

        @elseif($var['type'] === "textarea")
            <textarea name="value[{{ $var['id'] }}]" cols="40" rows="5">{{ $var['value'] }}</textarea>

        @elseif($var['type'] === "select")
            @foreach($var['store_options'] as $k => $opt)
                <label for="value_{{ $var['id'] }}_{{ $k }}"><input type="radio" name="value[{{ $var['id'] }}]"
                                                                    id="value_{{ $var['id'] }}_{{ $k }}"
                                                                    value="{{ $opt }}"
                                                                    @if($var['value'] === $opt)checked="true" @endif
                                                                    @if($var['code'] === 'rewrite')
                                                                        onclick="return ReWriterConfirm(this);"
                                                                    @endif
                                                                    @if($var['code'] === 'smtp_ssl' && $opt === 1)
                                                                        onclick="return confirm('{{ $lang['smtp_ssl_confirm'] }}');"
                                                                    @endif
                                                                    @if($var['code'] === 'enable_gzip' && $opt === 1)
                                                                        onclick="return confirm('{{ $lang['gzip_confirm'] }}');"
                                                                    @endif
                                                                    @if($var['code'] === 'retain_original_img' && $opt === 0)
                                                                        onclick="return confirm('{{ $lang['retain_original_confirm'] }}');"
                        @endif
                    />{$var.display_options[$k]}</label>
            @endforeach

        @elseif($var['type'] === "options")
            <select name="value[{{ $var['id'] }}]" id="value_{{ $var['id'] }}_{{ $key }}">
                {html_options options=$lang.cfg_range[$var.code] selected=$var.value}
            </select>

        @elseif($var['type'] === "file")
            <input name="{{ $var['code'] }}" type="file" size="40"/>
            @if(($var['code'] === "shop_logo" || $var['code'] === "no_picture" || $var['code'] === "watermark" || $var['code'] === "shop_slagon" || $var['code'] === "wap_logo") && $var['value'])
                <a href="?act=del&code={{ $var['code'] }}"><img src="{{ asset('static/admin/images/no.gif') }}"
                                                                alt="Delete" border="0"/></a> <img
                    src="{{ asset('static/admin/images/yes.gif') }}" border="0"
                    onmouseover="showImg('{{ $var['code'] }}_layer', 'show')"
                    onmouseout="showImg('{{ $var['code'] }}_layer', 'hide')"/>
                <div id="{{ $var['code'] }}_layer"
                     style="position:absolute; width:100px; height:100px; z-index:1; visibility:hidden" border="1">
                    <img src="{{ $var['value'] }}" border="0"/>
                </div>
            @else
                @if($var['value'] != "")
                    <img src="{{ asset('static/admin/images/yes.gif') }}" alt="yes"/>
                @else
                    <img src="{{ asset('static/admin/images/no.gif') }}" alt="no"/>
                @endif
            @endif
        @elseif($var['type'] === "manual")

            @if($var['code'] === "shop_country")
                <select name="value[{{ $var['id'] }}]" id="selCountries"
                        onchange="region.changed(this, 1, 'selProvinces')">
                    <option value=''>{{ $lang['select_please'] }}</option>
                    @foreach($countries as $region)
                        <option value="{{ $region['region_id'] }}"
                                @if($region['region_id'] === $cfg['shop_country'])selected@endif>{{ $region['region_name'] }}</option>
                    @endforeach
                </select>
            @elseif($var['code'] === "shop_province")
                <select name="value[{{ $var['id'] }}]" id="selProvinces"
                        onchange="region.changed(this, 2, 'selCities')">
                    <option value=''>{{ $lang['select_please'] }}</option>
                    @foreach($provinces as $region)
                        <option value="{{ $region['region_id'] }}"
                                @if($region['region_id'] === $cfg['shop_province'])selected@endif>{{ $region['region_name'] }}</option>
                    @endforeach
                </select>
            @elseif($var['code'] === "shop_city")
                <select name="value[{{ $var['id'] }}]" id="selCities">
                    <option value=''>{{ $lang['select_please'] }}</option>
                    @foreach($cities as $region)
                        <option value="{{ $region['region_id'] }}"
                                @if($region['region_id'] === $cfg['shop_city'])selected@endif>{{ $region['region_name'] }}</option>
                    @endforeach
                </select>
            @elseif($var['code'] === "lang")
                <select name="value[{{ $var['id'] }}]">
                    {html_options values=$lang_list output=$lang_list selected=$var.value}
                </select>
            @elseif($var['code'] === "invoice_type")
                <table>
                    <tr>
                        <th scope="col">{{ $lang['invoice_type'] }}</th>
                        <th scope="col">{{ $lang['invoice_rate'] }}</th>
                    </tr>
                    <tr>
                        <td><input name="invoice_type[]" type="text" value="{$cfg.invoice_type.type[0]}"/></td>
                        <td><input name="invoice_rate[]" type="text" value="{$cfg.invoice_type.rate[0]}"/></td>
                    </tr>
                    <tr>
                        <td><input name="invoice_type[]" type="text" value="{$cfg.invoice_type.type[1]}"/></td>
                        <td><input name="invoice_rate[]" type="text" value="{$cfg.invoice_type.rate[1]}"/></td>
                    </tr>
                    <tr>
                        <td><input name="invoice_type[]" type="text" value="{$cfg.invoice_type.type[2]}"/></td>
                        <td><input name="invoice_rate[]" type="text" value="{$cfg.invoice_type.rate[2]}"/></td>
                    </tr>
                </table>
            @endif
        @endif
        @if($var['desc'])
            <br/>
            <span class="notice-span"
                  {{ $help_open ? 'style="display:block" ' : ' style="display:none" ' }} id="notice{{ $var['code'] }}">{$var.desc|nl2br}</span>
        @endif
    </td>
</tr>
