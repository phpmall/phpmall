@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <!-- 商品搜索 -->
    @include('goods_search')
    <!-- 商品列表 -->
    <form method="post" action="" name="listForm" onsubmit="return confirmSubmit(this)">
        <!-- start goods list -->
        <div class="list-div" id="listDiv">
            @endif
            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>
                        <input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox"/>
                        <a href="javascript:listTable.sort('goods_id'); ">{{ $lang['record_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('goods_name'); ">{{ $lang['goods_name'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('goods_sn'); ">{{ $lang['goods_sn'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('shop_price'); ">{{ $lang['shop_price'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('is_on_sale'); ">{{ $lang['is_on_sale'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('is_best'); ">{{ $lang['is_best'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('is_new'); ">{{ $lang['is_new'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('is_hot'); ">{{ $lang['is_hot'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('sort_order'); ">{{ $lang['sort_order'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    @if($use_storage)
                        <th><a href="javascript:listTable.sort('goods_number'); ">{{ $lang['goods_number'] }}</a>
                            <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                        </th>
                    @endif
                    <th>{{ $lang['handler'] }}</th>
                <tr>
                @forelse($goods_list as $goods)
                    <tr>
                        <td><input type="checkbox" name="checkboxes[]"
                                   value="{{ $goods['goods_id'] }}"/>{{ $goods['goods_id'] }}</td>
                        <td class="first-cell" style="@if($goods['is_promote'])color:red;@endif"><span
                                onclick="listTable.edit(this, 'edit_goods_name', {{ $goods['goods_id'] }})">{{ $goods['goods_name'] }}</span>
                        </td>
                        <td><span
                                onclick="listTable.edit(this, 'edit_goods_sn', {{ $goods['goods_id'] }})">{{ $goods['goods_sn'] }}</span>
                        </td>
                        <td align="right"><span
                                onclick="listTable.edit(this, 'edit_goods_price', {{ $goods['goods_id'] }})">{{ $goods['shop_price'] }}

    </span></td>
                        <td align="center"><img
                                src="{{ asset('static/admin/images/'.($goods['is_on_sale'] ? 'yes' : 'no').'.gif') }}"
                                onclick="listTable.toggle(this, 'toggle_on_sale', {{ $goods['goods_id'] }})"/></td>
                        <td align="center"><img
                                src="{{ asset('static/admin/images/'.($goods['is_best'] ? 'yes' : 'no').'.gif') }}"
                                onclick="listTable.toggle(this, 'toggle_best', {{ $goods['goods_id'] }})"/></td>
                        <td align="center"><img
                                src="{{ asset('static/admin/images/'.($goods['is_new'] ? 'yes' : 'no').'.gif') }}"
                                onclick="listTable.toggle(this, 'toggle_new', {{ $goods['goods_id'] }})"/></td>
                        <td align="center"><img
                                src="{{ asset('static/admin/images/'.($goods['is_hot'] ? 'yes' : 'no').'.gif') }}"
                                onclick="listTable.toggle(this, 'toggle_hot', {{ $goods['goods_id'] }})"/></td>
                        <td align="center"><span
                                onclick="listTable.edit(this, 'edit_sort_order', {{ $goods['goods_id'] }})">{{ $goods['sort_order'] }}</span>
                        </td>
                        @if($use_storage)
                            <td align="right"><span
                                    onclick="listTable.edit(this, 'edit_goods_number', {{ $goods['goods_id'] }})">{{ $goods['goods_number'] }}</span>
                            </td>
                        @endif
                        <td align="center">
                            <a href="../goods.php?id={{ $goods['goods_id'] }}" target="_blank"
                               title="{{ $lang['view'] }}"><img src="{{ asset('static/admin/images/icon_view.gif') }}"
                                                                width="16" height="16" border="0"/></a>
                            <a href="goods.php?act=edit&goods_id={{ $goods['goods_id'] }}@if($code != 'real_goods')&extension_code={{ $code }}@endif"
                               title="{{ $lang['edit'] }}"><img src="{{ asset('static/admin/images/icon_edit.gif') }}"
                                                                width="16" height="16" border="0"/></a>
                            <a href="goods.php?act=copy&goods_id={{ $goods['goods_id'] }}@if($code != 'real_goods')&extension_code={{ $code }}@endif"
                               title="{{ $lang['copy'] }}"><img src="{{ asset('static/admin/images/icon_copy.gif') }}"
                                                                width="16" height="16" border="0"/></a>
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $goods['goods_id'] }}, '{{ $lang['trash_goods_confirm'] }}')"
                               title="{{ $lang['trash'] }}"><img src="{{ asset('static/admin/images/icon_trash.gif') }}"
                                                                 width="16" height="16" border="0"/></a>
                            '.($specifications[$goods['goods_type']] != '' ? '<a
                                href="goods.php?act=product_list&goods_id={{ $goods['goods_id'] }}"
                                title="{{ $lang['item_list'] }}"><img
                                    src="{{ asset('static/admin/images/icon_docs.gif') }}" width="16" height="16"
                                    border="0"/></a>' : '<img src="{{ asset('static/admin/images/empty.gif') }}"
                                                              width="16" height="16" border="0"/>').'
                            @if($add_handler)
                                |
                                @foreach($add_handler as $handler)
                                    <a href="{{ $handler['url'] }}&goods_id={{ $goods['goods_id'] }}"
                                       title="{{ $handler['title'] }}"><img
                                            src="{{ asset('static/admin/images/'.$handler['img']) }}" width="16"
                                            height="16" border="0"/></a>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="10">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse
            </table>
            <!-- end goods list -->

            <!-- 分页 -->
            <table id="page-table" cellspacing="0">
                <tr>
                    <td align="right" nowrap="true">
                        @include('admin::page')
                    </td>
                </tr>
            </table>

