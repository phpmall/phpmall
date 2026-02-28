@include('admin::pageheader')
<div class="main-div">
    <form action="category.php" method="post" name="theForm" enctype="multipart/form-data">
        <table width="100%">
            <tr>
                <td>
                    <div style="font-weight:bold"><img src="{{ asset('static/admin/images/notice.gif') }}" width="16"
                                                       height="16"
                                                       border="0"/> {{ $lang['cat_move_desc'] }}</div>
                    <ul>
                        <li>{{ $lang['cat_move_notic'] }}</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <strong>{{ $lang['source_cat'] }}</strong>&nbsp;&nbsp;
                    <select name="cat_id">
                        <option value="0">{{ $lang['select_please'] }}</option>
                        {{ $cat_select }}
                    </select>&nbsp;&nbsp;
                    <strong>{{ $lang['target_cat'] }}</strong>
                    <select name="target_cat_id">
                        <option value="0">{{ $lang['select_please'] }}</option>
                        {{ $cat_select }}
                    </select>&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="submit" name="move_cat" value="{{ $lang['start_move_cat'] }}" class="button">
                    <input type="reset" value="{{ $lang['button_reset'] }}" class="button"/>
                    <input type="hidden" name="act" value="{{ $form_act }}"/>
                </td>
            </tr>
        </table>
    </form>
</div>

<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/validator.js') }}"></script>
<script type="text/javascript">

</script>

@include('admin::pagefooter')
