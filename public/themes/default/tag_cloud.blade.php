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
<div class="block">
    <div class="box">
        <div class="box_1">
            <h3><span>{{ $lang['all_tags'] }}</span></h3>
            <div class="boxCenterList RelaArticle">
                <p class="f_red" style="text-decoration:none;">&nbsp;&nbsp; {{ $lang['tag_cloud_desc'] }}
                    &nbsp;&nbsp;</p>
                @if($tags)
                    <!-- 标签云开始 @foreach($tags as $tag)-->
                    <span style="font-size:{{ $tag['size'] }}; line-height:36px;"> <a href="{{ $tag['url'] }}"
                                                                                      style="color:{{ $tag['color'] }}">
                @if($tag['bold'])
                                <b>{{ $tag['tag_words'] }}</b>
                            @else
                                {{ $tag['tag_words'] }}
                            @endif
              </a>
              @if($tags_from == 'user')
                            <a href="user.php?act=act_del_tag&amp;tag_words={{ urlencode($tag['tag_words']) }}&amp;uid={{ $tag['user_id'] }}"
                               title="{{ $lang['drop'] }}"> <img src="images/drop.gif" alt="{{ $lang['drop'] }}"/> </a>
                            &nbsp;&nbsp;
                        @endif
            </span>
                    <!-- 标签云结束 @endforeach-->
                    @else
                        <span style="margin:2px 10px; font-size:14px; line-height:36px;">{{ $lang['no_tag'] }}</span>
                    @endif
            </div>
        </div>
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
