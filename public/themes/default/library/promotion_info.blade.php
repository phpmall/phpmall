@if($promotion_info)
    <!-- 促销信息 -->
    <div class="box">
        <div class="box_1">
            <h3><span>{{ $lang['promotion_info'] }}</span></h3>
            <div class="boxCenterList RelaArticle">
                @foreach($promotion_info as $item => $key)
                    @if($item['type'] == "snatch")
                        <a href="snatch.php" title="{$lang.$item.type}">{{ $lang['snatch_promotion'] }}</a>
                    @elseif($item['type'] == "group_buy")
                        <a href="group_buy.php" title="{$lang.$item.type}">{{ $lang['group_promotion'] }}</a>
                    @elseif($item['type'] == "auction")
                        <a href="auction.php" title="{$lang.$item.type}">{{ $lang['auction_promotion'] }}</a>
                    @elseif($item['type'] == "favourable")
                        <a href="activity.php" title="{$lang.$item.type}">{{ $lang['favourable_promotion'] }}</a>
                    @elseif($item['type'] == "package")
                        <a href="package.php" title="{$lang.$item.type}">{{ $lang['package_promotion'] }}</a>
                    @endif
                    <a href="{{ $item['url'] }}" title="{$lang.$item.type} {{ $item['act_name'] }}{{ $item['time'] }}"
                       style="background:none; padding-left:0px;">{{ $item['act_name'] }}</a><br/>
                @endforeach
            </div>
        </div>
    </div>
    <div class="blank5"></div>
@endif
