<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-xl font-semibold text-gray-900">Reset Password</h2>
        <p class="text-sm text-gray-600 mt-1">Enter your new password</p>
    </div>

    <form method="POST" action="{{ route('password.reset-otp') }}">
        @csrf

        <div class="mb-4">
            <x-input-label for="password" :value="__('New Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mb-6">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition mb-4">
            Reset Password
        </button>

        <div class="text-center">
            <p class="text-gray-600 text-sm">
                <a href="{{ route('login') }}" class="text-green-600 hover:text-green-700 font-semibold">
                    Back to Sign In
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
