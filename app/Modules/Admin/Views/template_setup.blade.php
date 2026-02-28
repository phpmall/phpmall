@include('admin::pageheader')

<div class="form-div">
    <form method="post" action="template.php">
        {{ $lang['select_template'] }}
        <select name="template_file">
            {html_options options=$lang.template_files selected=$curr_template_file}
        </select>
        <input type="submit" value="{{ $lang['button_submit'] }}" class="button"/>
        <input type="hidden" name="act" value="setup"/>
    </form>
</div>

<!-- start template options list -->
<div class="list-div">
    <form name="theForm" action="template.php" method="post">
        <table width="100%" cellpadding="3" cellspacing="1">
            <tr>
                <th>{{ $lang['library_name'] }}</th>
                <th>{{ $lang['region_name'] }}</th>
                <th>{{ $lang['sort_order'] }}</th>
                <th>{{ $lang['contents'] }}</th>
                <th>{{ $lang['number'] }}</th>
                <th>{{ $lang['display'] }}</th>
            </tr>
            @foreach($temp_options as $library => $lib_name)
                <tr>
                    <td class="first-cell">{{ $library['desc'] }}</td>
                    <td><select name="regions[{{ $lib_name }}]">'.($library['editable'] === 1 ? '
                            <option value="">{{ $lang['not_editable'] }}</option>
                            ' : '
                            <option value="">{{ $lang['select_plz'] }}</option>
                            {html_options values=$temp_regions output=$temp_regions
                            selected=$library.region}').'
                        </select></td>
                    <td><input type="text" name="sort_order[{{ $lib_name }}]" value="{{ $library['sort_order'] }}"
                               size="4"
                               @if($library['editable'] === 1) disabled @endif /></td>
                    <td><input type="hidden" name="map[{{ $lib_name }}]" value="{{ $library['library'] }}"/></td>
                    <td>'.($library['number_enabled'] ? '<input type="text" name="number[{{ $lib_name }}]"
                                                                value="{{ $library['number'] }}" size="4"/>' : '&nbsp;').'
                    </td>
                    <td align="center"><input type="checkbox" name="display[{{ $lib_name }}]" value="1"
                                              @if($library['editable'] === 1) disabled
                                              @endif@if($library['display'] === 1) checked="true"
                            @endif /></td>
                </tr>
            @endforeach

            <!-- cateogry goods list -->
            <tr>
                <td colspan="6" style="background-color: #F4FBFB; font-weight: bold" align="left"><a href="javascript:;"
                                                                                                     onclick="addCatGoods(this)">[+]</a> {{ $lang['template_libs']['cat_goods'] }}
                </td>
            </tr>
            @foreach($cate_goods as $library => $lib_name)
                <tr>
                    <td class="first-cell" align="right"><a href="javascript:;" onclick="removeRow(this)">[-]</a></td>
                    <td><select name="regions[cat_goods][]">
                            <option value="">{{ $lang['select_plz'] }}</option>
                            {html_options values=$temp_regions output=$temp_regions selected=$library.region}
                        </select>
                    </td>
                    <td><input type="text" name="sort_order[cat_goods][]" value="{{ $library['sort_order'] }}"
                               size="4"/>
                    </td>
                    <td><select name="categories[cat_goods][]">
                            <option value="">{{ $lang['select_plz'] }}</option>{{ $library['cats'] }}
                        </select></td>
                    <td><input type="text" name="number[cat_goods][]" value="{{ $library['number'] }}" size="4"/></td>
                    <td></td>
                </tr>
            @endforeach

            <tr>
                <td colspan="6" style="background-color: #F4FBFB; font-weight: bold" align="left"><a href="javascript:;"
                                                                                                     onclick="addBrandGoods(this)">[+]</a> {{ $lang['template_libs']['brand_goods'] }}
                </td>
            </tr>
            @foreach($brand_goods as $library => $lib_name)
                <tr>
                    <td class="first-cell" align="right"><a href="javascript:;" onclick="removeRow(this)">[-]</a></td>
                    <td><select name="regions[brand_goods][]">
                            <option value="">{{ $lang['select_plz'] }}</option>
                            {html_options values=$temp_regions output=$temp_regions selected=$library.region}
                        </select>
                    </td>
                    <td><input type="text" name="sort_order[brand_goods][]" value="{{ $library['sort_order'] }}"
                               size="4"/>
                    </td>
                    <td><select name="brands[brand_goods][]">
                            <option value="">{{ $lang['select_plz'] }}</option>
                            {html_options options=$arr_brands selected=$library.brand}
                        </select></td>
                    <td><input type="text" name="number[brand_goods][]" value="{{ $library['number'] }}" size="4"/></td>
                    <td></td>
                </tr>
            @endforeach

            <tr>
                <td colspan="6" style="background-color: #F4FBFB; font-weight: bold" align="left"><a href="javascript:;"
                                                                                                     onclick="addArticles(this)">[+]</a> {{ $lang['template_libs']['articles'] }}
                </td>
            </tr>
            @foreach($cat_articles as $library => $lib_name)
                <tr>
                    <td class="first-cell" align="right"><a href="javascript:;" onclick="removeRow(this)">[-]</a></td>
                    <td><select name="regions[cat_articles][]">
                            <option value="">{{ $lang['select_plz'] }}</option>
                            {html_options values=$temp_regions output=$temp_regions selected=$library.region}
                        </select>
                    </td>
                    <td><input type="text" name="sort_order[cat_articles][]" value="{{ $library['sort_order'] }}"
                               size="4"/></td>
                    <td><select name="article_cat[cat_articles][]">
                            <option value="0">{{ $lang['select_plz'] }}</option>{{ $library['cat'] }}
                        </select></td>
                    <td><input type="text" name="number[cat_articles][]" value="{{ $library['number'] }}" size="4"/>
                    </td>
                    <td></td>
                </tr>
            @endforeach

            <tr>
                <td colspan="6" style="background-color: #F4FBFB; font-weight: bold" align="left"><a href="javascript:;"
                                                                                                     onclick="addAdPosition(this)">[+]</a> {{ $lang['template_libs']['ad_position'] }}
                </td>
            </tr>
            @foreach($ad_positions as $ad_position => $lib_name)
                <tr>
                    <td class="first-cell" align="right"><a href="javascript:;" onclick="removeRow(this)">[-]</a></td>
                    <td><select name="regions[ad_position][]">
                            <option value="">{{ $lang['select_plz'] }}</option>
                            {html_options values=$temp_regions output=$temp_regions
                            selected=$ad_position.region}
                        </select></td>
                    <td><input type="text" name="sort_order[ad_position][]" value="{{ $ad_position['sort_order'] }}"
                               size="4"/></td>
                    <td><select name="ad_position[]">
                            <option value="0">{{ $lang['select_plz'] }}</option>
                            {html_options options=$arr_ad_positions selected=$ad_position.ad_pos}
                        </select></td>
                    <td><input type="text" name="number[ad_position][]" value="{{ $ad_position['number'] }}" size="4"/>
                    </td>
                    <td></td>
                </tr>
            @endforeach

        </table>
        <div class="button-div ">
            <input type="submit" value="{{ $lang['button_submit'] }}" class="button"/>
            <input type="reset" value="{{ $lang['button_reset'] }}" class="button"/>
            <input type="hidden" name="act" value="setting"/>
            <input type="hidden" name="template_file" value="{{ $curr_template_file }}"/>
        </div>
    </form>
