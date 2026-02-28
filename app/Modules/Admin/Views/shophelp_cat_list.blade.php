@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <!-- start form -->
    <div class="form-div">
        <form method="post" action="javascript:newArticleCat()" name="theForm">
            {{ $lang['cat_add'] }} <input type="text" name="cat_name" maxlength="150" size="40"/>
            <input type="submit" value="{{ $lang['button_submit'] }}" class="button"/>
        </form>
    </div>
    <!-- end form -->

    <!-- start article list -->
    <div class="list-div" id="listDiv">
        @endif

        <table cellspacing='1' cellpadding='3' id='listTable'>
            <tr>
                <th>{{ $lang['cat_name'] }}</th>
                <th>{{ $lang['num'] }}</th>
                <th>{{ $lang['sort'] }}</th>
                <th>{{ $lang['handler'] }}</th>
            </tr>
            @foreach($list as $item)
                <tr>
                    <td class="first-cell"><span
                            onclick="javascript:listTable.edit(this,'edit_catname', {{ $item['cat_id'] }})">{{ $item['cat_name'] }}</span>
                    </td>
                    <td align="right">{{ $item['num'] }}{{ $lang['js_languages']['chap'] }}</td>
                    <td align="right"><span
                            onclick="javascript:listTable.edit(this,'edit_cat_order', {{ $item['cat_id'] }})">{{ $item['sort_order'] }}</span>
                    </td>
                    <td align="center">
                        <a href="shophelp.php?act=add&cat_id={{ $item['cat_id'] }}"
                           title="{{ $lang['article_add'] }}"><img
                                src="{{ asset('static/admin/images/icon_add.gif') }}" border="0" height="16"
                                width="16"></a>&nbsp;&nbsp;<a
                            href="shophelp.php?act=list_article&cat_id={{ $item['cat_id'] }}"
                            title="{{ $lang['article_list'] }}"><img
                                src="{{ asset('static/admin/images/icon_docs.gif') }}"
                                border="0" height="16" width="16"></a>&nbsp;&nbsp;
                        <a href="javascript:;"
                           onclick="listTable.remove({{ $item['cat_id'] }}, '{{ $lang['drop_confirm'] }}')"
                           title="{{ $lang['remove'] }}"><img src="{{ asset('static/admin/images/icon_drop.gif') }}"
                                                              border="0"
                                                              height="16" width="16"></a>
                    </td>
                </tr>
            @endforeach
        </table>

@if($full_page)
    </div>
    <!-- end article list -->

    <script type="text/javascript">
        @foreach($filter as $item => $key)
            listTable.filter.{{ $key }} = '{{ $item }}';
        @endforeach


        function newArticleCat() {
            var cat_name = Utils.trim(document.forms['theForm'].elements['cat_name'].value);

            if (Utils.trim(cat_name).length > 0) {
                Ajax.call('shophelp.php?is_ajax=1&act=add_catname', 'cat_name=' + cat_name, newGoodsTypeResponse, "POST", "JSON");
            }
        }

        function newGoodsTypeResponse(result) {
            if (result.error === 0) {
                document.getElementById('listDiv').innerHTML = result.content;
                document.forms['theForm'].elements['cat_name'].value = '';
                document.forms['theForm'].elements['cat_name'].focus();
            }

            if (result.message.length > 0) {
                alert(result.message);
            }
        }
    </script>
    @include('admin::pagefooter')
@endif
