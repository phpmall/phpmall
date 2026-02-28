@if($full_page)
    @include('admin::pageheader')

    <script type="text/javascript" src="{{ asset('js/utils.js') }}"></script>
    <script type="text/javascript" src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <form method="post" action="" name="listForm">
        <!-- start ad position list -->
        <div class="list-div" id="listDiv">
            @endif

            <table width="100%" cellspacing="1" cellpadding="2" id="list-table">
                <tr>
                    <th>{{ $lang['cat_name'] }}</th>
                    <th>{{ $lang['type'] }}</th>
                    <th>{{ $lang['cat_desc'] }}</th>
                    <th>{{ $lang['sort_order'] }}</th>
                    <th>{{ $lang['show_in_nav'] }}</th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @foreach($articlecat as $cat)
                    <tr align="center" class="{{ $cat['level'] }}" id="{{ $cat['level'] }}_{{ $cat['cat_id'] }}">
                        <td align="left" class="first-cell nowrap" valign="top">
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
                                    href="article.php?act=list&amp;cat_id={{ $cat['cat_id'] }}">{{ $cat['cat_name'] }}</a></span>
                        </td>
                        <td class="nowrap" valign="top">
                            {{ $cat['type_name'] }}
                        </td>
                        <td align="left" valign="top">
                            {{ $cat['cat_desc'] }}
                        </td>
                        <td width="10%" align="right" class="nowrap" valign="top"><span
                                onclick="listTable.edit(this, 'edit_sort_order', {{ $cat['cat_id'] }})">{{ $cat['sort_order'] }}</span>
                        </td>
                        <td width="10%" class="nowrap" valign="top"><img @if($cat['show_in_nav'] === '1')
                                                                             src="{{ asset('static/admin/images/yes.gif') }}"
                                                                         @else
                                                                             src="{{ asset('static/admin/images/no.gif') }}"
                                                                         @endif
                                                                         onclick="listTable.toggle(this, 'toggle_show_in_nav', {{ $cat['cat_id'] }})"/>
                        </td>
                        <td width="24%" align="right" class="nowrap" valign="top">
                            <a href="articlecat.php?act=edit&amp;id={{ $cat['cat_id'] }}">{{ $lang['edit'] }}</a>
                            @if($cat['cat_type'] != 2 && $cat['cat_type'] != 3 && $cat['cat_type'] != 4)
                                |
                                <a href="javascript:;"
                                   onclick="listTable.remove('{{ $cat['cat_id'] }}', '{{ $lang['drop_confirm'] }}')"
                                   title="{{ $lang['remove'] }}">{{ $lang['remove'] }}</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>

@if($full_page)
        </div>
    </form>
    <script type="text/javascript">
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
                if (imgObj.tagName === "IMG" && imgObj.src != "{{ asset('static/admin/images/menu_arrow.gif') }}") {
                    imgObj.src = (imgObj.src === imgPlus.src) ? "{{ asset('static/admin/images/menu_minus.gif') }}" : imgPlus.src;
                }
            }
        }

    </script>
    @include('admin::pagefooter')
@endif
