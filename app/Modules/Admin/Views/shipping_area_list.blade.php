@if($full_page)
    @include('admin::pageheader')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('static/admin/js/listtable.js') }}"></script>
    <!-- start shipping area list -->
    <form method="post" action="shipping_area.php" name="listForm"
          onsubmit="return confirm('{{ $lang['remove_confirm'] }}')">
        <div class="list-div" id="listDiv">
            @endif

            <table cellspacing='1' cellpadding='3' id='listTable'>
                <tr>
                    <th><input type="checkbox" onclick="listTable.selectAll(this, 'areas')"/>{{ $lang['record_id'] }}
                    </th>
                    <th>{{ $lang['shipping_area_name'] }}</th>
                    <th>{{ $lang['shipping_area_regions'] }}</th>
                    <th>{{ $lang['handler'] }}</th>
                </tr>

                @foreach($areas as $area)
                    <tr>
                        <td>
                            <input type="checkbox" name="areas[]"
                                   value="{{ $area['shipping_area_id'] }}"/>{{ $area['shipping_area_id'] }}
                        </td>
                        <td class="first-cell">
            <span
                onclick="listTable.edit(this, 'edit_area', '{{ $area['shipping_area_id'] }}'); return false;">{$area.shipping_area_name|escape:"html"}</a>
                        </td>
                        <td>{{ $area['shipping_area_regions'] }}</td>
                        <td align="center">
                            <a href="shipping_area.php?act=edit&id={{ $area['shipping_area_id'] }}">{{ $lang['edit'] }}</a>
                            | <a
                                href="javascript:;"
                                onclick="listTable.remove({{ $area['shipping_area_id'] }}, '{{ $lang['remove_confirm'] }}', 'remove_area')">{{ $lang['remove'] }}</a>
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="4" align="center">
                        <input type="hidden" name="act" value="multi_remove"/>
                        <input type="hidden" name="shipping" value="{{ $smarty['get']['shipping'] }}"/>
                        <input type="submit" value="{{ $lang['delete_selected'] }}" disabled="true" id="btnSubmit"
                               class="button"/>
                    </td>
                </tr>
            </table>

@if($full_page)
        </div>
    </form>
    <!-- end shipping area list -->

    <script type="text/javascript">
    </script>

    @include('admin::pagefooter')
@endif
