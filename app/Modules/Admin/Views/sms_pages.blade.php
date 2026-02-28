<div id="turn-page">
    {{ $lang['total_records'] }} <span id="totalRecords">{{ $turn_page['total_records'] }}</span>
    {{ $lang['total_pages'] }} <span id="totalPages">{{ $turn_page['total_pages'] }}</span>
    {{ $lang['page_current'] }} <span id="pageCurrent">{{ $turn_page['page'] }}</span>
    {{ $lang['page_size'] }} <input type='text' size='3' name="page_size" id='pageSize'
                                    value="{{ $turn_page['page_size'] }}"/>
    <span id="page-link">
        <a href="javascript:gotoPageFirst()">{{ $lang['page_first'] }}</a>
        <a href="javascript:gotoPagePrev()">{{ $lang['page_prev'] }}</a>
        <a href="javascript:gotoPageNext()">{{ $lang['page_next'] }}</a>
        <a href="javascript:gotoPageLast()">{{ $lang['page_last'] }}</a>
        <select id="gotoPage" onchange="gotoPage(this.value)"></select>
        <input type="hidden" value="{{ $turn_page['page'] }}" name="page">
    </span>
</div>
<script src="{{ asset('js/utils.js') }}"></script>
<script type="text/javascript">
    var total_pages = {{ $turn_page['total_pages'] }};
    var page = {{ $turn_page['page'] }};
    var page_size = {{ $turn_page['page_size'] }};
    onload = function () {
        var lst = document.getElementById('gotoPage');

        for (i = 1; i <= total_pages; i++) {
            var opt = new Option(i, i);
            lst.options.add(opt);

            if (i === page) {
                opt.selected = true;
            }
        }
    }

    document.getElementById("pageSize").onkeypress = function (e) {
        var evt = Utils.fixEvent(e);
        if (evt.keyCode === 13) {
            document.forms['listForm'].submit();
            return false;
        }
        ;
    }

    /**
     * 前往第一页
     */
    function gotoPageFirst() {
        document.forms['listForm'].elements['page'].value = 1;
        document.forms['listForm'].submit();
    }

    /**
     * 跳转到下一页
     */
    function gotoPageNext() {
        if (page < total_pages) {
            document.forms['listForm'].elements['page'].value = page + 1;
            document.forms['listForm'].submit();
        }
    }

    /**
     * 跳转到上一页
     */
    function gotoPagePrev() {
        if (page > 1) {
            document.forms['listForm'].elements['page'].value = page - 1;
            document.forms['listForm'].submit();
        }
    }

    /**
     * 跳转到最后一页
     */
    function gotoPageLast() {
        if (page < total_pages) {
            document.forms['listForm'].elements['page'].value = total_pages;
            document.forms['listForm'].submit();
        }
    }
</script>
