<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="Keywords" content="{{ $keywords }}"/>
    <meta name="Description" content="{{ $description }}"/>
    <meta name="Description" content="{{ $description }}"/>
    @if($auto_redirect)
        <meta http-equiv="refresh" content="3;URL={{ $message['href'] }}"/>
    @endif
    <title>{{ $page_title }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <link href="{{ $ecs_css_path }}" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
</head>

<body>
@include('web::library/page_header')
<!--当前位置 start-->
<div class="block box">
    <div id="ur_here">
        @include('web::library/ur_here')
    </div>
</div>
<!--当前位置 end-->
<div class="blank"></div>
<div class="block">
    <div class="flowBox">
        <h6><span>{{ $lang['print_quotation'] }}</span></h6>
        <form action="quotation.php" method="post" name="searchForm" target="_blank" class="quotation">
            <!-- 分类 -->
            <select name="cat_id">
                <option value="0">{{ $lang['all_category'] }}</option>{{ $cat_list }}
            </select>
            <!-- 品牌 -->
            <select name="brand_id">
                <option value="0">{{ $lang['all_brand'] }}</option>
                {html_options options=$brand_list}
            </select>
            <!-- 关键字 -->
            {{ $lang['keywords'] }} <input type="text" name="keyword" class="inputBg"/>
            <!-- 搜索 -->
            <input name="act" type="hidden" value="print_quotation"/>
            <input type="submit" name="print_quotation" id="print_quotation" value="{{ $lang['print_quotation'] }}"
                   style="vertical-align:middle;" class="bnt_blue_1"/>
        </form>
    </div>

</div>
<div class="blank5"></div>
<!--帮助-->
<div class="block">
    <div class="box">
        <div class="helpTitBg clearfix">
            @include('web::library/help')
        </div>
    </div>
</div>
<div class="blank"></div>
<!--帮助-->
@include('web::library/page_footer')
</body>

</html>
