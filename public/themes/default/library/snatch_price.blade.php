<div class="box">
    <div class="box_1">
        <h3><span>{{ $lang['new_price'] }}</span></h3>
        <div class="boxCenterList RelaArticle">
            <ul class="clearfix">
                @foreach($price_list as $item)
                    <li>{{ $item['user_name'] }}&nbsp;&nbsp;{{ $item['bid_price'] }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<div class="blank5"></div>
