@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <div class="form-div">
        <form method="post" action="area_manage.php" name="theForm" onsubmit="return add_area()">
            @if($region_type === '0')
                {{ $lang['add_country'] }}:
            @elseif($region_type === '1')
                {{ $lang['add_province'] }}:
            @elseif($region_type === '2')
                {{ $lang['add_city'] }}:
            @elseif($region_type === '3')
                {{ $lang['add_cantonal'] }}:
            @endif
            <input type="text" name="region_name" maxlength="150" size="40"/>
            <input type="hidden" name="region_type" value="{{ $region_type }}"/>
            <input type="hidden" name="parent_id" value="{{ $parent_id }}"/>
            <input type="submit" value="{{ $lang['button_submit'] }}" class="button"/>
        </form>
    </div>

    <!-- start category list -->
    <div class="list-div">
        <table cellspacing='1' cellpadding='3' id='listTable'>
            <tr>
                <th>{{ $area_here }}</th>
            </tr>
        </table>
    </div>
    <div class="list-div" id="listDiv">
        @endif

        <table cellspacing='1' cellpadding='3' id='listTable'>
            <tr>
                @foreach($region_arr as $list)
                    @if($loop->area_name . iteration > 1 && ($loop->area_name . iteration - 1) % 3 === 0)
            </tr>
            <tr>
                @endif
                <td class="first-cell" align="left">
                    <span
                        onclick="listTable.edit(this, 'edit_area_name', '{{ $list['region_id'] }}'); return false;">{$list.region_name|escape:"html"}</span>
                    <span class="link-span">
                        @if($region_type < 3)
                            <a href="area_manage.php?act=list&type={$list.region_type+1}&pid={{ $list['region_id'] }}"
                               title="{{ $lang['manage_area'] }}">
                                {{ $lang['manage_area'] }}</a>&nbsp;&nbsp;
                        @endif
                        <a href="javascript:listTable.remove({{ $list['region_id'] }}, '{{ $lang['area_drop_confirm'] }}', 'drop_area')"
                           title="{{ $lang['drop'] }}">{{ $lang['drop'] }}</a>
                    </span>
                </td>
                @endforeach
            </tr>
        </table>

@if($full_page)
    </div>
    <script type="text/javascript">
        onload = function () {
            document.forms['theForm'].elements['region_name'].focus();
        }

        /**
         * 新建区域
         */
        function add_area() {
            var region_name = Utils.trim(document.forms['theForm'].elements['region_name'].value);
            var region_type = Utils.trim(document.forms['theForm'].elements['region_type'].value);
            var parent_id = Utils.trim(document.forms['theForm'].elements['parent_id'].value);

            if (region_name.length === 0) {
                alert(region_name_empty);
            } else {
                Ajax.call('area_manage.php?is_ajax=1&act=add_area',
                    'parent_id=' + parent_id + '&region_name=' + region_name + '&region_type=' + region_type,
                    listTable.listCallback, 'POST', 'JSON');
            }

            return false;
        }
    </script>
    @include('admin::pagefooter')
@endif
