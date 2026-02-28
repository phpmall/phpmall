<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="Keywords" content="{{ $keywords }}"/>
    <meta name="Description" content="{{ $description }}"/>
    <!-- TemplateBeginEditable name="doctitle" -->
    <title>{{ $page_title }}</title>
    <!-- TemplateEndEditable -->
    <!-- TemplateBeginEditable name="head" -->
    <!-- TemplateEndEditable -->
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
<div class="block clearfix">
    <!--left start-->
    <div class="AreaL">
        <!-- TemplateBeginEditable name="左边区域" -->
        @include('web::library/cart')
        @include('web::library/category_tree')
        @if($article_categories)
            @include('web::library/article_category_tree')
        @endif
        @include('web::library/filter_attr')
        @include('web::library/price_grade')
        <!-- TemplateEndEditable -->
        <!-- TemplateBeginEditable name="左边广告区域（宽200px）" -->
        <!-- TemplateEndEditable -->
        <!--AD end-->
        @include('web::library/history')
    </div>
    <!--left end-->
    <!--right start-->
    <div class="AreaR">
        <div class="box">
            <div class="box_1">
                <h3><span>{{ $lang['article_list'] }}</span></h3>
                <div class="boxCenterList">
                    <form action="{{ $search_url }}" name="search_form" method="post" class="article_search">
                        <input name="keywords" type="text" id="requirement" value="{{ $search_value }}"
                               class="inputBg"/>
                        <input name="id" type="hidden" value="{{ $cat_id }}"/>
                        <input name="cur_url" id="cur_url" type="hidden" value=""/>
                        <input type="submit" value="{{ $lang['button_search'] }}" class="bnt_blue_1"/>
                    </form>
                    <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#dddddd">
                        <tr>
                            <th bgcolor="#ffffff">{{ $lang['article_title'] }}</th>
                            <th bgcolor="#ffffff">{{ $lang['article_author'] }}</th>
                            <th bgcolor="#ffffff">{{ $lang['article_add_time'] }}</th>
                        </tr>
                        @foreach($artciles_list as $article)
                            <tr>
                                <td bgcolor="#ffffff"><a href="{{ $article['url'] }}" title="{{ $article['title'] }}"
                                                         class="f6">{{ $article['short_title'] }}</a></td>
                                <td bgcolor="#ffffff">{{ $article['author'] }}</td>
                                <td bgcolor="#ffffff" align="center">{{ $article['add_time'] }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="blank5"></div>
        @include('web::library/pages')
    </div>
    <!--right end-->
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
<script type="text/javascript">
    document.getElementById('cur_url').value = window.location.href;
</script>

</html>
