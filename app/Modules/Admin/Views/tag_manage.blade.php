@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <form method="POST" action="tag_manage.php?act=batch_drop" name="listForm">
        <!-- start tag list -->
        <div class="list-div" id="listDiv">
@endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>
                        <input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox">
                        <a href="javascript:listTable.sort('tag_id'); ">{{ $lang['record_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('tag_words'); ">{{ $lang['tag_words'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('user_name'); ">{{ $lang['user_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('goods_name'); ">{{ $lang['goods_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($tag_list as $tag)
                    <tr>
                        <td><span><input name="checkboxes[]" type="checkbox" value="{{ $tag['tag_id'] }}">{{ $tag['tag_id'] }}</span>
                        </td>
                        <td class="first-cell"><span
                                onclick="javascript:listTable.edit(this, 'edit_tag_name', {{ $tag['tag_id'] }})">{{ $tag['tag_words'] }}</span>
                        </td>
                        <td align="left"><span>{{ $tag['user_name'] }}</span></td>
                        <td align="left"><span><a href="../goods.php?id={{ $tag['goods_id'] }}"
                                                  target="_blank">{{ $tag['goods_name'] }}</a></span></td>
                        <td align="center">
            <span>
              <a href="tag_manage.php?act=edit&amp;id={{ $tag['tag_id'] }}" title="{{ $lang['edit'] }}"><img
                      src="{{ asset('static/admin/images/icon_edit.gif') }}" border="0" height="16" width="16"/></a>
              <a href="javascript:;" onclick="listTable.remove({{ $tag['tag_id'] }}, '{{ $lang['drop_confirm'] }}')"
                 title="{{ $lang['remove'] }}"><img src="{{ asset('static/admin/images/icon_drop.gif') }}" border="0"
                                                    height="16"
                                                    width="16"></a></span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="10">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse
                <tr>
                    <td colspan="2"><input type="submit" class="button" id="btnSubmit" value="{{ $lang['drop_tags'] }}"
                                           disabled="true"/></td>
                    <td align="right" nowrap="true" colspan="3">@include('admin::page')</td>
                </tr>
            </table>

@if($full_page)
        </div>
        <!-- end tag list -->
    </form>

    <script type="text/javascript">
        listTable.recordCount = {{ $record_count }};
        listTable.pageCount = {{ $page_count }};

        @foreach($filter as $item => $key)
            listTable.filter.{{ $key }} = '{{ $item }}';
        @endforeach
    </script>
    @include('admin::pagefooter')
@endif
