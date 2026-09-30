<x-layouts.app>
    <x-slot:title>{{ $title ?? '账号设置' }} - {{ config('app.name') }}</x-slot:title>

    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">设置</h1>
            <p class="text-sm text-gray-500 mt-1">管理您的个人资料和账号设置</p>
        </div>

        <div class="flex flex-col md:flex-row gap-8">
            <aside class="w-full md:w-64 space-y-1">
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('profile.edit') ? 'bg-red-50 text-red-600 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                    个人资料
                </a>
                <a href="{{ route('security.edit') }}"
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('security.edit') ? 'bg-red-50 text-red-600 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                    安全设置
                </a>
                <a href="{{ route('appearance.edit') }}"
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('appearance.edit') ? 'bg-red-50 text-red-600 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                    外观设置
                </a>
            </aside>

            <div class="flex-1 bg-white p-6 sm:p-8 rounded-xl border border-gray-200 shadow-sm">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-layouts.app>
