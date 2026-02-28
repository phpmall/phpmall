@include('admin::pageheader')
<script src="{{ asset('static/admin/js/validator.js') }}"></script>
<div class="main-div">
    <form action="navigator.php" method="post" name="form" onSubmit="return checkForm();">
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td>{{ $lang['system_main'] }}</td>
                <td>
                    <select onchange="add_main(this.value);" name="menulist" id="menulist">
                        <option value='-'>-</option>
                        @foreach($sysmain as $val => $key)
                            <option value='{{ $key }}'>'.($val['2'] ? '{{ $val['2'] }}' : '{{ $val['0'] }}').'</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td>{{ $lang['item_name'] }}</td>
                <td><input type="text" name="item_name" value="{{ $rt['item_name'] }}" id="item_name" size="40"
                           onKeyPress="javascript:key();"/></td>
            </tr>
            <tr>
                <td><a href="javascript:showNotice('notice_url');" title="{{ $lang['notice_url'] }}"><img
                            src="{{ asset('static/admin/images/notice.gif') }}" width="16" height="16" border="0"
                            alt="{{ $lang['form_notice'] }}">{{ $lang['item_url'] }}</a></td>
                <td><input type="text" name="item_url" value="{{ $rt['item_url'] }}" id="item_url" size="40"
                           onKeyPress="javascript:key();"/></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <span class="notice-span" {{ $help_open ? 'style="display:block" ' : ' style="display:none" ' }}
                    id="notice_url">{{ $lang['notice_url'] }}</span>
                </td>
            </tr>
            <tr>
                <td>{{ $lang['item_vieworder'] }}</td>
                <td><input type="text" name="item_vieworder" value="{{ $rt['item_vieworder'] }}" size="40"/></td>
            </tr>
            <tr>
                <td>{{ $lang['item_ifshow'] }}</td>
                <td><select name="item_ifshow">
                        <option value='1' {{ $rt['item_ifshow_1'] }}>{{ $lang['yes'] }}</option>
                        <option value='0' {{ $rt['item_ifshow_0'] }}>{{ $lang['no'] }}</option>
                    </select></td>
            </tr>
            <tr>
                <td>{{ $lang['item_opennew'] }}</td>
                <td><select name="item_opennew">
                        <option value='0' {{ $rt['item_opennew_0'] }}>{{ $lang['no'] }}</option>
                        <option value='1' {{ $rt['item_opennew_1'] }}>{{ $lang['yes'] }}</option>
                    </select></td>
            </tr>
            <tr>
                <td>{{ $lang['item_type'] }}</td>
                <td><select name="item_type">
                        <option value='top' {{ $rt['item_type_top'] }}>{{ $lang['top'] }}</option>
                        <option value='middle' {{ $rt['item_type_middle'] }}>{{ $lang['middle'] }}</option>
                        <option value='bottom' {{ $rt['item_type_bottom'] }}>{{ $lang['bottom'] }}</option>
                    </select></td>
            </tr>
            <tr align="center">
                <td colspan="2">
                    <input type="hidden" name="id" value="{{ $rt['id'] }}"/>
                    <input type="hidden" name="step" value="2"/>
                    <input type="hidden" name="act" value="{{ $rt['act'] }}"/>
                    <input type="submit" class="button" name="Submit" value="{{ $lang['button_submit'] }}"/>
                </td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript">
    var last;

    function add_main(key) {
        var sysm = new Object;
        @foreach($sysmain as $val => $key)
            sysm[{{ $key }}] = new Array();
        sysm[{{ $key }}][0] = '{{ $val['0'] }}';
        sysm[{{ $key }}][1] = '{{ $val['1'] }}';
        @endforeach
        if (key != '-') {
            if (sysm[key][0] != '-') {
                document.getElementById('item_name').value = sysm[key][0];
                document.getElementById('item_url').value = sysm[key][1];
                last = document.getElementById('menulist').selectedIndex;
            } else {
                if (last < document.getElementById('menulist').selectedIndex) {
                    document.getElementById('menulist').selectedIndex++;
                } else {
                    document.getElementById('menulist').selectedIndex--;
                }
                last = document.getElementById('menulist').selectedIndex;
                document.getElementById('item_name').value = sysm[last - 1][0];
                document.getElementById('item_url').value = sysm[last - 1][1];
            }
        } else {
            last = document.getElementById('menulist').selectedIndex = 1;
            document.getElementById('item_name').value = sysm[last - 1][0];
            document.getElementById('item_url').value = sysm[last - 1][1];
        }
    }

    function checkForm() {
        if (document.getElementById('item_name').value === '') {
            alert('{{ $lang['namecannotnull'] }}');
            return false;
        }
        if (document.getElementById('item_url').value === '') {
            alert('{{ $lang['linkcannotnull'] }}');
            return false;
        }
        return true;
    }

    function key() {
        last = document.getElementById('menulist').selectedIndex = 0;
    }

</script>
@include('admin::pagefooter')
