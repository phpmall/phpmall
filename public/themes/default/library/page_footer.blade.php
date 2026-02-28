<!--友情链接 start-->
@if($img_links || $txt_links)
    <div id="bottomNav" class="box">
        <div class="box_1">
            <div class="links clearfix">
                <!--开始图片类型的友情链接 -->
                @foreach($img_links as $link)
                    <a href="{{ $link['url'] }}" target="_blank" title="{{ $link['name'] }}"><img
                            src="{{ $link['logo'] }}"
                            alt="{{ $link['name'] }}"
                            border="0"/></a>
                    <!--结束图片类型的友情链接 -->
                @endforeach
                @if($txt_links)
                    <!--开始文字类型的友情链接 -->
                    @foreach($txt_links as $link)
                        [<a href="{{ $link['url'] }}" target="_blank"
                            title="{{ $link['name'] }}">{{ $link['name'] }}</a>]
                        <!--结束文字类型的友情链接 -->
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endif
<!--友情链接 end-->
<div class="blank"></div>
<!--底部导航 start-->
<div id="bottomNav" class="box">
    <div class="box_1">
        <div class="bNavList clearfix">
            <div class="f_l">
                @if($navigator_list['bottom'])
                    @foreach($navigator_list['bottom'] as $nav)
                        <a href="{{ $nav['url'] }}"
                           @if($nav['opennew'] == 1)
                           target="_blank"
                           @endif
                           >{{ $nav['name'] }}</a>
                        @if(!$loop->nav_bottom_list.last)
                            -
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="f_r">
                <a href="#top"><img src="images/bnt_top.gif"/></a>
                <a href="{{ url('/') }}"><img src="images/bnt_home.gif"/></a>
            </div>
        </div>
    </div>
</div>
<!--底部导航 end-->
<div class="blank"></div>
<!--版权 start-->
<div id="footer">
    <div class="text">
        {{ $copyright }}<br/>
        {{ $shop_address }} {{ $shop_postcode }}
        <!-- 客服电话-->
        @if($service_phone)
            Tel: {{ $service_phone }}
        @endif
        <!-- 结束客服电话 -->
        <!-- 邮件 -->
        @if($service_email)
            E-mail: {{ $service_email }}<br/>
        @endif
        <!-- 结束邮件 -->
        <!-- QQ 号码 -->
        @foreach($qq as $im)
            @if($im)
                <a href="http://wpa.qq.com/msgrd?V=1&amp;Uin={{ $im }}&amp;Site={{ $shop_name }}&amp;Menu=yes"
                   target="_blank"><img src="http://wpa.qq.com/pa?p=1:{{ $im }}:4" height="16" border="0"
                                        alt="QQ"/> {{ $im }}</a>
            @endif
        @endforeach
        <!-- 结束QQ号码 -->
        <!-- 淘宝旺旺 -->
        @foreach($ww as $im)
            @if($im)
                <a href="http://amos1.taobao.com/msg.ww?v=2&uid={{{ $im }}}&s=2" target="_blank"><img
                        src="http://amos1.taobao.com/online.ww?v=2&uid={{{ $im }}}&s=2" width="16" height="16"
                        border="0" alt="淘宝旺旺"/>{{ $im }}</a>
            @endif
        @endforeach
            <!-- 结束淘宝旺旺 -->
        <!-- Yahoo Messenger -->
        @foreach($ym as $im)
            @if($im)
                <a href="http://edit.yahoo.com/config/send_webmesg?.target={{ $im }}n&.src=pg" target="_blank"><img
                        src="../images/yahoo.gif" width="18" height="17" border="0" alt="Yahoo Messenger"/> {{ $im }}
                </a>
            @endif
        @endforeach
            <!-- 结束Yahoo Messenger -->
        <!-- MSN Messenger -->
        @foreach($msn as $im)
            @if($im)
                <img src="../images/msn.gif" width="18" height="17" border="0" alt="MSN"/> <a
                    href="msnim:chat?contact={{ $im }}">{{ $im }}</a>
            @endif
        @endforeach
            <!-- 结束MSN Messenger -->
        <!-- Skype -->
        @foreach($skype as $im)
            @if($im)
                <img src="http://mystatus.skype.com/smallclassic/{{ urlencode($im) }}" alt="Skype"/><a
                    href="skype:{{ urlencode($im) }}?call">{{ $im }}</a>
            @endif
        @endforeach<br/>
        <!-- ICP 证书-->
        @if($icp_number)
            {{ $lang['icp_number'] }}:<a href="http://www.miibeian.gov.cn/" target="_blank">{{ $icp_number }}</a><br/>
        @endif
            <!-- 结束ICP 证书 -->
        {insert name='query_info'}<br/>
        @foreach($lang['p_y'] as $pv)
            {{ $pv }}
        @endforeach
        {{ $licensed }}<br/>
        @if($stats_code)
            <div align="left">{{ $stats_code }}</div>
        @endif
        <div align="left" id="rss"><a href="{{ $feed_url }}"><img src="../images/xml_rss2.gif" alt="rss"/></a></div>
    </div>
</div>

