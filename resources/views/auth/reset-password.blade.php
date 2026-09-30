<x-layouts.guest>
    <x-slot:heading>重置密码</x-slot:heading>
    <x-slot:subheading>请在下方输入您的新密码</x-slot:subheading>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') ?? $token ?? '' }}">

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">电子邮箱</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email ?? $email ?? '') }}" required readonly
                   class="mt-1 block w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-500 shadow-sm focus:outline-none">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">新密码</label>
            <input id="password" type="password" name="password" required autofocus autocomplete="new-password"
                   placeholder="请输入新密码"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm placeholder-gray-400 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 @error('password') border-red-500 @enderror">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">确认新密码</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   placeholder="请再次输入新密码"
                   class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm placeholder-gray-400 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 @error('password_confirmation') border-red-500 @enderror">
            @error('password_confirmation')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit" data-test="reset-password-button"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                重置密码
            </button>
        </div>
    </form>
</x-layouts.guest>
