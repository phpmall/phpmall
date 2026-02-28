@include('admin::pageheader')
<div class="main-div">
    <form action="bonus.php" method="post" name="theForm" enctype="multipart/form-data" onsubmit="return validate()">
        <table width="100%">
            <tr>
                <td class="label">{{ $lang['bonus_type_id'] }}</td>
                <td>
                    <select name="bonus_type_id">
                        {html_options options=$type_list selected=$smarty.get.id}
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['send_bonus_count'] }}</td>
                <td>
                    <input type="text" name="bonus_sum" size="30" maxlength="6"/>
                </td>
            </tr>
            <td class="label">&nbsp;</td>
            <td>{{ $lang['bonus_sn_notic'] }}</td>
            </tr>
            <tr>
                <td class="label">&nbsp;</td>
                <td>
                    <input type="submit" value="{{ $lang['button_submit'] }}" class="button"/>
                    <input type="reset" value="{{ $lang['button_reset'] }}" class="button"/>
                </td>
            </tr>
        </table>
        <input type="hidden" name="act" value="send_by_print"/>
    </form>
</div>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/validator.js') }}"></script>

<script type="text/javascript">
    document.forms['theForm'].elements['bonus_sum'].focus();

    /**
     * 检查表单输入的数据
     */
    function validate() {
        validator = new Validator("theForm");
        validator.required("bonus_type_id", bonus_type_empty);
        validator.required("bonus_sum", bonus_sum_empty);
        validator.isNumber("bonus_sum", bonus_sum_number, true);
        return validator.passed();
    }
</script>

@include('admin::pagefooter')