</div>

<!-- end template options list -->

<script type="text/javascript">
    var currTemplateFile = '{{ $curr_template_file }}';
    var selCategories = '{{ $arr_cates }}';
    var selRegions = new Array();
    var selBrands = new Array();
    var selArticleCats = new Array();
    var selAdPositions = new Array();

    @foreach($temp_regions as $region => $id)
        selRegions[{{ $id }}] = '{{{ $region }}}';
    @endforeach

        @foreach($arr_brands as $brand => $id)
        selBrands[{{ $id }}] = '{{{ $brand }}}';
    @endforeach

        @foreach($arr_article_cats as $cat => $id)
        selArticleCats[{{ $id }}] = '{{ $cat }}';
    @endforeach

        @foreach($arr_ad_positions as $ad_position => $id)
        selAdPositions[{{ $id }}] = '{{{ $ad_position }}}';

    @endforeach

    /**
     * 创建区域选择的下拉列表
     */
    function buildRegionSelect(selName) {
        var sel = '<select name="' + selName + '">';

        sel += '<option value="">' + selectPlease + '</option>';

        for (i = 0; i < selRegions.length; i++) {
            sel += '<option value="' + selRegions[i] + '">' + selRegions[i] + '</option>';
        }

        sel += '</select>';

        return sel;
    }

    /**
     * 创建选择品牌的下拉列表
     */
    function buildBrandSelect(selName) {
        var sel = '<select name="' + selName + '">';

        sel += '<option value="">' + selectPlease + '</option>';

        for (brand in selBrands) {
            if (brand != "______array" && brand != "toJSONString") {
                sel += '<option value="' + brand + '">' + selBrands[brand] + '</option>';
            }
        }

        sel += '</select>';

        return sel;
    }

    /**
     * 创建选择文章分类的下拉列表
     */
    function buildArticleCatSelect(selName) {
        var sel = '<select name="' + selName + '">';

        sel += '<option value="">' + selectPlease + '</option>';

        for (cat in selArticleCats) {
            if (cat != "______array" && cat != "toJSONString") {
                sel += '<option value="' + cat + '">' + selArticleCats[cat] + '</option>';
            }
        }

        sel += '</select>';

        return sel;
    }

    /**
     * 创建选择广告位置的列表
     */
    function buildAdPositionsSelect(selName) {
        var sel = '<select name="' + selName + '">';

        sel += '<option value="">' + selectPlease + '</option>';

        for (ap in selAdPositions) {
            if (ap != "______array" && ap != "toJSONString") {
                sel += '<option value="' + ap + '">' + selAdPositions[ap] + '</option>';
            }
        }

        sel += '</select>';

        return sel;
    }

    /**
     * 增加一个分类的商品
     */
    function addCatGoods(obj) {
        var rowId = rowindex(obj.parentNode.parentNode);

        var table = obj.parentNode.parentNode.parentNode.parentNode;

        var row = table.insertRow(rowId + 1);
        var cell = row.insertCell(-1);
        cell.innerHTML = '<a href="javascript:;" onclick="removeRow(this)">[-]</a>';
        cell.className = 'first-cell';
        cell.align = 'right';

        cell = row.insertCell(-1);
        cell.innerHTML = buildRegionSelect('regions[cat_goods][]');

        cell = row.insertCell(-1);
        cell.innerHTML = '<input type="text" name="sort_order[cat_goods][]" value="0" size="4" />';

        cell = row.insertCell(-1);
        cell.innerHTML = '<select name="categories[cat_goods][]"><option value="">' + selectPlease + '</option>' + selCategories + '</select>';

        cell = row.insertCell(-1);
        cell.innerHTML = '<input type="text" name="number[cat_goods][]" value="5" size="4" />';

        cell = row.insertCell(-1);
    }

    /**
     * 增加一个品牌的商品
     */
    function addBrandGoods(obj) {
        var rowId = rowindex(obj.parentNode.parentNode);

        var table = obj.parentNode.parentNode.parentNode.parentNode;

        var row = table.insertRow(rowId + 1);
        var cell = row.insertCell(-1);
        cell.innerHTML = '<a href="javascript:;" onclick="removeRow(this)">[-]</a>';
        cell.className = 'first-cell';
        cell.align = 'right';

        cell = row.insertCell(-1);
        cell.innerHTML = buildRegionSelect('regions[brand_goods][]');

        cell = row.insertCell(-1);
        cell.innerHTML = '<input type="text" name="sort_order[brand_goods][]" value="0" size="4" />';

        cell = row.insertCell(-1);
        cell.innerHTML = buildBrandSelect('brands[brand_goods][]');

        cell = row.insertCell(-1);
        cell.innerHTML = '<input type="text" name="number[brand_goods][]" value="5" size="4" />';

        cell = row.insertCell(-1);
    }

    /**
     * 增加一个文章列表
     */
    function addArticles(obj) {
        var rowId = rowindex(obj.parentNode.parentNode);

        var table = obj.parentNode.parentNode.parentNode.parentNode;

        var row = table.insertRow(rowId + 1);
        var cell = row.insertCell(-1);
        cell.innerHTML = '<a href="javascript:;" onclick="removeRow(this)">[-]</a>';
        cell.className = 'first-cell';
        cell.align = 'right';

        cell = row.insertCell(-1);
        cell.innerHTML = buildRegionSelect('regions[cat_articles][]');

        cell = row.insertCell(-1);
        cell.innerHTML = '<input type="text" name="sort_order[cat_articles][]" value="0" size="4" />';

        cell = row.insertCell(-1);
        cell.innerHTML = buildArticleCatSelect('article_cat[cat_articles][]');

        cell = row.insertCell(-1);
        cell.innerHTML = '<input type="text" name="number[cat_articles][]" value="5" size="4" />';

        cell = row.insertCell(-1);
    }

    /**
     * 增加一个广告位
     */
    function addAdPosition(obj) {
        var rowId = rowindex(obj.parentNode.parentNode);

        var table = obj.parentNode.parentNode.parentNode.parentNode;

        var row = table.insertRow(rowId + 1);
        var cell = row.insertCell(-1);
        cell.innerHTML = '<a href="javascript:;" onclick="removeRow(this)">[-]</a>';
        cell.className = 'first-cell';
        cell.align = 'right';

        cell = row.insertCell(-1);
        cell.innerHTML = buildRegionSelect('regions[ad_position][]');

        cell = row.insertCell(-1);
        cell.innerHTML = '<input type="text" name="sort_order[ad_position][]" value="0" size="4" />';

        cell = row.insertCell(-1);
        cell.innerHTML = buildAdPositionsSelect('ad_position[]');

        cell = row.insertCell(-1);
        cell.innerHTML = '<input type="text" name="number[ad_position][]" value="1" size="4" />';

        cell = row.insertCell(-1);
    }

    /**
     * 删除一行
     */
    function removeRow(obj) {
        if (confirm(removeConfirm)) {
            var table = obj.parentNode.parentNode.parentNode;
            var row = obj.parentNode.parentNode;

            for (i = 0; i < table.childNodes.length; i++) {
                if (table.childNodes[i] === row) {
                    table.removeChild(table.childNodes[i]);
                }
            }
        }
    }
</script>
@include('admin::pagefooter')
