@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>

    <form method="post" action="" name="listForm">
        <!-- start reg_fiedls list -->
        <div class="list-div" id="listDiv">
            @endif

            <table cellspacing='1' id="list-table">
                <tr>
                    <th>{{ $lang['field_name'] }}</th>
                    <th>{{ $lang['field_order'] }}</th>
                    <th>{{ $lang['field_display'] }}</th>
                    <th>{{ $lang['field_need'] }}</th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>
                @foreach($reg_fields as $field)
                    <tr>
                        <td class="first-cell"><span
                                onclick="listTable.edit(this,'edit_name', {{ $field['id'] }})">{{ $field['reg_field_name'] }}</span>
                        </td>
                        <td align="center"><span
                                onclick="listTable.edit(this,'edit_order', {{ $field['id'] }})">{{ $field['dis_order'] }}</span>
                        </td>
                        <td align="center"><img
                                src="{{ asset('static/admin/images/' . ($field['display'] ? 'yes' : 'no') . '.gif') }}"
                                onclick="listTable.toggle(this, 'toggle_dis', {{ $field['id'] }})"/></td>
                        <td align="center"><img src="{{ asset('static/admin/images/
                ' . ($field['is_need'] ? 'yes' : 'no') . '.gif') }}"
                                                onclick="listTable.toggle(this, 'toggle_need', {{ $field['id'] }})"/>
                        </td>
                        <td align="right"><a
                                href="?act=edit&id={{ $field['id'] }}">{{ $lang['edit'] }}</a>@if($field['type'] === 0)
                                | <a href="javascript:;"
                                     onclick="listTable.remove({{ $field['id'] }}, '{{ $lang['drop_confirm'] }}')"
                                     title="{{ $lang['remove'] }}">{{ $lang['remove'] }}</a>
                            @endif</td>
                    </tr>
                @endforeach
            </table>

@if($full_page)
        </div>
        <!-- end reg_fiedls list -->
    </form>
    <script type="text/javascript">
    </script>
    @include('admin::pagefooter')
@endif
