@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <!-- 品牌搜索 -->
    @include('brand_search')
    <form method="post" action="" name="listForm">
        <!-- start brand list -->
        <div class="list-div" id="listDiv">
            @endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th>{{ $lang['brand_name'] }}</th>
                    <th>{{ $lang['site_url'] }}</th>
                    <th>{{ $lang['brand_desc'] }}</th>
                    <th>{{ $lang['sort_order'] }}</th>
                    <th>{{ $lang['is_show'] }}</th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($brand_list as $brand)
                    <tr>
                        <td class="first-cell">
                            <span style="float:right">{{ $brand['brand_logo'] }}</span>
                            <span
                                onclick="javascript:listTable.edit(this, 'edit_brand_name', {{ $brand['brand_id'] }})">{{ $brand['brand_name'] }}</span>
                        </td>
                        <td>{{ $brand['site_url'] }}</td>
                        <td align="left">{{ \Illuminate\Support\Str::limit($brand['brand_desc'], 36, '...') }}</td>
                        <td align="right"><span
                                onclick="javascript:listTable.edit(this, 'edit_sort_order', {{ $brand['brand_id'] }})">{{ $brand['sort_order'] }}</span>
                        </td>
                        <td align="center"><img
                                src="{{ asset('static/admin/images/'.($brand['is_show'] ? 'yes' : 'no').'.gif') }}"
                                onclick="listTable.toggle(this, 'toggle_show', {{ $brand['brand_id'] }})"/></td>
                        <td align="center">
                            <a href="brand.php?act=edit&id={{ $brand['brand_id'] }}"
                               title="{{ $lang['edit'] }}">{{ $lang['edit'] }}</a> |
                            <a href="javascript:;"
                               onclick="listTable.remove({{ $brand['brand_id'] }}, '{{ $lang['drop_confirm'] }}')"
                               title="{{ $lang['edit'] }}">{{ $lang['remove'] }}</a>
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
                <!-- end brand list -->
        </div>
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
