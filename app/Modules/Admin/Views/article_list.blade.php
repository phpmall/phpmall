@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <div class="form-div">
        <form action="javascript:searchArticle()" name="searchForm">
            <img src="{{ asset('static/admin/images/icon_search.gif') }}" width="26" height="22" border="0"
                 alt="SEARCH"/>
            <select name="cat_id">
                <option value="0">{{ $lang['all_cat'] }}</option>
                {{ $cat_select }}
            </select>
            {{ $lang['title'] }} <input type="text" name="keyword" id="keyword"/>
            <input type="submit" value="{{ $lang['button_search'] }}" class="button"/>
        </form>
    </div>

    <form method="POST" action="article.php?act=batch_remove" name="listForm">
        <!-- start cat list -->
        <div class="list-div" id="listDiv">
            @endif

            <table cellspacing='1' cellpadding='3' id='list-table'>
                <tr>
                    <th><input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox">
                        <a href="javascript:listTable.sort('article_id'); ">{{ $lang['article_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('title'); ">{{ $lang['title'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('cat_id'); ">{{ $lang['cat'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('article_type'); ">{{ $lang['article_type'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('is_open'); ">{{ $lang['is_open'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('add_time'); ">{{ $lang['add_time'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($article_list as $list)
                    <tr>
                        <td><span><input name="checkboxes[]" type="checkbox" value="{{ $list['article_id'] }}"
                                         @if($list['cat_id'] <= 0 )disabled="true"@endif/>{{ $list['article_id'] }}</span>
                        </td>
                        <td class="first-cell">
                            <span
                                onclick="javascript:listTable.edit(this, 'edit_title', {{ $list['article_id'] }})">{{ $list['title'] }}</span>
                        </td>
                        <td align="left"><span>'.($list['cat_id'] > 0 ? '{{ $list['cat_name'] }}' : '{{ $lang['reserve'] }}').'</span>
                        </td>
                        <td align="center"><span>'.($list['article_type'] === 0 ? '{{ $lang['common'] }}' : '{{ $lang['top'] }}').'</span>
                        </td>
                        <td align="center">@if($list['cat_id'] > 0)
                                <span>
    <img src="{{ asset('static/admin/images/'.($list['is_open'] === 1 ? 'yes' : 'no').'.gif') }}"
         onclick="listTable.toggle(this, 'toggle_show', {{ $list['article_id'] }})"/></span>
                            @else
                                <img src="{{ asset('static/admin/images/yes.gif') }}" alt="yes"/>
                            @endif</td>
                        <td align="center"><span>{{ $list['date'] }}</span></td>
                        <td align="center" nowrap="true"><span>
      <a href="../article.php?id={{ $list['article_id'] }}" target="_blank" title="{{ $lang['view'] }}"><img
              src="{{ asset('static/admin/images/icon_view.gif') }}" border="0" height="16" width="16"/></a>&nbsp;
      <a href="article.php?act=edit&id={{ $list['article_id'] }}" title="{{ $lang['edit'] }}"><img
              src="{{ asset('static/admin/images/icon_edit.gif') }}" border="0" height="16" width="16"/></a>&nbsp;
     @if($list['cat_id'] > 0)
                                    <a href="javascript:;"
                                       onclick="listTable.remove({{ $list['article_id'] }}, '{{ $lang['drop_confirm'] }}')"
                                       title="{{ $lang['remove'] }}"><img
                                            src="{{ asset('static/admin/images/icon_drop.gif') }}" border="0"
                                            height="16" width="16"></a>
                                @endif</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="10">{{ $lang['no_article'] }}</td>
                    </tr>
                @endforelse
                <tr>&nbsp;
                    <td align="right" nowrap="true" colspan="8">@include('admin::page')</td>
                </tr>
            </table>

@if($full_page)
        </div>

        <div>
            <input type="hidden" name="act" value="batch"/>
            <select name="type" id="selAction" onchange="changeAction()">
                <option value="">{{ $lang['select_please'] }}</option>
                <option value="button_remove">{{ $lang['button_remove'] }}</option>
                <option value="button_hide">{{ $lang['button_hide'] }}</option>
                <option value="button_show">{{ $lang['button_show'] }}</option>
                <option value="move_to">{{ $lang['move_to'] }}</option>
            </select>
            <select name="target_cat" style="display:none">
                <option value="0">{{ $lang['select_please'] }}</option>
                {{ $cat_select }}
            </select>

            <input type="submit" value="{{ $lang['button_submit'] }}" id="btnSubmit" name="btnSubmit" class="button"
                   disabled="true"/>
        </div>
    </form>
    <!-- end cat list -->
    <script type="text/javascript">
        listTable.recordCount = {{ $record_count }};
        listTable.pageCount = {{ $page_count }};

        @foreach($filter as $item => $key)
            listTable.filter.{{ $key }} = '{{ $item }}';

        @endforeach


        /**
         * @param: bool ext 其他条件：用于转移分类
         */
        function confirmSubmit(frm, ext) {
            if (frm.elements['type'].value === 'button_remove') {
                return confirm(drop_confirm);
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

            if (!document.getElementById('btnSubmit').disabled &&
                confirmSubmit(frm, false)) {
                frm.submit();
            }
        }

        // 搜索文章
        function searchArticle() {
            listTable.filter.keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
            listTable.filter.cat_id = parseInt(document.forms['searchForm'].elements['cat_id'].value);
            listTable.filter.page = 1;
            listTable.loadList();
        }
    </script>
    @include('admin::pagefooter')
@endif
