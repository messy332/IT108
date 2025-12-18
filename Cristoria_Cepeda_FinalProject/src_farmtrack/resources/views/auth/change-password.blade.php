@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-6">
                <div class="mx-auto h-16 w-16 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Change Your Password</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Your account was created with a default password. Please set a new password to continue.
                </p>
            </div>

            @if(session('warning'))
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg mb-4">
                    {{ session('warning') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.change.update') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        New Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="Enter new password">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    
                    <!-- Password Strength Indicator -->
                    <div id="password-strength-container" class="mt-2 hidden">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div id="strength-bar" class="h-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <span id="strength-text" class="text-xs font-medium w-16"></span>
                        </div>
                        <ul class="text-xs space-y-1 mt-2">
                            <li id="check-length" class="flex items-center gap-1 text-gray-400">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                <span>At least 8 characters</span>
                            </li>
                            <li id="check-letters" class="flex items-center gap-1 text-gray-400">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                <span>Contains letters (Weak)</span>
                            </li>
                            <li id="check-numbers" class="flex items-center gap-1 text-gray-400">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                <span>Contains numbers (Medium)</span>
                            </li>
                            <li id="check-special" class="flex items-center gap-1 text-gray-400">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="3"/></svg>
                                <span>Contains special characters (Strong)</span>
                            </li>
                        </ul>
                    </div>
                    
                    <p class="text-gray-500 text-xs mt-1">Minimum 8 characters</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="Confirm new password">
                </div>

                <button type="submit"
                        class="w-full py-3 px-4 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                    Change Password
                </button>
            </form>

            <div class="mt-4 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                        Logout instead
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const strengthContainer = document.getElementById('password-strength-container');
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');
    const checkLength = document.getElementById('check-length');
    const checkLetters = document.getElementById('check-letters');
    const checkNumbers = document.getElementById('check-numbers');
    const checkSpecial = document.getElementById('check-special');

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        
        if (password.length === 0) {
            strengthContainer.classList.add('hidden');
            return;
        }
        
        strengthContainer.classList.remove('hidden');
        
        // Check criteria
        const hasLength = password.length >= 8;
        const hasLetters = /[a-zA-Z]/.test(password);
        const hasNumbers = /[0-9]/.test(password);
        const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
        
        // Update checkmarks
        updateCheck(checkLength, hasLength);
        updateCheck(checkLetters, hasLetters);
        updateCheck(checkNumbers, hasNumbers);
        updateCheck(checkSpecial, hasSpecial);
        
        // Calculate strength
        let strength = 'none';
        let barWidth = 0;
        let barColor = 'bg-gray-300';
        let textColor = 'text-gray-500';
        
        if (hasLength) {
            if (hasLetters && hasNumbers && hasSpecial) {
                strength = 'Strong';
                barWidth = 100;
                barColor = 'bg-green-500';
                textColor = 'text-green-600';
            } else if (hasLetters && hasNumbers) {
                strength = 'Medium';
                barWidth = 66;
                barColor = 'bg-yellow-500';
                textColor = 'text-yellow-600';
            } else if (hasLetters) {
                strength = 'Weak';
                barWidth = 33;
                barColor = 'bg-red-500';
                textColor = 'text-red-600';
            } else {
                strength = 'Very Weak';
                barWidth = 15;
                barColor = 'bg-red-400';
                textColor = 'text-red-500';
            }
        } else {
            strength = 'Too Short';
            barWidth = 10;
            barColor = 'bg-gray-400';
            textColor = 'text-gray-500';
        }
        
        // Update UI
        strengthBar.className = 'h-full transition-all duration-300 ' + barColor;
        strengthBar.style.width = barWidth + '%';
        strengthText.textContent = strength;
        strengthText.className = 'text-xs font-medium w-16 ' + textColor;
    });
    
    function updateCheck(element, passed) {
        if (passed) {
            element.classList.remove('text-gray-400');
            element.classList.add('text-green-600');
            element.querySelector('svg').innerHTML = '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>';
        } else {
            element.classList.remove('text-green-600');
            element.classList.add('text-gray-400');
            element.querySelector('svg').innerHTML = '<circle cx="10" cy="10" r="3"/>';
        }
    }
});
</script>
@endpush
