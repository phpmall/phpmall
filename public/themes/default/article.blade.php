<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="Keywords" content="{{ $keywords }}"/>
    <meta name="Description" content="{{ $description }}"/>
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
<div class="block clearfix">
    <!--left start-->
    <div class="AreaL">
        @include('web::library/cart')
        @include('web::library/category_tree')
        @include('web::library/article_category_tree')
        @include('web::library/filter_attr')
        @include('web::library/price_grade')
        <!-- TemplateBeginEditable name="左边区域" -->
        @include('web::library/goods_related')
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
                <div style="border:4px solid #fcf8f7; background-color:#fff; padding:20px 15px;">
                    <div class="tc" style="padding:8px;">
                        <font class="f5 f6">{{ $article['title'] }}</font><br/>
                        <font class="f3">{{ $article['author'] }} / {{ $article['add_time'] }}</font>
                    </div>
                    @if($article['content'])
                        {{ $article['content'] }}
                    @endif
                    @if($article['open_type'] == 2 || $article['open_type'] == 1)
                        <br/>
                        <div><a href="{{ $article['file_url'] }}" target="_blank">{{ $lang['relative_file'] }}</a></div>
                    @endif
                    <div style="padding:8px; margin-top:15px; text-align:left; border-top:1px solid #ccc;">
                        <!-- 上一篇文章 -->
                        @if($next_article)
                            {{ $lang['next_article'] }}:<a href="{{ $next_article['url'] }}"
                                                           class="f6">{{ $next_article['title'] }}</a><br/>
                        @endif
                        <!-- 下一篇文章 -->
                        @if($prev_article)
                            {{ $lang['prev_article'] }}:<a href="{{ $prev_article['url'] }}"
                                                           class="f6">{{ $prev_article['title'] }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="blank"></div>
        @include('web::library/comments')
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

</html>
