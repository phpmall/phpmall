<x-layouts.app>
    <x-slot:title>仪表盘 - {{ config('app.name') }}</x-slot:title>

    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h1 class="text-2xl font-bold text-gray-900">仪表盘</h1>
            <p class="mt-1 text-sm text-gray-500">欢迎回来，{{ Auth::user()->name }}！</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-sm font-medium text-gray-500">我的订单</div>
                <div class="mt-2 text-3xl font-bold text-gray-900">0</div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-sm font-medium text-gray-500">优惠券</div>
                <div class="mt-2 text-3xl font-bold text-gray-900">0</div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-sm font-medium text-gray-500">收藏商品</div>
                <div class="mt-2 text-3xl font-bold text-gray-900">0</div>
            </div>
        </div>
    </div>
</x-layouts.app>
