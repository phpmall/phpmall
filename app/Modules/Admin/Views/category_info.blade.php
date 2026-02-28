@include('admin::pageheader')
<!-- start add new category form -->
<div class="main-div">
    <form action="category.php" method="post" name="theForm" enctype="multipart/form-data" onsubmit="return validate()">
        <table width="100%" id="general-table">
            <tr>
                <td class="label">{{ $lang['cat_name'] }}:</td>
                <td>
                    <input type='text' name='cat_name' maxlength="20" value='{{ $cat_info['cat_name'] }}' size='27'/>
                    <font color="red">*</font>
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['parent_id'] }}:</td>
                <td>
                    <select name="parent_id">
                        <option value="0">{{ $lang['cat_top'] }}</option>
                        {{ $cat_select }}
                    </select>
                </td>
            </tr>

            <tr id="measure_unit">
                <td class="label">{{ $lang['measure_unit'] }}:</td>
                <td>
                    <input type="text" name='measure_unit' value='{{ $cat_info['measure_unit'] }}' size="12"/>
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['sort_order'] }}:</td>
                <td>
                    <input type="text" name='sort_order' '.($cat_info['sort_order'] ?
                    'value='{{ $cat_info['sort_order'] }}'' : ' value="50"').' size="15" />
                </td>
            </tr>

            <tr>
                <td class="label">{{ $lang['is_show'] }}:</td>
                <td>
                    <input type="radio" name="is_show" value="1"
                           @if($cat_info['is_show'] != 0) checked="true"@endif/> {{ $lang['yes'] }}
                    <input type="radio" name="is_show" value="0"
                           @if($cat_info['is_show'] === 0) checked="true"@endif /> {{ $lang['no'] }}
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['show_in_nav'] }}:</td>
                <td>
                    <input type="radio" name="show_in_nav" value="1"
                           @if($cat_info['show_in_nav'] != 0) checked="true"@endif/> {{ $lang['yes'] }}
                    <input type="radio" name="show_in_nav" value="0"
                           @if($cat_info['show_in_nav'] === 0) checked="true"@endif /> {{ $lang['no'] }}
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['show_in_index'] }}:</td>
                <td>
                    <input type="checkbox" name="cat_recommend[]" value="1"
                           @if($cat_recommend[1] === 1) checked="true"@endif/> {{ $lang['index_best'] }}
                    <input type="checkbox" name="cat_recommend[]" value="2"
                           @if($cat_recommend[2] === 1) checked="true"@endif /> {{ $lang['index_new'] }}
                    <input type="checkbox" name="cat_recommend[]" value="3"
                           @if($cat_recommend[3] === 1) checked="true"@endif /> {{ $lang['index_hot'] }}
                </td>
            </tr>
            <tr>
                <td class="label"><a href="javascript:showNotice('noticeFilterAttr');"
                                     title="{{ $lang['form_notice'] }}"><img
                            src="{{ asset('static/admin/images/notice.gif') }}" width="16" height="16" border="0"
                            alt="{{ $lang['notice_style'] }}"></a>{{ $lang['filter_attr'] }}:
                </td>
                <td>
                    <script type="text/javascript">
                        var arr = new Array();
                        var sel_filter_attr = "{{ $lang['sel_filter_attr'] }}";
                        @foreach($attr_list as $val => $att_cat_id)
                            arr[{{ $att_cat_id }}] = new Array();
                        @foreach($val as $item => $i)
                            @foreach($item as $attr_val => $attr_id)
                            arr[{{ $att_cat_id }}][{{ $i }}] = ["{{ $attr_val }}", {{ $attr_id }}];
                        @endforeach
                        @endforeach
                        @endforeach

                        function changeCat(obj) {
                            var key = obj.value;
                            var sel = window.ActiveXObject ? obj.parentNode.childNodes[4] : obj.parentNode.childNodes[5];
                            sel.length = 0;
                            sel.options[0] = new Option(sel_filter_attr, 0);
                            if (arr[key] === undefined) {
                                return;
                            }
                            for (var i = 0; i < arr[key].length; i++) {
                                sel.options[i + 1] = new Option(arr[key][i][0], arr[key][i][1]);
                            }

                        }

                    </script>
                    <table width="100%" id="tbody-attr" align="center">
                        @if($attr_cat_id === 0)
                            <tr>
                                <td>
                                    <a href="javascript:;" onclick="addFilterAttr(this)">[+]</a>
                                    <select onChange="changeCat(this)">
                                        <option value="0">{{ $lang['sel_goods_type'] }}</option>{{ $goods_type_list }}
                                    </select>&nbsp;&nbsp;
                                    <select name="filter_attr[]">
                                        <option value="0">{{ $lang['sel_filter_attr'] }}</option>
                                    </select><br/>
                                </td>
                            </tr>
                        @endif
                        @foreach($filter_attr_list as $filter_attr)
                            <tr>
                                <td>
                                    @if($loop->filter_attr_tab.iteration === 1)
                                        <a href="javascript:;" onclick="addFilterAttr(this)">[+]</a>
                                    @else
                                        <a href="javascript:;" onclick="removeFilterAttr(this)">[-]&nbsp;</a>
                                    @endif
                                    <select onChange="changeCat(this)">
                                        <option
                                            value="0">{{ $lang['sel_goods_type'] }}</option>{{ $filter_attr['goods_type_list'] }}
                                    </select>&nbsp;&nbsp;
                                    <select name="filter_attr[]">
                                        <option value="0">{{ $lang['sel_filter_attr'] }}</option>
                                        {html_options options=$filter_attr.option
                                        selected=$filter_attr.filter_attr}</select><br/>
                                </td>
                            </tr>
                        @endforeach
                    </table>

                    <span class="notice-span"
                          {{ $help_open ? 'style="display:block" ' : ' style="display:none" ' }} id="noticeFilterAttr">{{ $lang['filter_attr_notic'] }}</span>
                </td>
            </tr>
            <tr>
                <td class="label"><a href="javascript:showNotice('noticeGrade');"
                                     title="{{ $lang['form_notice'] }}"><img
                            src="{{ asset('static/admin/images/notice.gif') }}" width="16" height="16" border="0"
                            alt="{{ $lang['notice_style'] }}"></a>{{ $lang['grade'] }}:
                </td>
                <td>
                    <input type="text" name="grade" value="{{ $cat_info['grade'] ?? 0 }}" size="40"/> <br/>
                    <span class="notice-span"
                          {{ $help_open ? 'style="display:block" ' : ' style="display:none" ' }} id="noticeGrade">{{ $lang['notice_grade'] }}</span>
                </td>
            </tr>
            <tr>
                <td class="label"><a href="javascript:showNotice('noticeGoodsSN');"
                                     title="{{ $lang['form_notice'] }}"><img
                            src="{{ asset('static/admin/images/notice.gif') }}" width="16" height="16" border="0"
                            alt="{{ $lang['notice_style'] }}"></a>{{ $lang['cat_style'] }}:
                </td>
                <td>
                    <input type="text" name="style" value="{{ $cat_info['style'] }}" size="40"/> <br/>
                    <span class="notice-span"
                          {{ $help_open ? 'style="display:block" ' : ' style="display:none" ' }} id="noticeGoodsSN">{{ $lang['notice_style'] }}</span>
                </td>
            </tr>
            <tr>
                <td class="label">{{ $lang['keywords'] }}:</td>
                <td><input type="text" name="keywords" value='{{ $cat_info['keywords'] }}' size="50">
                </td>
            </tr>

            <tr>
                <td class="label">{{ $lang['cat_desc'] }}:</td>
                <td>
                    <textarea name='cat_desc' rows="6" cols="48">{{ $cat_info['cat_desc'] }}</textarea>
                </td>
            </tr>
        </table>
        <div class="button-div">
            <input type="submit" value="{{ $lang['button_submit'] }}"/>
            <input type="reset" value="{{ $lang['button_reset'] }}"/>
        </div>
        <input type="hidden" name="act" value="{{ $form_act }}"/>
        <input type="hidden" name="old_cat_name" value="{{ $cat_info['cat_name'] }}"/>
        <input type="hidden" name="cat_id" value="{{ $cat_info['cat_id'] }}"/>
    </form>
