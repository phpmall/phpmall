@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <form method="post" action="" name="listForm">
        <!-- start ad position list -->
        <div class="list-div" id="listDiv">
            @endif

            <table width="100%" cellspacing="1" cellpadding="2" id="list-table">
                <tr>
                    <th>{{ $lang['cat_name'] }}</th>
                    <th>{{ $lang['goods_number'] }}</th>
                    <th>{{ $lang['measure_unit'] }}</th>
                    <th>{{ $lang['nav'] }}</th>
                    <th>{{ $lang['is_show'] }}</th>
                    <th>{{ $lang['short_grade'] }}</th>
                    <th>{{ $lang['sort_order'] }}</th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @foreach($cat_info as $cat)
                    <tr align="center" class="{{ $cat['level'] }}" id="{{ $cat['level'] }}_{{ $cat['cat_id'] }}">
                        <td align="left" class="first-cell">
                            @if($cat['is_leaf'] != 1)
                                <img src="{{ asset('static/admin/images/menu_minus.gif') }}"
                                     id="icon_{{ $cat['level'] }}_{{ $cat['cat_id'] }}" width="9" height="9" border="0"
                                     style="margin-left:{{ $cat['level'] }}em" onclick="rowClicked(this)"/>
                            @else
                                <img src="{{ asset('static/admin/images/menu_arrow.gif') }}" width="9" height="9"
                                     border="0"
                                     style="margin-left:{{ $cat['level'] }}em"/>
                            @endif
                            <span><a
                                    href="goods.php?act=list&cat_id={{ $cat['cat_id'] }}">{{ $cat['cat_name'] }}</a></span>
                            @if($cat['cat_image'])
                                <img src="../{{ $cat['cat_image'] }}" border="0" style="vertical-align:middle;"
                                     width="60px"
                                     height="21px">
                            @endif
                        </td>
                        <td width="10%">{{ $cat['goods_num'] }}</td>
                        <td width="10%"><span
                                onclick="listTable.edit(this, 'edit_measure_unit', {{ $cat['cat_id'] }})">'.($cat['measure_unit']
                            ? '{{ $cat['measure_unit'] }}' : '&nbsp;&nbsp;&nbsp;&nbsp;').'</span>
                        </td>
                        <td width="10%"><img
                                src="{{ asset('static/admin/images/' . ($cat['show_in_nav'] === '1' ? 'yes' : 'no') . '.gif') }}"
                                onclick="listTable.toggle(this, 'toggle_show_in_nav', {{ $cat['cat_id'] }})"/></td>
                        <td width="10%"><img
                                src="{{ asset('static/admin/images/' . ($cat['is_show'] === '1' ? 'yes' : 'no') . '.gif') }}"
                                onclick="listTable.toggle(this, 'toggle_is_show', {{ $cat['cat_id'] }})"/></td>
                        <td><span
                                onclick="listTable.edit(this, 'edit_grade', {{ $cat['cat_id'] }})">{{ $cat['grade'] }}</span>
                        </td>
                        <td width="10%" align="right"><span
                                onclick="listTable.edit(this, 'edit_sort_order', {{ $cat['cat_id'] }})">{{ $cat['sort_order'] }}</span>
                        </td>
                        <td width="24%" align="center">
                            <a href="category.php?act=move&cat_id={{ $cat['cat_id'] }}">{{ $lang['move_goods'] }}</a> |
                            <a href="category.php?act=edit&amp;cat_id={{ $cat['cat_id'] }}">{{ $lang['edit'] }}</a> |
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $cat['cat_id'] }}, '{{ $lang['drop_confirm'] }}')"
                               title="{{ $lang['remove'] }}">{{ $lang['remove'] }}</a>
                        </td>
                    </tr>
                @endforeach
            </table>
@if($full_page)
        </div>
    </form>
    <script type="text/javascript">
        onload = function () {
            var imgPlus = new Image();
            imgPlus.src = "{{ asset('static/admin/images/menu_plus.gif') }}";

            /**
             * 折叠分类列表
             */
            function rowClicked(obj) {
                // 当前图像
                img = obj;
                // 取得上二级tr>td>img对象
                obj = obj.parentNode.parentNode;
                // 整个分类列表表格
                var tbl = document.getElementById("list-table");
                // 当前分类级别
                var lvl = parseInt(obj.className);
                // 是否找到元素
                var fnd = false;
                var sub_display = img.src.indexOf('menu_minus.gif') > 0 ? 'none' : (Browser.isIE) ? 'block' : 'table-row';
                // 遍历所有的分类
                for (i = 0; i < tbl.rows.length; i++) {
                    var row = tbl.rows[i];
                    if (row === obj) {
                        // 找到当前行
                        fnd = true;
                        //document.getElementById('result').innerHTML += 'Find row at ' + i +"<br/>";
                    } else {
                        if (fnd === true) {
                            var cur = parseInt(row.className);
                            var icon = 'icon_' + row.id;
                            if (cur > lvl) {
                                row.style.display = sub_display;
                                if (sub_display != 'none') {
                                    var iconimg = document.getElementById(icon);
                                    iconimg.src = iconimg.src.replace('plus.gif', 'minus.gif');
                                }
                            } else {
                                fnd = false;
                                break;
                            }
                        }
                    }
                }

                for (i = 0; i < obj.cells[0].childNodes.length; i++) {
                    var imgObj = obj.cells[0].childNodes[i];
                    if (imgObj.tagName === "IMG" && imgObj.src != 'images/menu_arrow.gif') {
                        imgObj.src = (imgObj.src === imgPlus.src) ? 'images/menu_minus.gif' : imgPlus.src;
                    }
                }
            }

    </script>
    @include('admin::pagefooter')
@endif
