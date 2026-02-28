@include('admin::pageheader')
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/validator.js') }}"></script>

<div class="main-div">
    <form action="" method="post" name="theForm" onsubmit="return validate();">
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">{{ $lang['goods_type_name'] }}:</td>
                <td><input type="text" name="cat_name" value="{{ $goods_type['cat_name'] }}" size="40"/>
                    {{ $lang['require_field'] }}</td>
            </tr>
            <tr style="display:none">
                <td class="label">{{ $lang['goods_type_status'] }}:</td>
                <td>{html_radios name="enabled" options=$lang.arr_goods_status checked=$goods_type.enabled}</td>
            </tr>
            <tr>
                <td class="label"><a href="javascript:showNotice('noticeAttrGroups');"
                                     title="{{ $lang['form_notice'] }}"><img
                            src="{{ asset('static/admin/images/notice.gif') }}" width="16" height="16" border="0"
                            alt="{{ $lang['form_notice'] }}"></a> {{ $lang['attr_groups'] }}:
                </td>
                <td>
                    <textarea name="attr_group" rows="5" cols="40">{{ $goods_type['attr_group'] }}</textarea><br/>
                    <span class="notice-span"
                          {{ $help_open ? 'style="display:block" ' : ' style="display:none" ' }} id="noticeAttrGroups">{{ $lang['notice_attr_groups'] }}</span>
                </td>
            </tr>
            <tr align="center">
                <td colspan="2">
                    <input type="hidden" name="cat_id" value="{{ $goods_type['cat_id'] }}"/>
                    <input type="submit" value="{{ $lang['button_submit'] }}" class="button"/>
                    <input type="reset" value="{{ $lang['button_reset'] }}" class="button"/>
                    <input type="hidden" name="act" value="{{ $form_act }}"/>
                </td>
            </tr>
        </table>
    </form>
</div>

<script type="text/javascript">
    onload = function () {
        document.forms['theForm'].elements['cat_name'].focus();
    }

    function validate() {
        var validator = new Validator('theForm');
        validator.required('cat_name', type_name_empty);

        return validator.passed();
    }
</script>

@include('admin::pagefooter')
