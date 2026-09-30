<x-layouts.guest>
    <x-slot:heading>创建新账号</x-slot:heading>
    <x-slot:subheading>请在下方填写信息以完成注册</x-slot:subheading>

    <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">姓名</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   placeholder="请输入姓名"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm placeholder-gray-400 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">电子邮箱</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   placeholder="email@example.com"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm placeholder-gray-400 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 @error('email') border-red-500 @enderror">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">密码</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   placeholder="请输入密码"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm placeholder-gray-400 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 @error('password') border-red-500 @enderror">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">确认密码</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   placeholder="请再次输入密码"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm placeholder-gray-400 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 @error('password_confirmation') border-red-500 @enderror">
            @error('password_confirmation')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit" data-test="register-user-button"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                创建账号
            </button>
        </div>

        <div class="text-center text-sm text-gray-600 mt-4">
            已有账号？
            <a href="{{ route('login') }}" class="font-medium text-red-600 hover:text-red-500">立即登录</a>
        </div>
    </form>
</x-layouts.guest>
