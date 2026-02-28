@include('admin::pageheader')
<script src="{{ asset('static/admin/js/validator.js') }}"></script>
<script src="{{ asset('js/transport.js') }}"></script>
<script src="{{ asset('js/region.js') }}"></script>
<div class="main-div">
    <form method="post" action="suppliers.php" name="theForm" enctype="multipart/form-data"
          onsubmit="return validate()">
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">{{ $lang['label_suppliers_name'] }}</td>
                <td><input type="text" name="suppliers_name" maxlength="60"
                           value="{{ $suppliers['suppliers_name'] }}"/>{{ $lang['require_field'] }}</td>
            </tr>
            <tr>
                <td class="label">{{ $lang['label_suppliers_desc'] }}</td>
                <td><textarea name="suppliers_desc" cols="60" rows="4">{{ $suppliers['suppliers_desc'] }}</textarea>
                </td>
            </tr>
            <tr>
                <td class="label">
                    <a href="javascript:showNotice('noticeAdmins');" title="{{ $lang['form_notice'] }}"><img
                            src="{{ asset('static/admin/images/notice.gif') }}" width="16" height="16" border="0"
                            alt="{{ $lang['form_notice'] }}"></a>{{ $lang['label_admins'] }}</td>
                <td>@foreach($suppliers['admin_list'] as $admin)
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
        </table>

        <table align="center">
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" class="button" value="{{ $lang['button_submit'] }}"/>
                    <input type="reset" class="button" value="{{ $lang['button_reset'] }}"/>
                    <input type="hidden" name="act" value="{{ $form_action }}"/>
                    <input type="hidden" name="id" value="{{ $suppliers['suppliers_id'] }}"/>
                </td>
            </tr>
        </table>
    </form>
</div>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/validator.js') }}"></script>

<script type="text/javascript">
    document.forms['theForm'].elements['suppliers_name'].focus();
    /**
     * 检查表单输入的数据
     */
    function validate() {
        validator = new Validator("theForm");
        validator.required("suppliers_name", no_suppliers_name);
        return validator.passed();
    }

</script>

@include('admin::pagefooter')