@if($full_page)
        </div>

        <div>
            <input type="hidden" name="act" value="batch"/>
            <select name="type" id="selAction" onchange="changeAction()">
                <option value="">{{ $lang['select_please'] }}</option>
                <option value="trash">{{ $lang['trash'] }}</option>
                <option value="on_sale">{{ $lang['on_sale'] }}</option>
                <option value="not_on_sale">{{ $lang['not_on_sale'] }}</option>
                <option value="best">{{ $lang['best'] }}</option>
                <option value="not_best">{{ $lang['not_best'] }}</option>
                <option value="new">{{ $lang['new'] }}</option>
                <option value="not_new">{{ $lang['not_new'] }}</option>
                <option value="hot">{{ $lang['hot'] }}</option>
                <option value="not_hot">{{ $lang['not_hot'] }}</option>
                <option value="move_to">{{ $lang['move_to'] }}</option>
                @if($suppliers_list > 0)
                    <option value="suppliers_move_to">{{ $lang['suppliers_move_to'] }}</option>
                @endif
            </select>
            <select name="target_cat" style="display:none">
                <option value="0">{{ $lang['select_please'] }}</option>{{ $cat_list }}
            </select>
            @if($suppliers_list > 0)
                <!--二级主菜单：转移供货商-->
                <select name="suppliers_id" style="display:none">
                    <option value="-1">{{ $lang['select_please'] }}</option>
                    <option value="0">{{ $lang['lab_to_shopex'] }}</option>
                    @foreach($suppliers_list as $sl)
                        <option value="{{ $sl['suppliers_id'] }}">{{ $sl['suppliers_name'] }}</option>
                    @endforeach
                </select>
                <!--end!-->
            @endif
            @if($code != 'real_goods')
                <input type="hidden" name="extension_code" value="{{ $code }}"/>
            @endif
            <input type="submit" value="{{ $lang['button_submit'] }}" id="btnSubmit" name="btnSubmit" class="button"
                   disabled="true"/>
        </div>
    </form>

    <script type="text/javascript">
        listTable.recordCount = {{ $record_count }};
        listTable.pageCount = {{ $page_count }};

        @foreach($filter as $item => $key)
            listTable.filter.{{ $key }} = '{{ $item }}';
        @endforeach
            onload = function () {
            document.forms['listForm'].reset();
        }

        /**
         * @param: bool ext 其他条件：用于转移分类
         */
        function confirmSubmit(frm, ext) {
            if (frm.elements['type'].value === 'trash') {
                return confirm(batch_trash_confirm);
            } else if (frm.elements['type'].value === 'not_on_sale') {
                return confirm(batch_no_on_sale);
            } else if (frm.elements['type'].value === 'move_to') {
                ext = (ext === undefined) ? true : ext;
                return ext && frm.elements['target_cat'].value != 0;
            } else if (frm.elements['type'].value === '') {
                return false;
            } else {
                return true;
            }
        }

        function changeAction() {
            var frm = document.forms['listForm'];

            // 切换分类列表的显示
            frm.elements['target_cat'].style.display = frm.elements['type'].value === 'move_to' ? '' : 'none';

            @if($suppliers_list > 0)
                frm.elements['suppliers_id'].style.display = frm.elements['type'].value === 'suppliers_move_to' ? '' : 'none';
            @endif

            if (!document.getElementById('btnSubmit').disabled &&
                confirmSubmit(frm, false)) {
                frm.submit();
            }
        }

    </script>
    @include('admin::pagefooter')
@endif
