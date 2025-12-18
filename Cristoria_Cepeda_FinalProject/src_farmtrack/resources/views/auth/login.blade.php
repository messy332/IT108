<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-xl font-semibold text-gray-900">Welcome Back</h2>
        <p class="text-sm text-gray-600 mt-1">Sign in to your Farm Track account</p>
        <div class="mt-3 p-3 bg-green-50 rounded-lg">
            <p class="text-xs text-green-700 mb-1">Demo Credentials:</p>
            <p class="text-xs text-gray-600">Email: admin@gmail.com</p>
            <p class="text-xs text-gray-600">Password: admin_1010</p>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Security Notice for Lockout -->
    @if(session('lockout_message'))
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-sm text-red-700">{{ session('lockout_message') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="login-form">
        @csrf

        <!-- Honeypot field for bot detection (hidden from users) -->
        <div style="position: absolute; left: -9999px;" aria-hidden="true">
            <input type="text" name="website" tabindex="-1" autocomplete="off">
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-green-600 hover:text-green-900 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif

            <button type="submit" id="login-btn" class="ml-3 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Sign In') }}
            </button>
        </div>
    </form>

    <!-- Security info -->
    <div class="mt-6 pt-4 border-t border-gray-200">
        <p class="text-xs text-gray-500 text-center">
            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            Your connection is secure. Account will be locked after 5 failed attempts.
        </p>
    </div>

    <script>
        // Prevent form resubmission on back button
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        // Disable submit button after click to prevent double submission
        document.getElementById('login-form').addEventListener('submit', function() {
            const btn = document.getElementById('login-btn');
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Signing in...';
        });

        // Check for honeypot (bot detection)
        document.getElementById('login-form').addEventListener('submit', function(e) {
            const honeypot = document.querySelector('input[name="website"]');
            if (honeypot && honeypot.value !== '') {
                e.preventDefault();
                return false;
            }
        });
    </script>
</x-guest-layout>
