<x-layouts.guest>
    <x-slot:heading>找回密码</x-slot:heading>
    <x-slot:subheading>请输入您的邮箱以获取密码重置链接</x-slot:subheading>

    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">电子邮箱</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   placeholder="email@example.com"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm placeholder-gray-400 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 @error('email') border-red-500 @enderror">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit" data-test="email-password-reset-link-button"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                发送密码重置链接
            </button>
        </div>

        <div class="text-center text-sm text-gray-600 mt-4">
            <a href="{{ route('login') }}" class="font-medium text-red-600 hover:text-red-500">返回登录</a>
        </div>
    </form>
</x-layouts.guest>
