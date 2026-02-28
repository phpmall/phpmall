@include('admin::pageheader')
<div class="main-div">
    <form method="post" action="pack.php" name="theForm" enctype="multipart/form-data" onsubmit="return validate()">
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">{{ $lang['pack_name'] }}</td>
                <td><input type="text" name="pack_name" maxlength="60" size="30"
                           value="{{ $pack['pack_name'] }}"/>{{ $lang['require_field'] }}</td>
            </tr>
            <tr>
                <td class="label"><a href="javascript:showNotice('notice_packfee');" title="{{ $lang['form_notice'] }}">
                        <img src="{{ asset('static/admin/images/notice.gif') }}" width="16" height="16" border="0"
                             alt="{{ $lang['form_notice'] }}"></a>{{ $lang['pack_fee'] }}</td>
                <td><input type="text" name="pack_fee" maxlength="60" size="30"
                           value="{{ $pack['pack_fee'] }}"/>{{ $lang['require_field'] }}
                    <br/><span class="notice-span"
                               {{ $help_open ? 'style="display:block" ' : ' style="display:none" ' }} id="notice_packfee">{{ $lang['notice_packfee'] }}</span>
                </td>
            </tr>
            <tr>
                <td class="label"><a href="javascript:showNotice('notice_freemoney');"
                                     title="{{ $lang['form_notice'] }}">
                        <img src="{{ asset('static/admin/images/notice.gif') }}" width="16" height="16" border="0"
                             alt="{{ $lang['form_notice'] }}"></a>{{ $lang['free_money'] }}</td>
                <td><input type="text" name="free_money" maxlength="60" size="30"
                           value="{{ $pack['free_money'] }}"/>{{ $lang['require_field'] }}
                    <br/><span class="notice-span"
                               {{ $help_open ? 'style="display:block" ' : ' style="display:none" ' }} id="notice_freemoney">{{ $lang['notice_freemoney'] }}</span>
                </td>
            </tr>
            <tr>
                <td class="label">@if($pack['pack_img'] != "")
                        <a href="javascript:showNotice('warn_packimg');" title="{{ $lang['form_notice'] }}">
                            <img src="{{ asset('static/admin/images/warning_small.gif') }}" width="16" height="16"
                                 border="0" alt="{{ $lang['form_notice'] }}"></a>
                    @endif{{ $lang['pack_img'] }}</td>
                <td><input type="file" name="pack_img" size="45">@if($pack['pack_img'] != "")
                        <input type="button" value="{{ $lang['drop_pack_img'] }}"
                               onclick="if (confirm('{{ $lang['confirm_pack_img'] }}'))location.href='pack.php?act=drop_pack_img&id={{ $pack['pack_id'] }}'">
                    @endif
                    <br/><span class="notice-span"
                               {{ $help_open ? 'style="display:block" ' : ' style="display:none" ' }} id="warn_packimg">{{ $lang['warn_packimg'] }}</span>
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['pack_desc'] }}</td>
                <td><textarea name="pack_desc" cols="60" rows="4">{{ $pack['pack_desc'] }}</textarea></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <input type="submit" class="button" value="{{ $lang['button_submit'] }}"/>
                    <input type="reset" class="button" value="{{ $lang['button_reset'] }}"/>
                    <input type="hidden" name="act" value="{{ $form_action }}"/>
                    <input type="hidden" name="id" value="{{ $pack['pack_id'] }}"/>
                    <input type="hidden" name="old_packname" value="{{ $pack['pack_name'] }}"/>
                    <input type="hidden" name="old_packimg" value="{{ $pack['pack_img'] }}">
                </td>
            </tr>
        </table>
    </form>
</div>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/validator.js') }}"></script>

<script type="text/javascript">
    document.forms['theForm'].elements['pack_name'].focus();

    /**
     * 检查表单输入的数据
     */
    function validate() {
        validator = new Validator("theForm");
        validator.required("pack_name", no_packname);
        validator.isNumber("pack_fee", packfee_un_num, 1);
        validator.isNumber("free_money", packmoney_un_num, 1);
        return validator.passed();
    }
</script>

@include('admin::pagefooter')
