@extends('portal.layouts.portal')

@section('title', '商城首页 - 品质好物 一站购齐')

@section('content')
<div class="w" style="margin-top: 15px;">
  <!-- 首页首屏：分类导航 + 主推轮播 -->
  <div style="display: flex; gap: 15px; height: 460px;">
    <!-- 左侧分类侧边栏 -->
    <div style="width: 220px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 12px 0;">
      <ul style="list-style: none; margin: 0; padding: 0;">
        @forelse($categories as $cat)
          <li style="padding: 10px 20px; font-size: 14px; display: flex; justify-content: space-between; align-items: center; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#fdf2f2'" onmouseout="this.style.background='transparent'">
            <a href="{{ route('portal.goods.list', ['category_id' => $cat['id']]) }}" style="color: #333; text-decoration: none; font-weight: 500;">
              {{ $cat['name'] }}
            </a>
            <span style="color: #999; font-size: 12px;">›</span>
          </li>
        @empty
          <li style="padding: 15px 20px; color: #999; font-size: 13px;">暂无分类</li>
        @endforelse
      </ul>
    </div>

    <!-- 中间巨幕 Banner -->
    <div style="flex: 1; border-radius: 8px; overflow: hidden; position: relative; background: linear-gradient(135deg, #e1251b 0%, #ff6b6b 100%); display: flex; align-items: center; padding: 40px; color: white;">
      <div>
        <span style="background: rgba(255,255,255,0.25); padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: bold;">2026 全球尖货狂欢节</span>
        <h1 style="font-size: 42px; margin: 15px 0; font-weight: 800; line-height: 1.2;">正品直供<br/>限时狂欢满减</h1>
        <p style="font-size: 16px; opacity: 0.9; margin-bottom: 25px;">全场数码家电、美妆个护爆款低至 5 折起，顺丰极速配送</p>
        <a href="{{ route('portal.goods.list') }}" style="background: #fff; color: #e1251b; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: bold; font-size: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">立即前往选购 ›</a>
      </div>
    </div>

    <!-- 右侧个人与热销简报 -->
    <div style="width: 260px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
      <div style="text-align: center; padding-bottom: 15px; border-bottom: 1px dashed #eee;">
        <div style="width: 60px; height: 60px; border-radius: 50%; background: #fdf2f2; color: #e1251b; font-size: 24px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">👤</div>
        @auth
          <div style="font-weight: bold; font-size: 15px; color: #333;">Hi, {{ auth()->user()->name }}</div>
          <p style="font-size: 12px; color: #999; margin: 5px 0;">欢迎光临优品商城</p>
        @else
          <div style="font-weight: bold; font-size: 15px; color: #333;">Hi, 欢迎光临！</div>
          <div style="display: flex; gap: 8px; justify-content: center; margin-top: 10px;">
            <a href="{{ route('login') }}" style="background: #e1251b; color: white; padding: 4px 14px; border-radius: 12px; font-size: 12px; text-decoration: none;">登录</a>
            <a href="{{ route('register') }}" style="background: #f5f5f5; color: #333; padding: 4px 14px; border-radius: 12px; font-size: 12px; text-decoration: none;">注册</a>
          </div>
        @endauth
      </div>

      <div>
        <div style="font-size: 14px; font-weight: bold; color: #333; margin-bottom: 10px;">商城快报</div>
        <ul style="list-style: none; margin: 0; padding: 0; font-size: 12px; color: #666; line-height: 2;">
          <li><span style="color: #e1251b; margin-right: 5px;">[热点]</span> 2026 春季旗舰新品首发上市</li>
          <li><span style="color: #e1251b; margin-right: 5px;">[通知]</span> 全场订单支持极速闪电发货</li>
          <li><span style="color: #e1251b; margin-right: 5px;">[特惠]</span> 会员专享大额优惠券火热开抢</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- 为你推荐商品网格 -->
  <div style="margin-top: 35px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 15px;">
      <div>
        <h2 style="font-size: 22px; font-weight: bold; color: #333; margin: 0;">为你推荐 (Featured Goods)</h2>
        <p style="font-size: 13px; color: #999; margin: 4px 0 0;">品质精选，每日更新</p>
      </div>
      <a href="{{ route('portal.goods.list') }}" style="color: #e1251b; text-decoration: none; font-size: 13px;">查看更多 ›</a>
    </div>

    <div id="goodsGrid" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px;">
      @forelse($featured as $item)
        <div style="background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: transform 0.2s, box-shadow 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 6px 16px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)'">
          <a href="{{ route('portal.goods.show', $item->id) }}" style="text-decoration: none; color: inherit; display: block;">
            <div style="width: 100%; height: 210px; background: #f7f7f7; display: flex; align-items: center; justify-content: center; overflow: hidden;">
              @if($item->main_image)
                <img src="{{ $item->main_image }}" alt="{{ $item->title }}" style="width: 100%; height: 100%; object-fit: cover;">
              @else
                <span style="font-size: 48px;">📦</span>
              @endif
            </div>
            <div style="padding: 12px;">
              <div style="color: #e1251b; font-size: 18px; font-weight: bold;">
                <span style="font-size: 12px;">¥</span>{{ number_format($item->min_price / 100, 2) }}
              </div>
              <div style="font-size: 14px; color: #333; font-weight: 500; margin: 6px 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                {{ $item->title }}
              </div>
              <div style="display: flex; justify-content: space-between; font-size: 12px; color: #999;">
                <span>已售 {{ $item->sales_count }} 件</span>
                @if($item->is_hot)
                  <span style="color: #e1251b; background: #fdf2f2; padding: 1px 4px; border-radius: 2px;">热销</span>
                @endif
              </div>
            </div>
          </a>
        </div>
      @empty
        <div style="grid-column: span 5; text-align: center; padding: 40px; color: #999;">
          暂无推荐商品
        </div>
      @endforelse
    </div>
  </div>
</div>
@endsection
