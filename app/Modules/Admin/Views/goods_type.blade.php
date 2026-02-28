@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <form method="post" action="" name="listForm">
        <!-- start goods type list -->
        <div class="list-div" id="listDiv">
            @endif

            <table width="100%" cellpadding="3" cellspacing="1" id="listTable">
                <tr>
                    <th>{{ $lang['goods_type_name'] }}</th>
                    <th>{{ $lang['attr_groups'] }}</th>
                    <th>{{ $lang['attribute_number'] }}</th>
                    <th>{{ $lang['goods_type_status'] }}</th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($goods_type_arr as $goods_type)
                    <tr>
                        <td class="first-cell"><span
                                onclick="javascript:listTable.edit(this, 'edit_type_name', {{ $goods_type['cat_id'] }})">{{ $goods_type['cat_name'] }}</span>
                        </td>
                        <td>{{ $goods_type['attr_group'] }}</td>
                        <td align="right">{{ $goods_type['attr_count'] }}</td>
                        <td align="center"><img
                                src="{{ asset('static/admin/images/'.($goods_type['enabled'] ? 'yes' : 'no').'.gif') }}">
                        </td>
                        <td align="center">
                            <a href="attribute.php?act=list&goods_type={{ $goods_type['cat_id'] }}"
                               title="{{ $lang['attribute'] }}">{{ $lang['attribute'] }}</a> |
                            <a href="goods_type.php?act=edit&cat_id={{ $goods_type['cat_id'] }}"
                               title="{{ $lang['edit'] }}">{{ $lang['edit'] }}</a> |
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $goods_type['cat_id'] }}, '{{ $lang['remove_confirm'] }}')"
                               title="{{ $lang['remove'] }}">{{ $lang['remove'] }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="10">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse
                <tr>
                    <td align="right" nowrap="true" colspan="6">
                        @include('admin::page')
                    </td>
                </tr>
            </table>

@if($full_page)
        </div>
        <!-- end goods type list -->
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
