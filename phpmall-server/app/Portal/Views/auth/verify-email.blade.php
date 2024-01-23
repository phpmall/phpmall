<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('感谢您的注册，请完成邮箱验证。') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('一个新的验证链接已发送到您在注册时提供的电子邮件地址。') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('重新发送验证邮件') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('退出') }}
            </button>
        </form>
    </div>
</x-guest-layout>
