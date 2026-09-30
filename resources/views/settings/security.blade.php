<x-layouts.app>
    <x-slot:title>安全设置 - {{ config('app.name') }}</x-slot:title>

    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">设置</h1>
            <p class="text-sm text-gray-500 mt-1">管理您的个人资料和账号设置</p>
        </div>

        <div class="flex flex-col md:flex-row gap-8">
            <aside class="w-full md:w-64 space-y-1">
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100">
                    个人资料
                </a>
                <a href="{{ route('security.edit') }}"
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-red-50 text-red-600 font-semibold">
                    安全设置
                </a>
                <a href="{{ route('appearance.edit') }}"
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100">
                    外观设置
                </a>
            </aside>

            <div class="flex-1 bg-white p-6 sm:p-8 rounded-xl border border-gray-200 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-1">修改密码</h2>
                <p class="text-sm text-gray-500 mb-6">确保您的账号使用足够复杂且随机的密码以保障安全</p>

                @if (session('status'))
                    <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('user-password.update') }}" class="space-y-5 max-w-xl">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">当前密码</label>
                        <input id="current_password" type="password" name="current_password" required autocomplete="current-password"
                               placeholder="请输入当前密码"
                               class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 @error('current_password') border-red-500 @enderror">
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">新密码</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                               placeholder="请输入新密码"
                               class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">确认新密码</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                               placeholder="请再次输入新密码"
                               class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 @error('password_confirmation') border-red-500 @enderror">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" data-test="update-password-button"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            保存
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
