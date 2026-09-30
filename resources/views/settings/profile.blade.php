<x-layouts.app>
    <x-slot:title>个人资料设置 - {{ config('app.name') }}</x-slot:title>

    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">设置</h1>
            <p class="text-sm text-gray-500 mt-1">管理您的个人资料和账号设置</p>
        </div>

        <div class="flex flex-col md:flex-row gap-8">
            <aside class="w-full md:w-64 space-y-1">
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-red-50 text-red-600 font-semibold">
                    个人资料
                </a>
                <a href="{{ route('security.edit') }}"
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100">
                    安全设置
                </a>
                <a href="{{ route('appearance.edit') }}"
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100">
                    外观设置
                </a>
            </aside>

            <div class="flex-1 space-y-8">
                <!-- 资料更新表单 -->
                <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-200 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 mb-1">个人资料</h2>
                    <p class="text-sm text-gray-500 mb-6">更新您的姓名和电子邮箱</p>

                    @if (session('status'))
                        <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-5 max-w-xl">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">姓名</label>
                            <input id="name" type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">电子邮箱</label>
                            <input id="email" type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <button type="submit" data-test="update-profile-button"
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                保存
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 账号注销 -->
                <div class="bg-white p-6 sm:p-8 rounded-xl border border-red-200 shadow-sm">
                    <h2 class="text-lg font-semibold text-red-600 mb-1">注销账号</h2>
                    <p class="text-sm text-gray-500 mb-6">永久注销您的账号及所有相关数据，请谨慎操作，该操作无法撤销。</p>

                    <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4 max-w-xl" onsubmit="return confirm('确定要永久注销账号吗？该操作不可恢复！');">
                        @csrf
                        @method('DELETE')

                        <div>
                            <label for="delete_password" class="block text-sm font-medium text-gray-700">请输入密码以确认注销</label>
                            <input id="delete_password" type="password" name="password" required placeholder="请输入您的密码"
                                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <button type="submit" data-test="delete-user-button"
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                注销账号
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
