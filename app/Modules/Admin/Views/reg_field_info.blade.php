@include('admin::pageheader')

<div class="main-div">
    <form action="reg_fields.php" method="post" name="theForm" onsubmit="return validate()">
        <table width="100%">
            <tr>
                <td class="label">{{ $lang['reg_field_name'] }}:</td>
                <td><input type="text" name="reg_field_name" value="{{ $reg_field['reg_field_name'] }}"
                           maxlength="20"/>{{ $lang['require_field'] }}</td>
            </tr>
            <tr>
                <td class="label">{{ $lang['field_order'] }}:</td>
                <td><input type="text" name="reg_field_order" value="{{ $reg_field['reg_field_order'] }}" maxlength="3"
                           size="5"/></td>
            </tr>
            <tr>
                <td class="label">{{ $lang['field_display'] }}:</td>
                <td><input type="radio" name="reg_field_display" value="1"
                           @if($reg_field['reg_field_display'] === 1)checked='checked'@endif/>{{ $lang['yes'] }}&nbsp;&nbsp;&nbsp;<input
                        type="radio" name="reg_field_display" value="0"
                        @if($reg_field['reg_field_display'] === 0)checked='checked'@endif/>{{ $lang['no'] }}</td>
            </tr>
            <tr>
                <td class="label">{{ $lang['field_need'] }}:</td>
                <td><input type="radio" name="reg_field_need" value="1"
                           @if($reg_field['reg_field_need'] === 1)checked='checked'@endif/>{{ $lang['yes'] }}&nbsp;&nbsp;&nbsp;<input
                        type="radio" name="reg_field_need" value="0"
                        @if($reg_field['reg_field_need'] === 0)checked='checked'@endif/>{{ $lang['no'] }}</td>
            </tr>
            <tr>
                <td></td>
                <td align="left">
                    <input type="hidden" name="act" value="{{ $form_action }}"/>
                    <input type="hidden" name="id" value="{{ $reg_field['reg_field_id'] }}"/>
                    <input type="hidden" name="old_field_name" value="{{ $reg_field['reg_field_name'] }}"/>
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
    document.forms['theForm'].elements['reg_field_name'].focus();
    /**
     * 检查表单输入的数据
     */
    function validate() {
        validator = new Validator("theForm");
        validator.required('reg_field_name', field_name_empty);
        return validator.passed();
    }

</script>

@include('admin::pagefooter')
