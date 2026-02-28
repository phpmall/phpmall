@include('admin::pageheader')
<script src="{{ asset('static/admin/js/validator.js') }}"></script>
<script src="{{ asset('js/transport.js') }}"></script>
<script src="{{ asset('js/region.js') }}"></script>
<div class="main-div">
    <form method="post" action="agency.php" name="theForm" enctype="multipart/form-data" onsubmit="return validate()">
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">{{ $lang['label_agency_name'] }}</td>
                <td><input type="text" name="agency_name" maxlength="60"
                           value="{{ $agency['agency_name'] }}"/>{{ $lang['require_field'] }}</td>
            </tr>
            <tr>
                <td class="label">{{ $lang['label_agency_desc'] }}</td>
                <td><textarea name="agency_desc" cols="60" rows="4">{{ $agency['agency_desc'] }}</textarea></td>
            </tr>
            <tr>
                <td class="label">
                    <a href="javascript:showNotice('noticeAdmins');" title="{{ $lang['form_notice'] }}"><img
                            src="{{ asset('static/admin/images/notice.gif') }}" width="16" height="16" border="0"
                            alt="{{ $lang['form_notice'] }}"></a>{{ $lang['label_admins'] }}</td>
                <td>@foreach($agency['admin_list'] as $admin)
                        <input type="checkbox" name="admins[]" value="{{ $admin['user_id'] }}"
                               @if($admin['type'] === "this")checked="checked"@endif />
                        {{ $admin['user_name'] }}@if($admin['type'] === "other")
                            (*)
                        @endif&nbsp;&nbsp;
                    @endforeach<br/>
                    <span class="notice-span"
                          {{ $help_open ? 'style="display:block" ' : ' style="display:none" ' }} id="noticeAdmins">{{ $lang['notice_admins'] }}</span>
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['label_regions'] }}</td>
                <td id="regionCell">@foreach($agency['region_list'] as $region)
                        <input type="checkbox" name="regions[]" value="{{ $region['region_id'] }}" checked="true"/>
                        {{ $region['region_name'] }}&nbsp;&nbsp;
                    @endforeach </td>
            </tr>
        </table>
        <hr>
        <table cellspacing="1" cellpadding="3" width="100%">
            <caption><strong>{{ $lang['add_region'] }}</strong></caption>
            <tr>
                <td width="10%">&nbsp;</td>
                <td>{{ $lang['label_country'] }}</td>
                <td>{{ $lang['label_province'] }}</td>
                <td>{{ $lang['label_city'] }}</td>
                <td>{{ $lang['label_district'] }}</td>
                <td width="10">&nbsp;</td>
                <td width="10%">&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><select name="country" id="selCountries" onChange="region.changed(this, 1, 'selProvinces')"
                            size="10">
                        @foreach($countries as $country)
                            <option value="{{ $country['region_id'] }}"
                                    @if($loop->fe_country.first)selected@endif>{{ $country['region_name'] }}</option>
                        @endforeach
                    </select></td>
                <td><select name="province" id="selProvinces" onChange="region.changed(this, 2, 'selCities')" size="10">
                        <option value="">{{ $lang['select_please'] }}</option>
                    </select></td>
                <td><select name="city" id="selCities" onChange="region.changed(this, 3, 'selDistricts')" size="10">
                        <option value="">{{ $lang['select_please'] }}</option>
                    </select></td>
                <td><select name="district" id="selDistricts" size="10">
                        <option value="">{{ $lang['select_please'] }}</option>
                    </select></td>
                <td><input type="button" value="+" class="button" onclick="addRegion()"/></td>
                <td>&nbsp;</td>
            </tr>
        </table>

        <table align="center">
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" class="button" value="{{ $lang['button_submit'] }}"/>
                    <input type="reset" class="button" value="{{ $lang['button_reset'] }}"/>
                    <input type="hidden" name="act" value="{{ $form_action }}"/>
                    <input type="hidden" name="id" value="{{ $agency['agency_id'] }}"/>
                </td>
            </tr>
        </table>
    </form>
</div>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/validator.js') }}"></script>

<script type="text/javascript">
    region.isAdmin = true;
    document.forms['theForm'].elements['agency_name'].focus();
    onload = function () {
        var selCountry = document.forms['theForm'].elements['country'];
        if (selCountry.selectedIndex >= 0) {
            region.loadProvinces(selCountry.options[selCountry.selectedIndex].value);
        }
    }

    /**
     * 检查表单输入的数据
     */
    function validate() {
        validator = new Validator("theForm");
        validator.required("agency_name", no_agencyname);
        return validator.passed();
    }

    /**
     * 添加一个区域
     */
    function addRegion() {
        var selCountry = document.forms['theForm'].elements['country'];
        var selProvince = document.forms['theForm'].elements['province'];
        var selCity = document.forms['theForm'].elements['city'];
        var selDistrict = document.forms['theForm'].elements['district'];
        var regionCell = document.getElementById("regionCell");

        if (selDistrict.selectedIndex > 0) {
            regionId = selDistrict.options[selDistrict.selectedIndex].value;
            regionName = selDistrict.options[selDistrict.selectedIndex].text;
        } else {
            if (selCity.selectedIndex > 0) {
                regionId = selCity.options[selCity.selectedIndex].value;
                regionName = selCity.options[selCity.selectedIndex].text;
            } else {
                if (selProvince.selectedIndex > 0) {
                    regionId = selProvince.options[selProvince.selectedIndex].value;
                    regionName = selProvince.options[selProvince.selectedIndex].text;
                } else {
                    if (selCountry.selectedIndex >= 0) {
                        regionId = selCountry.options[selCountry.selectedIndex].value;
                        regionName = selCountry.options[selCountry.selectedIndex].text;
                    } else {
                        return;
                    }
                }
            }
        }

        // 检查该地区是否已经存在
        exists = false;
        for (i = 0; i < document.forms['theForm'].elements.length; i++) {
            if (document.forms['theForm'].elements[i].type === "checkbox" && document.forms['theForm'].elements[i].name.substr(0, 6) === 'region') {
                if (document.forms['theForm'].elements[i].value === regionId) {
                    exists = true;
                    alert(region_exists);
                    break;
                }
            }
        }

        // 创建checkbox
        if (!exists) {
            regionCell.innerHTML += "<input type='checkbox' name='regions[]' value='" + regionId + "' checked='true' /> " + regionName + "&nbsp;&nbsp;";
        }
    }

</script>

@include('admin::pagefooter')
