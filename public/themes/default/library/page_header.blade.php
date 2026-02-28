<script type="text/javascript">
    var process_request = "{{ $lang['process_request'] }}";
</script>
<div class="block clearfix">
    <div class="f_l"><a href="../index.php" name="top"><img src="../images/logo.gif"/></a></div>
    <div class="f_r log">
        <ul>
            <li class="userInfo">
                <script type="text/javascript" src="{{ asset('js/transport.js') }}"></script>
                <script type="text/javascript" src="{{ asset('js/utils.js') }}"></script>
                <font id="ECS_MEMBERZONE">{* 提醒您：根据用户id来调用member_info.lbi显示不同的界面 *}{insert
                    name='member_info'} </font>
            </li>
            @if($navigator_list['top'])
                <li id="topNav" class="clearfix">
                    @foreach($navigator_list['top'] as $nav)
                        <a href="{{ $nav['url'] }}"
                           @if($nav['opennew'] == 1) target="_blank" @endif>{{ $nav['name'] }}</a>
                        @if(!$loop->nav_top_list.last)
                            |
                        @endif
                    @endforeach
                    <div class="topNavR"></div>
                </li>
            @endif
        </ul>
    </div>
</div>
<div class="blank"></div>
<div id="mainNav" class="clearfix">
    <a href="../index.php" @if($navigator_list['config']['index'] == 1) class="cur"@endif>{{ $lang['home'] }}
        <span></span></a>
    @foreach($navigator_list['middle'] as $nav)
        <a href="{{ $nav['url'] }}" @if($nav['opennew'] == 1)target="_blank"
           @endif @if($nav['active'] == 1) class="cur"@endif>{{ $nav['name'] }}<span></span></a>
    @endforeach
</div>
<!--search start-->
<div id="search" class="clearfix">
    <div class="keys f_l">
        <script type="text/javascript">

            <!--
            function checkSearchForm() {
                if (document.getElementById('keyword').value) {
                    return true;
                } else {
                    alert("{{ $lang['no_keywords'] }}");
                    return false;
                }
            }

            -->

        </script>
        @if($searchkeywords)
            {{ $lang['hot_search'] }} ：
            @foreach($searchkeywords as $val)
                <a href="search.php?keywords={{ urlencode($val) }}">{{ $val }}</a>
            @endforeach
        @endif
    </div>
    <form id="searchForm" name="searchForm" method="get" action="search.php" onSubmit="return checkSearchForm()"
          class="f_r" style="_position:relative; top:5px;">
        <select name="category" id="category" class="B_input">
            <option value="0">{{ $lang['all_category'] }}</option>
            {{ $category_list }}
        </select>
        <input name="keywords" type="text" id="keyword" value="{{{ $search_keywords }}}" class="B_input"
               style="width:110px;"/>
        <input name="imageField" type="submit" value="" class="go" style="cursor:pointer;"/>
        <a href="search.php?act=advanced_search">{{ $lang['advanced_search'] }}</a>
    </form>
</div>
<!--search end-->
