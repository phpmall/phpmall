<div class="form-div">
    <form action="javascript:searchGoods()" name="searchForm">
        <img src="{{ asset('static/admin/images/icon_search.gif') }}" width="26" height="22" border="0" alt="SEARCH"/>
        @if($smarty['get']['act'] != "trash")
            <!-- 分类 -->
            <select name="cat_id">
                <option value="0">{{ $lang['goods_cat'] }}</option>{{ $cat_list }}
            </select>
            <!-- 品牌 -->
            <select name="brand_id">
                <option value="0">{{ $lang['goods_brand'] }}</option>
                {html_options options=$brand_list}
            </select>
            <!-- 推荐 -->
            <select name="intro_type">
                <option value="0">{{ $lang['intro_type'] }}</option>
                {html_options options=$intro_list
                selected=$smarty.get.intro_type}
            </select>
            @if($suppliers_exists === 1)
                <!-- 供货商 -->
                <select name="suppliers_id">
                    <option value="0">{{ $lang['intro_type'] }}</option>
                    {html_options options=$suppliers_list_name
                    selected=$smarty.get.suppliers_id}
                </select>
            @endif
            <!-- 上架 -->
            <select name="is_on_sale">
                <option value=''>{{ $lang['intro_type'] }}</option>
                <option value="1">{{ $lang['on_sale'] }}</option>
                <option value="0">{{ $lang['not_on_sale'] }}</option>
            </select>
        @endif
        <!-- 关键字 -->
        {{ $lang['keyword'] }} <input type="text" name="keyword" size="15"/>
        <input type="submit" value="{{ $lang['button_search'] }}" class="button"/>
    </form>
</div>
<script type="text/javascript">
    function searchGoods() {
        @if($smarty['get']['act'] != "trash")
            listTable.filter['cat_id'] = document.forms['searchForm'].elements['cat_id'].value;
        listTable.filter['brand_id'] = document.forms['searchForm'].elements['brand_id'].value;
        listTable.filter['intro_type'] = document.forms['searchForm'].elements['intro_type'].value;
        @if($suppliers_exists === 1)
            listTable.filter['suppliers_id'] = document.forms['searchForm'].elements['suppliers_id'].value;
        @endif
            listTable.filter['is_on_sale'] = document.forms['searchForm'].elements['is_on_sale'].value;
        @endif

            listTable.filter['keyword'] = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
        listTable.filter['page'] = 1;

        listTable.loadList();
    }
</script>
