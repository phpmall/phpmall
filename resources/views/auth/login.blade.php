<x-layouts.guest>
    <x-slot:heading>登录您的账号</x-slot:heading>
    <x-slot:subheading>请输入您的邮箱和密码以登录系统</x-slot:subheading>

    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">电子邮箱</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   placeholder="email@example.com"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm placeholder-gray-400 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 @error('email') border-red-500 @enderror">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-gray-700">密码</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-red-600 hover:text-red-500">
                        忘记密码？
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   placeholder="请输入密码"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm placeholder-gray-400 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 @error('password') border-red-500 @enderror">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox"
                       class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                <label for="remember" class="ml-2 block text-sm text-gray-700">记住我</label>
            </div>
        </div>

        <div>
            <button type="submit" data-test="login-button"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                登录
            </button>
        </div>

        @if (Route::has('register'))
            <div class="text-center text-sm text-gray-600 mt-4">
                还没有账号？
                <a href="{{ route('register') }}" class="font-medium text-red-600 hover:text-red-500">立即注册</a>
            </div>
        @endif
    </form>
</x-layouts.guest>
