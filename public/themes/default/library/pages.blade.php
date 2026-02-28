<!--翻页 start-->
<form name="selectPageForm" action="{{ $smarty['server']['PHP_SELF'] }}" method="get">
    @if($pager['styleid'] == 0 )
        <div id="pager">
            {{ $lang['pager_1'] }}{{ $pager['record_count'] }}{{ $lang['pager_2'] }}{{ $lang['pager_3'] }}{{ $pager['page_count'] }}{{ $lang['pager_4'] }}
            <span> <a href="{{ $pager['page_first'] }}">{{ $lang['page_first'] }}</a> <a
                    href="{{ $pager['page_prev'] }}">{{ $lang['page_prev'] }}</a> <a
                    href="{{ $pager['page_next'] }}">{{ $lang['page_next'] }}</a> <a
                    href="{{ $pager['page_last'] }}">{{ $lang['page_last'] }}</a> </span>
            @foreach($pager['search'] as $key => $item)
                @if($key == 'keywords')
                    <input type="hidden" name="{{ $key }}" value="{{{ $item }}}"/>
                @else
                    <input type="hidden" name="{{ $key }}" value="{{ $item }}"/>
                @endif
            @endforeach
            <select name="page" id="page" onchange="selectPage(this)">
                {html_options options=$pager.array selected=$pager.page}
            </select>
        </div>
    @else

        <!--翻页 start-->
        <div id="pager" class="pagebar">
            <span class="f_l f6" style="margin-right:10px;">{{ $lang['pager_1'] }}<b>{{ $pager['record_count'] }}</b> {{ $lang['pager_2'] }}</span>
            @if($pager['page_first'])
                <a href="{{ $pager['page_first'] }}">{{ $lang['page_first'] }} ...</a>
            @endif
            @if($pager['page_prev'])
                <a class="prev" href="{{ $pager['page_prev'] }}">{{ $lang['page_prev'] }}</a>
            @endif
            @if($pager['page_count'] != 1)
                @foreach($pager['page_number'] as $key => $item)
                    @if($pager['page'] == $key)
                        <span class="page_now">{{ $key }}</span>
                    @else
                        <a href="{{ $item }}">[{{ $key }}]</a>
                    @endif
                @endforeach
            @endif

            @if($pager['page_next'])
                <a class="next" href="{{ $pager['page_next'] }}">{{ $lang['page_next'] }}</a>
            @endif
            @if($pager['page_last'])
                <a class="last" href="{{ $pager['page_last'] }}">...{{ $lang['page_last'] }}</a>
            @endif
            @if($pager['page_kbd'])
                @foreach($pager['search'] as $key => $item)
                    @if($key == 'keywords')
                        <input type="hidden" name="{{ $key }}" value="{{{ $item }}}"/>
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $item }}"/>
                    @endif
                @endforeach
                <kbd style="float:left; margin-left:8px; position:relative; bottom:3px;"><input type="text" name="page"
                                                                                                onkeydown="if(event.keyCode==13)selectPage(this)"
                                                                                                size="3"
                                                                                                class="B_blue"/></kbd>
            @endif
        </div>
        <!--翻页 END-->

    @endif
</form>
<script type="text/javascript">
    <!--

function selectPage(sel) {
    sel.form.submit();
}


</script>
