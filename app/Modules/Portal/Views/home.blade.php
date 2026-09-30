@extends('portal::layouts.portal')

@section('title', '商城首页 - 正品低价、品质保障、轻松购物！')

@section('content')
<!-- 首屏核心区域：品类目录 + 巨幅轮播 + 右侧用户服务资讯 (Hero Section) -->
<main class="main-hero">
  <div class="w hero-layout">
    <!-- 左侧：细分类目树导航 -->
    <aside class="category-menu" id="categoryMenu">
      <ul class="cat-list">
        @forelse($categories as $cat)
          <li class="cat-item">
            <a href="{{ route('portal.goods.list', ['category_id' => $cat['id']]) }}">
              {{ $cat['name'] }}
            </a>
            <span class="cat-arrow">›</span>
          </li>
        @empty
          <li class="cat-item"><a href="javascript:;">暂无分类</a></li>
        @endforelse
      </ul>
    </aside>

    <!-- 中间巨幕轮播 Banner -->
    <section class="hero-slider" id="heroSlider">
      <div class="slider-container" style="background: linear-gradient(135deg, #e1251b 0%, #ff6b6b 100%); display: flex; align-items: center; padding: 40px; color: white; border-radius: 8px; height: 100%;">
        <div class="banner-inner">
          <span style="background: rgba(255,255,255,0.25); padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: bold;">2026 全球尖货狂欢节</span>
          <h1 style="font-size: 42px; margin: 15px 0; font-weight: 800; line-height: 1.2;">正品直供<br/>限时狂欢满减</h1>
          <p style="font-size: 16px; opacity: 0.9; margin-bottom: 25px;">全场数码家电、美妆个护爆款低至 5 折起，顺丰极速配送</p>
          <a href="{{ route('portal.goods.list') }}" style="background: #fff; color: #e1251b; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: bold; font-size: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: inline-block;">立即前往选购 ›</a>
        </div>
      </div>
    </section>

    <!-- 右侧：个人信息卡片与快报 -->
    <aside class="hero-extra">
      <div class="user-card">
        <div class="user-avatar-wrap">
          <div class="avatar-default" style="font-size: 32px;">👤</div>
        </div>
        @auth
          <p class="user-greeting">Hi, <strong>{{ auth()->user()->name }}</strong></p>
          <div class="user-tags">
            <span class="tag-plus">PLUS 会员</span>
          </div>
          <div class="user-btns" style="margin-top: 15px;">
            <a href="{{ route('portal.order.my') }}" class="btn-login-small" style="background: #e1251b; color: white;">我的订单</a>
            <a href="{{ route('portal.cart.view') }}" class="btn-reg-small" style="background: #f5f5f5; color: #333;">购物车</a>
          </div>
        @else
          <p class="user-greeting">Hi, <strong>欢迎光临！</strong></p>
          <div class="user-btns">
            <a href="{{ route('login') }}" class="btn-login-small">登 录</a>
            <a href="{{ route('register') }}" class="btn-reg-small">注 册</a>
          </div>
        @endauth
      </div>

      <!-- 促销资讯选项卡 -->
      <div class="news-tab">
        <div class="tab-heads">
          <span class="tab-title active">促销特惠</span>
          <span class="tab-title">商城快报</span>
        </div>
        <ul class="news-list">
          <li><a href="{{ route('portal.goods.list', ['is_hot' => 1]) }}"><span class="tag">HOT</span> 2026 春季旗舰新品首发上市</a></li>
          <li><a href="{{ route('portal.goods.list') }}"><span class="tag">通知</span> 全场订单支持极速闪电发货</a></li>
          <li><a href="{{ route('portal.goods.list') }}"><span class="tag">特惠</span> 会员专享大额优惠券火热开抢</a></li>
          <li><a href="{{ route('portal.goods.list') }}"><span class="tag">服务</span> 全程正品保障 支持7天无理由</a></li>
        </ul>
      </div>
    </aside>
  </div>
</main>

<!-- 为你推荐楼层 (Recommend Floor) -->
<section class="recommend-floor w" style="margin-top: 30px;">
  <div class="floor-head" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 15px;">
    <div>
      <h3 class="floor-title" style="font-size: 22px; font-weight: bold; color: #333; margin: 0;">为你推荐</h3>
      <span class="floor-sub" style="font-size: 13px; color: #999;">品质好物 每日更新</span>
    </div>
    <a href="{{ route('portal.goods.list') }}" style="color: #e1251b; text-decoration: none; font-size: 13px;">查看更多 ›</a>
  </div>

  <div class="goods-grid" id="recommendGrid" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px;">
    @forelse($featured as $item)
      <div class="goods-card" style="background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 6px 16px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)'">
        <a href="{{ route('portal.goods.show', $item->id) }}" style="text-decoration: none; color: inherit; display: block;">
          <div class="card-img" style="width: 100%; height: 210px; background: #f9f9f9; display: flex; align-items: center; justify-content: center; overflow: hidden;">
            @if($item->main_image)
              <img src="{{ $item->main_image }}" alt="{{ $item->title }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
              <span style="font-size: 48px;">📦</span>
            @endif
          </div>
          <div class="card-info" style="padding: 12px;">
            <div class="card-price" style="color: #e1251b; font-size: 18px; font-weight: bold;">
              <span style="font-size: 12px;">¥</span>{{ number_format($item->min_price / 100, 2) }}
            </div>
            <div class="card-title" style="font-size: 14px; color: #333; font-weight: 500; margin: 6px 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
              {{ $item->title }}
            </div>
            <div class="card-meta" style="display: flex; justify-content: space-between; font-size: 12px; color: #999;">
              <span>销量 {{ $item->sales_count }} 件</span>
              @if($item->is_hot)
                <span class="badge-tag" style="color: #e1251b; background: #fdf2f2; padding: 1px 4px; border-radius: 2px;">热销</span>
              @endif
            </div>
          </div>
        </a>
      </div>
    @empty
      <div style="grid-column: span 5; text-align: center; padding: 50px; color: #999;">
        暂无推荐商品
      </div>
    @endforelse
  </div>
</section>
@endsection
