<x-layouts.guest>
    <x-slot:heading>双重身份验证</x-slot:heading>
    <x-slot:subheading>请输入身份验证器应用提供的验证码，或使用备用恢复码</x-slot:subheading>

    <form method="POST" action="{{ route('two-factor.login.store') }}" class="space-y-5">
        @csrf

        <div>
            <label for="code" class="block text-sm font-medium text-gray-700">验证码</label>
            <input id="code" type="text" inputmode="numeric" name="code" autofocus autocomplete="one-time-code"
                   placeholder="请输入6位验证码"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm placeholder-gray-400 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 @error('code') border-red-500 @enderror">
            @error('code')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="recovery_code" class="block text-sm font-medium text-gray-700">或使用恢复码</label>
            <input id="recovery_code" type="text" name="recovery_code" autocomplete="one-time-code"
                   placeholder="请输入应急恢复码"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm placeholder-gray-400 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 @error('recovery_code') border-red-500 @enderror">
            @error('recovery_code')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                验证并登录
            </button>
        </div>
    </form>
</x-layouts.guest>
