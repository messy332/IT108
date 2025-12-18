@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Add New User</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('users.store') }}" method="POST" novalidate>
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
<p id="name-error" class="text-red-500 text-sm mt-1"></p>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select name="role_id" id="role_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('role_id') border-red-500 @enderror">
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" name="password" id="password" required oninput="checkPasswordStrength(this.value)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p id="password-strength" class="text-sm mt-1"></p>
<p id="password-error" class="text-red-500 text-sm mt-1"></p>
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required oninput="checkPasswordMatch()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
<p id="confirm-error" class="text-red-500 text-sm mt-1"></p>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('users.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    
    // Format name: remove numbers and special characters, capitalize properly
    function formatName(v) {
        v = v.replace(/[^A-Za-z ]+/g, '');
        v = v.replace(/\s+/g, ' ').trim();
        v = v.split(' ').filter(Boolean).map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase()).join(' ');
        return v;
    }
    
    function validateName() {
        const value = nameInput.value.trim();
        const errorEl = document.getElementById('name-error');
        
        if (!value) {
            errorEl.textContent = '';
            nameInput.classList.remove('border-red-500');
            return;
        }
        
        // Check if starts with capital letter
        if (!/^[A-Z]/.test(value)) {
            errorEl.textContent = 'Name must start with a capital letter';
            nameInput.classList.add('border-red-500');
            return;
        }
        
        // Check for numbers
        if (/\d/.test(value)) {
            errorEl.textContent = 'Numbers are not allowed in name';
            nameInput.classList.add('border-red-500');
            return;
        }
        
        // Check for special characters
        if (/[^A-Za-z ]/.test(value)) {
            errorEl.textContent = 'Special characters are not allowed in name';
            nameInput.classList.add('border-red-500');
            return;
        }
        
        errorEl.textContent = '';
        nameInput.classList.remove('border-red-500');
    }
    
    nameInput?.addEventListener('input', () => {
        const pos = nameInput.selectionStart;
        const original = nameInput.value;
        const formatted = formatName(original);
        nameInput.value = formatted;
        
        // Adjust cursor position if text was removed
        const diff = original.length - formatted.length;
        nameInput.setSelectionRange(pos - diff, pos - diff);
        
        validateName();
    });
    
    nameInput?.addEventListener('blur', validateName);
});

function checkPasswordStrength(pwd) {
    const strengthEl = document.getElementById('password-strength');
    if (pwd.length < 9) {
        strengthEl.textContent = 'Password too short (minimum 9 characters)';
        strengthEl.className = 'text-red-600';
        return;
    }
    const hasLetter = /[a-zA-Z]/.test(pwd);
    const hasNumber = /[0-9]/.test(pwd);
    const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(pwd);
    let strength = '';
    if (hasLetter && !hasNumber && !hasSpecial) {
        strength = 'Weak';
        strengthEl.className = 'text-orange-600';
    } else if (hasLetter && hasNumber && !hasSpecial) {
        strength = 'Medium';
        strengthEl.className = 'text-yellow-600';
    } else if (hasLetter && hasNumber && hasSpecial) {
        strength = 'Strong';
        strengthEl.className = 'text-green-600';
    } else {
        strength = 'Invalid';
        strengthEl.className = 'text-red-600';
    }
    strengthEl.textContent = 'Password strength: ' + strength;
}

function checkPasswordMatch() {
    const pwd = document.getElementById('password').value;
    const confirmPwd = document.getElementById('password_confirmation').value;
    const errorEl = document.getElementById('confirm-error');
    if (confirmPwd && pwd !== confirmPwd) {
        errorEl.textContent = 'Passwords do not match';
    } else {
        errorEl.textContent = '';
    }
}

// Form validation before submit
document.querySelector('form').addEventListener('submit', function(e) {
    const nameInput = document.getElementById('name');
    const passwordInput = document.getElementById('password');
    const namePattern = /^[A-Z][a-zA-Z]*(?: [a-zA-Z]+)?$/;
    if (!namePattern.test(nameInput.value)) {
        e.preventDefault();
        document.getElementById('name-error').textContent = 'Name must start with a capital letter, contain only letters, and may include a single space.';
        nameInput.focus();
        return;
    } else {
        document.getElementById('name-error').textContent = '';
    }
    if (passwordInput.value.length < 9) {
        e.preventDefault();
        document.getElementById('password-error').textContent = 'Password must be at least 9 characters long.';
        passwordInput.focus();
        return;
    } else {
        document.getElementById('password-error').textContent = '';
    }
    const confirmPwd = document.getElementById('password_confirmation').value;
    if (confirmPwd && passwordInput.value !== confirmPwd) {
        e.preventDefault();
        document.getElementById('confirm-error').textContent = 'Passwords do not match.';
        passwordInput.focus();
        return;
    } else {
        document.getElementById('confirm-error').textContent = '';
    }
});
</script>
@endsection
