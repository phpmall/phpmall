@include('admin::pageheader')
<script src="{{ asset('static/admin/js/validator.js') }}"></script>
<form action="flashplay.php" method="post" enctype="multipart/form-data">
    <div class="main-div">
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label"><a href="javascript:showNotice('width_height');"
                                     title="{{ $lang['form_notice'] }}"><img
                            src="{{ asset('static/admin/images/notice.gif') }}" width="16" height="16" border="0"
                            alt="{{ $lang['form_notice'] }}"></a>{{ $lang['img_src'] }}</td>
                <td><input type="file" name="img_file_src" value="" id="some_name" size="40"/>
                    <br/><input name="img_src" type="text" value="{{ $rt['img_src'] }}" size="40"/>
                    <br/><span class="notice-span"
                               {{ $help_open ? 'style="display:block" ' : ' style="display:none" ' }} id="width_height">{{ $width_height }}</span>
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['img_url'] }}</td>
                <td><input name="img_url" type="text"
                           value="'.($smarty['get']['ad_link'] ? '{{ $smarty['get']['ad_link'] }}' : '{{ $rt['img_url'] }}').'"
                           size="40"/></td>
            </tr>
            <tr>
                <td class="label">{{ $lang['schp_imgdesc'] }}</td>
                <td><input name="img_text" type="text" value="{{ $rt['img_txt'] }}" size="40"/></td>
            </tr>
            <tr>
                <td class="label">{{ $lang['schp_sort'] }}</td>
                <td><input name="img_sort" type="text" value="{{ $rt['img_sort'] }}" size="4" maxlength="3"/></td>
            </tr>
            <tr align="center">
                <td colspan="2">
                    <input type="hidden" name="id" value="{{ $rt['id'] }}"/>
                    <input type="hidden" name="step" value="2"/>
                    <input type="hidden" name="act" value="{{ $rt['act'] }}"/>
                    <input type="submit" class="button" name="Submit" value="{{ $lang['button_submit'] }}"/>
                    <input type="reset" class="button" name="Reset" value="{{ $lang['button_reset'] }}"/>
                </td>
            </tr>
        </table>
    </div>
</form>
<script type="text/javascript">
</script>
@include('admin::pagefooter')
