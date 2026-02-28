@if($goods_article_list)
    <div class="box">
        <div class="box_1">
            <h3><span>{{ $lang['article_releate'] }}</span></h3>
            <div class="boxCenterList RelaArticle">
                <!-- @foreach($goods_article_list as $article)
                    相关文章 -->
                    <a href="{{ $article['url'] }}" title="{{ $article['title'] }}">{{ $article['short_title'] }}</a>
                    <br/>
                @endforeach
            </div>
        </div>
    </div>
    <div class="blank5"></div>
@endif