</div>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('static/admin/js/validator.js') }}"></script>

<script type="text/javascript">
    document.forms['theForm'].elements['cat_name'].focus();

    /**
     * 检查表单输入的数据
     */
    function validate() {
        validator = new Validator("theForm");
        validator.required("cat_name", catname_empty);
        if (parseInt(document.forms['theForm'].elements['grade'].value) > 10 || parseInt(document.forms['theForm'].elements['grade'].value) < 0) {
            validator.addErrorMsg('{{ $lang['grade_error'] }}');
        }
        return validator.passed();
    }
    /**
     * 新增一个筛选属性
     */
    function addFilterAttr(obj) {
        var src = obj.parentNode.parentNode;
        var tbl = document.getElementById('tbody-attr');

        var validator = new Validator('theForm');
        var filterAttr = document.getElementsByName("filter_attr[]");

        if (filterAttr[filterAttr.length - 1].selectedIndex === 0) {
            validator.addErrorMsg(filter_attr_not_selected);
        }

        for (i = 0; i < filterAttr.length; i++) {
            for (j = i + 1; j < filterAttr.length; j++) {
                if (filterAttr.item(i).value === filterAttr.item(j).value) {
                    validator.addErrorMsg(filter_attr_not_repeated);
                }
            }
        }

        if (!validator.passed()) {
            return false;
        }

        var row = tbl.insertRow(tbl.rows.length);
        var cell = row.insertCell(-1);
        cell.innerHTML = src.cells[0].innerHTML.replace(/(.*)(addFilterAttr)(.*)(\[)(\+)/i, "$1removeFilterAttr$3$4-");
        filterAttr[filterAttr.length - 1].selectedIndex = 0;
    }

    /**
     * 删除一个筛选属性
     */
    function removeFilterAttr(obj) {
        var row = rowindex(obj.parentNode.parentNode);
        var tbl = document.getElementById('tbody-attr');

        tbl.deleteRow(row);
    }

</script>

@include('admin::pagefooter')
