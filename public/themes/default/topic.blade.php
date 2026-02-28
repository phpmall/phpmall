{* TitlePicWidth: 2 *}
{* TitlePicHeight: 38 *}

{* 说明：$title_pic，分类标题图片地址； *}
{* 说明：$base_style，基本风格样式颜色； *}
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="Keywords" content="{{ $keywords }}"/>
    <meta name="Description" content="{{ $description }}"/>
    <!-- TemplateBeginEditable name="doctitle" -->
    <title>{{ $topic['title'] }}_{{ $page_title }}</title>
    <!-- TemplateEndEditable -->
    <!-- TemplateBeginEditable name="head" -->
    <!-- TemplateEndEditable -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
    <link href="{{ $ecs_css_path }}" rel="stylesheet" type="text/css"/>
    @if($topic['css'] != '')
        <style type="text/css">
            {{ $topic['css'] }}
        </style>
    @endif
    <style type="text/css">
        h6 {
            font-family: "黑体";
            background: url({{ $title_pic }}) repeat-x 0 0;
            text-align: left;
            height: 38px;
            line-height: 38px;
            padding-left: 20px;
            font-weight: 200;
            font-size: 18px;
            color: #fff;
        }

        .goodsbox {
            margin: 5px;
            background: #fff;
            border: 1px solid{{ $base_style }}
      ;
            width: 170px;
            min-height: 1px;
            display: -moz-inline-stack;
            display: inline-block;
            text-align: center;
            vertical-align: top;
            zoom: 1;
            *display: inline;
            _height: 1px;
        }

        .goodsbox .imgbox {
            width: 170px;
            margin: 0 0 5px 0;
            overflow: hidden;
        }

        .sort_box {
            border: 1px solid #ccc;
            background: #f5f5f5;
            padding: 10px 0 10px 10px;
        }

        .sort_box a {
            color: #222;
        }
    </style>

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

    @if($topic['htmls'] == '')
        <script type="text/javascript">
            var topic_width = "960";
            var topic_height = "300";
            var img_url = "{{ $topic['topic_img'] }}";

            if (img_url.indexOf('.swf') != -1) {
                document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="' + topic_width + '" height="' + topic_height + '">');
                document.write('<param name="movie" value="' + img_url + '"><param name="quality" value="high">');
                document.write('<param name="menu" value="false"><param name=wmode value="opaque">');
                document.write('<embed src="' + img_url + '" wmode="opaque" menu="false" quality="high" width="' + topic_width + '" height="' + topic_height + '" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="transparent"/>');
                document.write('</object>');
            } else {
                document.write('<img width="' + topic_width + '" height="' + topic_height + '" border="0" src="' + img_url + '">');
            }
        </script>
    @else
        {{ $topic['htmls'] }}
    @endif

    @if($topic['intro'] != '')
        {{ $topic['intro'] }}
        <br/><br/>
    @endif

    @if($topic['title_pic'] == '')

        @foreach($sort_goods_arr as $sort => $sort_name)
            <div class="box">
                <div class="box_1 clearfix">
                    <h3><span>{{ $sort_name }}</span></h3>
                    <div class="centerPadd">
                        @foreach($sort as $goods)
                            <div class="goodsItem">
                                <a href="{{ $goods['url'] }}"><img src="{{ $goods['goods_thumb'] }}"
                                                                   alt="{{ $goods['name'] }}"
                                                                   class="goodsimg"/></a><br/>
                                <p><a href="{{ $goods['url'] }}"
                                      title="{{ $goods['name'] }}">{{ $goods['short_style_name'] }}</a></p>
                                <font class="f1">
                                    @if($goods['promote_price'] != "")
                                        {{ $goods['promote_price'] }}
                                    @else
                                        {{ $goods['shop_price'] }}
                                    @endif
                                </font>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @else

        @foreach($sort_goods_arr as $sort => $sort_name)
            <div class="clearfix">
                <h6>{{ $sort_name }}</h6>
                <div class="sort_box">
                    @foreach($sort as $goods)
                        <div class="goodsbox">
                            <div class="imgbox"><a href="{{ $goods['url'] }}"><img src="{{ $goods['goods_thumb'] }}"
                                                                                   alt="{{ $goods['name'] }}"/></a>
                            </div>
                            <a href="{{ $goods['url'] }}"
                               title="{{ $goods['name'] }}">{{ $goods['short_style_name'] }}</a><br/>
                            @if($goods['promote_price'] != "")
                                {{ $goods['promote_price'] }}<br/>
                            @else
                                {{ $goods['shop_price'] }}<br/>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

        @endforeach

    @endif
</div>
<div class="blank5"></div>

@include('web::library/page_footer')
</body>

</html>
