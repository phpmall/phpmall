@include('admin::pageheader')

<div class="main-div">
    <form action="user_rank.php" method="post" name="theForm" onsubmit="return validate()">
        <table width="100%">
            <tr>
                <td class="label">{{ $lang['rank_name'] }}:</td>
                <td><input type="text" name="rank_name" value="{{ $rank['rank_name'] }}"
                           maxlength="20"/>{{ $lang['require_field'] }}</td>
            </tr>
            <tr>
                <td class="label">{{ $lang['integral_min'] }}:</td>
                <td><input type="text" name="min_points" value="{{ $rank['min_points'] }}" size="10" maxlength="20"/>
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['integral_max'] }}:</td>
                <td><input type="text" name="max_points" value="{{ $rank['max_points'] }}" size="10" maxlength="20"/>
                </td>
            </tr>
            <tr>
                <td class="label"></td>
                <td>
                    <input type="checkbox" name="show_price" value="1" @if($rank['show_price'] === 1) checked="true"
                        @endif /> {{ $lang['show_price'] }}
                </td>
            </tr>
            <tr>
                <td class="label"></td>
                <td>
                    <input type="checkbox" name="special_rank" value="1" @if($rank['special_rank'] === 1) checked="true"
                           @endif onClick="javascript:doSpecial()"/> {{ $lang['special_rank'] }} <a
                        href="javascript:showNotice('notice_special');" title="{{ $lang['form_notice'] }}"><img
                            src="{{ asset('static/admin/images/notice.gif') }}" width="16" height="16" border="0"
                            alt="{{ $lang['form_notice'] }}"></a>
                    <br/><span class="notice-span"
                               {{ $help_open ? 'style="display:block" ' : ' style="display:none" ' }} id="notice_special">{{ $lang['notice_special'] }}</span>
                </td>
            </tr>
            <tr>
                <td class="label"><a href="javascript:showNotice('notice_discount');"
                                     title="{{ $lang['form_notice'] }}"><img
                            src="{{ asset('static/admin/images/notice.gif') }}"
                            width="16" height="16" border="0"
                            alt="{{ $lang['form_notice'] }}"></a>{{ $lang['discount'] }}:
                </td>
                <td><input type="text" name="discount" value="{{ $rank['discount'] }}" size="10"
                           maxlength="20"/>{{ $lang['require_field'] }}
                    <br/><span class="notice-span"
                               {{ $help_open ? 'style="display:block" ' : ' style="display:none" ' }} id="notice_discount">{{ $lang['notice_discount'] }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="hidden" name="act" value="{{ $form_action }}"/>
                    <input type="hidden" name="id" value="{{ $rank['rank_id'] }}"/>
                    <input type="submit" value="{{ $lang['button_submit'] }}" class="button"/>
                    <input type="reset" value="{{ $lang['button_reset'] }}" class="button"/>
                </td>
            </tr>
        </table>
    </form>
</div>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/validator.js') }}"></script>

<script type="text/javascript">
    document.forms['theForm'].elements['rank_name'].focus();
    /**
     * 检查表单输入的数据
     */
    function validate() {
        if (!document.forms['theForm'].elements['special_rank'].checked) {
            if (Utils.trim(document.forms['theForm'].elements['min_points'].value) === '' ||
                !Utils.isInt(document.forms['theForm'].elements['min_points'].value)) {
                alert(integral_min_invalid);
                return false;
            }

            if (Utils.trim(document.forms['theForm'].elements['max_points'].value) === '' ||
                !Utils.isInt(document.forms['theForm'].elements['max_points'].value)) {
                alert(integral_max_invalid);
                return false;
            }

            if (!document.forms['theForm'].elements['special_rank'].checked &&
                (parseInt(document.forms['theForm'].elements['max_points'].value) <=
                    parseInt(document.forms['theForm'].elements['min_points'].value))) {
                alert(integral_max_small);
                return false;
            }
            if (parseInt(document.forms['theForm'].elements['discount'].value) < 1 ||
                parseInt(document.forms['theForm'].elements['discount'].value) > 100) {
                alert(discount_invalid);
                return false;
            }
        }

        validator = new Validator("theForm");
        validator.required('rank_name', rank_name_empty);
        validator.isInt('discount', discount_invalid, true);
        return validator.passed();
    }

    function doSpecial() {
        if (document.forms['theForm'].elements['special_rank'].checked) {
            document.forms['theForm'].elements['max_points'].disabled = "true";
            document.forms['theForm'].elements['min_points'].disabled = "true";
        } else {
            document.forms['theForm'].elements['max_points'].disabled = "";
            document.forms['theForm'].elements['min_points'].disabled = "";
        }
    }

</script>

@include('admin::pagefooter')
