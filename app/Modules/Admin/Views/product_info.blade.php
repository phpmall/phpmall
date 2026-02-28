@if($full_page)

    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <script src="{{ asset('static/admin/js/validator.js') }}"></script>
    <!-- 添加货品 -->
    <div class="list-div" style="margin-bottom: 5px; margin-top: 10px;" id="listDiv">

        @endif

        <form method="post" action="goods.php" name="addForm" id="addForm">
            <input type="hidden" name="goods_id" value="{{ $goods_id }}"/>
            <input type="hidden" name="act" value="product_add_execute"/>
            <table width="100%" cellpadding="3" cellspacing="1" id="table_list">
                <tr>
                    <th colspan="20" scope="col">{{ $goods_name }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $goods_sn }}</th>
                </tr>
                <tr>
                    <!-- start for specifications -->
                    @forelse($attribute as $attribute_value)
                        <td scope="col">
                            <div align="center"><strong>{{ $attribute_value['attr_name'] }}</strong></div>
                        </td>
                    @empty
                        <td scope="col">&nbsp;</td>
                    @endforelse
                    <!-- end for specifications -->
                    <td class="label_2">{{ $lang['goods_sn'] }}</td>
                    <td class="label_2">{{ $lang['goods_number'] }}</td>
                    <td class="label_2">&nbsp;</td>
                </tr>

                @foreach($product_list as $product)
                    <tr>
                        @foreach($product['goods_attr'] as $goods_attr)
                            <td scope="col">
                                <div align="center">{{ $goods_attr }}</div>
                            </td>
                        @endforeach
                        <td class="td_1"><span
                                onclick="listTable.edit(this, 'edit_product_sn', {{ $product['product_id'] }})">{{ $product['product_sn'] ?? $lang . n_a }}</span>
                        </td>
                        <td class="td_1"><span
                                onclick="listTable.edit(this, 'edit_product_number', {{ $product['product_id'] }})">{{ $product['product_number'] }}</span>
                        </td>
                        <td><input type="button" class="button" value=" - "
                                   onclick="listTable.remove({{ $product['product_id'] }}, '{{ $lang['trash_product_confirm'] }}', 'product_remove')"/>
                        </td>
                    </tr>
                @endforeach

                <tr id="attr_row">
                    <!-- start for specifications_value -->
                    @foreach($attribute as $attribute_value => $attribute_key)
                        <td align="center">
                            <select name="attr[{{ $attribute_value['attr_id'] }}][]">
                                <option value="" selected>{{ $lang['select_please'] }}</option>
                                @foreach($attribute_value['attr_values'] as $value)
                                    <option value="{{ $value }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </td>
                    @endforeach
                    <!-- end for specifications_value -->

                    <td class="label_2"><input type="text" name="product_sn[]" value="" size="20"/></td>
                    <td class="label_2"><input type="text" name="product_number[]" value="{{ $product_number }}"
                                               size="10"/></td>
                    <td><input type="button" class="button" value=" + " onclick="javascript:add_attr_product();"/></td>
                </tr>

                <tr>
                    <td align="center" colspan="{{ $attribute_count_3 }}">
                        <input type="button" class="button" value="{{ $lang['button_save'] }}"
                               onclick="checkgood_sn()"/>
                    </td>
                </tr>
            </table>
        </form>

@if($full_page)

    </div>
    <!-- end 添加货品 -->

    <script type="text/javascript">

        @foreach($filter as $item => $key)
            listTable.filter.{{ $key }} = '{{ $item }}';
        @endforeach

            listTable.query = 'product_query';

        var _attr = new Array;
        @foreach($attribute as $attribute_value => $attribute_key)
            _attr[{{ $attribute_key }}] = '{{ $attribute_value['attr_id'] }}';

        @endforeach

        /**
         * 追加货品添加表格
         */
        function add_attr_product() {
            var table_list = document.getElementById('table_list');
            var new_tr_id = rand_str('t');
            var attr_row, attr_col, new_row, new_col;
            var index = table_list.rows.length - 2; //此行号为输入框所在行

            //创建新行
            attr_row = document.getElementById('attr_row');
            attr_col = attr_row.getElementsByTagName('td');

            new_row = table_list.insertRow(index);//新增行
            new_row.setAttribute("id", new_tr_id);

            //创建新行的列
            for (var i = 0; i < attr_col.length; i++) {
                new_col = new_row.insertCell(-1);
                new_col.setAttribute("align", attr_col[i].getAttribute("align"));
                new_col.setAttribute("class", attr_col[i].getAttribute("class"));
                new_col.setAttribute("className", attr_col[i].getAttribute("className"));

                if (i + 1 === attr_col.length) {
                    new_col.innerHTML = '<input type="button" class="button" value=" - " onclick="javascript:minus_attr_product(\'' + new_tr_id + '\');"/>';
                } else {
                    new_col.innerHTML = attr_col[i].innerHTML;
                }
            }

            //重置选项
            //  var sel = new_row.getElementsByTagName('select');
            //  if (sel.length > 0)
            //  {
            //    for (var j = 0; j < sel.length; j++)
            //    {
            //      sel[j].value = '';
            //    }
            //  }

            return true;
        }

        /**
         * 删除追加的货品表格
         */
        function minus_attr_product(tr_number) {
            if (tr_number.length > 0) {
                if (confirm("确定删除吗？") === false) {
                    return false;
                }

                var table_list = document.getElementById('table_list');

                for (var i = 0; i < table_list.rows.length; i++) {
                    if (table_list.rows[i].id === tr_number) {
                        table_list.deleteRow(i);
                    }
                }
            }

            return true;
        }

        function checkgood_sn() {
            var validator = new Validator('addForm');
            var volumePri = document.getElementsByName("product_sn[]");
            var check_sn = '';
            for (i = 0; i < volumePri.length; i++) {
                if (volumePri.item(i).value === "") {
                } else {
                    check_sn = check_sn + '||' + volumePri.item(i).value;
                }
            }
            var callback = function (res) {
                if (res.error > 0) {
                    alert(res.message);
                } else {
                    document.forms['addForm'].submit();
                }
            }
            Ajax.call('goods.php?is_ajax=1&act=check_products_goods_sn', "goods_sn=" + check_sn, callback, "GET", "JSON");

        }
    </script>
    @include('admin::pagefooter')

@endif
