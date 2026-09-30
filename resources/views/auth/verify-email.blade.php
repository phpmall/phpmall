<x-layouts.guest>
    <x-slot:heading>邮箱验证</x-slot:heading>
    <x-slot:subheading>请点击我们发送到您邮箱的链接以完成邮箱验证。</x-slot:subheading>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">
            新的验证链接已发送至您在注册时提供的电子邮箱。
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                重新发送验证邮件
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-500">
                退出登录
            </button>
        </form>
    </div>
</x-layouts.guest>
