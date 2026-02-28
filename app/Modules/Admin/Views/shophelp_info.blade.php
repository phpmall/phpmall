@include('admin::pageheader')
<form method="post" action="shophelp.php" name="theForm" onsubmit="return validate()">
    <div class="form-div">
        <table>
            <tr>
                <td width="80px">{{ $lang['title'] }}</td>
                <td><input type="text" name="title" size="50" maxlength="60"
                           value="{{ $article['title'] }}"/>{{ $lang['require_field'] }}</td>
            </tr>
            <tr>
                <td>{{ $lang['cat'] }}</td>
                <td>
                    {{ $cat_list }}
                    {{ $lang['require_field'] }}
                </td>
            </tr>
            <tr>
                <td>{{ $lang['article_type'] }}</td>
                <td>
                    <input type="radio" name="article_type" value="0"
                           @if($article['article_type'] === 0)checked="true"@endif>{{ $lang['common'] }}
                    <input type="radio" name="article_type" value="1"
                           @if($article['article_type'] === 1)checked="true"@endif>{{ $lang['top'] }}

                    {{ $lang['require_field'] }}
                </td>
            </tr>
        </table>
    </div>

    <div class="main-div">
        <table>
            <tr>
                <td colspan="2" align="center">
                    {{ $FCKeditor }}</script>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center"><br/>
                    <input type="submit" class="button" value="{{ $lang['button_submit'] }}"/>
                    <input type="reset" class="button" value="{{ $lang['button_reset'] }}"/>
                    <input type="hidden" name="act" value="{{ $form_action }}"/>
                    <input type="hidden" name="old_title" value="{{ $article['title'] }}"/>
                    <input type="hidden" name="id" value="{{ $article['article_id'] }}"/>
                </td>
            </tr>
        </table>
    </div>
</form>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/validator.js') }}"></script>
<script type="text/javascript">
    document.forms['theForm'].elements['title'].focus();
    /**
     * 检查表单输入的数据
     */
    function validate() {
        validator = new Validator("theForm");
        validator.required("title", no_title);
        validator.isNullOption('cat_id', no_cat)
        return validator.passed();
    }
</script>
@include('admin::pagefooter')
