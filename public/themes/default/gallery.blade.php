<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $gallery['goods_name'] }} - {{ $shop_name }}</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link href="{{ $ecs_css_path }}" rel="stylesheet" type="text/css"/>
    <style>
        body {
            margin: 0;
        }
    </style>
    <script type="text/javascript">
        @foreach($lang['gallery_js'] as $lang_js => $key)
        var {{ $key }} = '{{ $lang_js }}';
        @endforeach
    </script>
</head>
<body>
<div id="show-pic">
    <embed src="data/images/pic-view.swf" quality="high" id="picview" flashvars="copyright=shopex&xml=<products name='{{ $gallery['goods_name'] }}' shopname='{{ $shop_name }}'>
@foreach($gallery['list'] as $photo)
<smallpic@if($loop->gallery_list.first) selected='selected'@endif>{{ $photo['gallery_thumb'] }}</smallpic><photo_desc>{{ $photo['img_desc'] }}</photo_desc><bigpic@if($loop->gallery_list.first) selected='selected'@endif>{{ $photo['gallery'] }}</bigpic>
@endforeach
</products>" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="100%"
           height="100%"></embed>
    <script type="text/javascript">
        function windowClose() {
            if (window.confirm(close_window)) {
                window.close();
            }
        }
    </script>
</div>
</body>
</html>
