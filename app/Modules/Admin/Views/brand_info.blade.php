@include('admin::pageheader')
<div class="main-div">
    <form method="post" action="brand.php" name="theForm" enctype="multipart/form-data" onsubmit="return validate()">
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">{{ $lang['brand_name'] }}</td>
                <td><input type="text" name="brand_name" maxlength="60"
                           value="{{ $brand['brand_name'] }}"/>{{ $lang['require_field'] }}</td>
            </tr>
            <tr>
                <td class="label">{{ $lang['site_url'] }}</td>
                <td><input type="text" name="site_url" maxlength="60" size="40" value="{{ $brand['site_url'] }}"/></td>
            </tr>
            <tr>
                <td class="label"><a href="javascript:showNotice('warn_brandlogo');" title="{{ $lang['form_notice'] }}">
                        <img src="{{ asset('static/admin/images/notice.gif') }}" width="16" height="16" border="0"
                             alt="{{ $lang['form_notice'] }}"></a>{{ $lang['brand_logo'] }}</td>
                <td><input type="file" name="brand_logo" id="logo" size="45">@if($brand['brand_logo'] != "")
                        <input type="button" value="{{ $lang['drop_brand_logo'] }}"
                               onclick="if (confirm('{{ $lang['confirm_drop_logo'] }}'))location.href='brand.php?act=drop_logo&id={{ $brand['brand_id'] }}'">
                    @endif
                    <br/><span class="notice-span"
                               {{ $help_open ? 'style="display:block" ' : ' style="display:none" ' }} id="warn_brandlogo">
    @if($brand['brand_logo'] === '')
                            {{ $lang['up_brandlogo'] }}
                        @else
                            {{ $lang['warn_brandlogo'] }}
                        @endif
    </span>
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['brand_desc'] }}</td>
                <td><textarea name="brand_desc" cols="60" rows="4">{{ $brand['brand_desc'] }}</textarea></td>
            </tr>
            <tr>
                <td class="label">{{ $lang['sort_order'] }}</td>
                <td><input type="text" name="sort_order" maxlength="40" size="15" value="{{ $brand['sort_order'] }}"/>
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['is_show'] }}</td>
                <td><input type="radio" name="is_show" value="1"
                           @if($brand['is_show'] === 1)checked="checked"@endif /> {{ $lang['yes'] }}
                    <input type="radio" name="is_show" value="0"
                           @if($brand['is_show'] === 0)checked="checked"@endif /> {{ $lang['no'] }}
                    ({{ $lang['visibility_notes'] }})
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><br/>
                    <input type="submit" class="button" value="{{ $lang['button_submit'] }}"/>
                    <input type="reset" class="button" value="{{ $lang['button_reset'] }}"/>
                    <input type="hidden" name="act" value="{{ $form_action }}"/>
                    <input type="hidden" name="old_brandname" value="{{ $brand['brand_name'] }}"/>
                    <input type="hidden" name="id" value="{{ $brand['brand_id'] }}"/>
                    <input type="hidden" name="old_brandlogo" value="{{ $brand['brand_logo'] }}">
                </td>
            </tr>
        </table>
    </form>
</div>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/validator.js') }}"></script>

<script type="text/javascript">
    document.forms['theForm'].elements['brand_name'].focus();
    /**
     * 检查表单输入的数据
     */
    function validate() {
        validator = new Validator("theForm");
        validator.required("brand_name", no_brandname);
        validator.isNumber("sort_order", require_num, true);
        return validator.passed();
    }

</script>

@include('admin::pagefooter')
