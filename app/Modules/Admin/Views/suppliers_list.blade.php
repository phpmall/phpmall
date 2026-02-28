@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <form method="post" action="" name="listForm" onsubmit="return confirm(batch_drop_confirm);">
        <div class="list-div" id="listDiv">
@endif

            <table cellpadding="3" cellspacing="1">
                <tr>
                    <th><input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox"/>
                        <a href="javascript:listTable.sort('suppliers_id'); ">{{ $lang['record_id'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th><a href="javascript:listTable.sort('suppliers_name'); ">{{ $lang['suppliers_name'] }}</a>
                        <img src="{{ asset('static/admin/images/sort_desc.gif') }}">
                    </th>
                    <th>{{ $lang['suppliers_desc'] }}</th>
                    <th>{{ $lang['suppliers_check'] }}</th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @forelse($suppliers_list as $suppliers)
                    <tr>
                        <td><input type="checkbox" name="checkboxes[]" value="{{ $suppliers['suppliers_id'] }}"/>
                            {{ $suppliers['suppliers_id'] }}</td>
                        <td class="first-cell">
                        <span
                            onclick="javascript:listTable.edit(this, 'edit_suppliers_name', {{ $suppliers['suppliers_id'] }})">{{ $suppliers['suppliers_name'] }}
                        </span>
                        </td>
                        <td>{$suppliers.suppliers_desc|nl2br}</td>
                        <td align="center"><img
                                src="{{ asset('static/admin/images/' . ($suppliers['is_check'] === 1 ? 'yes' : 'no') . '.gif') }}"
                                onclick="listTable.toggle(this, 'is_check', {{ $suppliers['suppliers_id'] }})"
                                style="cursor:pointer;"/></td>
                        <td align="center">
                            <a href="suppliers.php?act=edit&id={{ $suppliers['suppliers_id'] }}"
                               title="{{ $lang['edit'] }}">{{ $lang['edit'] }}</a> |
                            <a href="javascript:void(0);"
                               onclick="listTable.remove({{ $suppliers['suppliers_id'] }}, '{{ $lang['drop_confirm'] }}')"
                               title="{{ $lang['remove'] }}">{{ $lang['remove'] }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="no-records" colspan="4">{{ $lang['no_records'] }}</td>
                    </tr>
                @endforelse
            </table>
            <table id="page-table" cellspacing="0">
                <tr>
                    <td>
                        <input name="remove" type="submit" id="btnSubmit" value="{{ $lang['drop'] }}" class="button"
                               disabled="true"/>
                        <input name="act" type="hidden" value="batch"/>
                    </td>
                    <td align="right" nowrap="true">
                        @include('admin::page')
                    </td>
                </tr>
            </table>

@if($full_page)
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
