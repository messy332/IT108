<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-xl font-semibold text-gray-900">Verify OTP</h2>
        <p class="text-sm text-gray-600 mt-1">Enter the 6-digit code sent to your email</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.verify-otp') }}" id="verifyForm">
        @csrf

        <div class="mb-4">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mb-6">
            <x-input-label for="otp" :value="__('OTP Code')" />
            <x-text-input id="otp" class="block mt-1 w-full text-center text-2xl tracking-widest" type="text" name="otp" maxlength="6" placeholder="000000" required inputmode="numeric" />
            <div id="otpError" class="text-red-600 text-sm mt-2"></div>
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition mb-4">
            Verify OTP
        </button>

        <div class="text-center">
            <p class="text-gray-600 text-sm">
                Didn't receive the code?
                <button type="button" id="resendBtn" onclick="resendOtp()"
                    class="text-green-600 hover:text-green-700 font-semibold">
                    Resend OTP
                </button>
                <span id="timerDisplay" class="text-gray-500 text-sm ml-2"></span>
            </p>
        </div>
    </form>

    <form id="resendForm" method="POST" action="{{ route('password.request-otp') }}" style="display: none;">
        @csrf
        <input type="hidden" name="email" value="{{ old('email') }}">
    </form>

    <script>
        const OTP_EXPIRY_MINUTES = 10;
        const RESEND_COOLDOWN_SECONDS = 30;
        let expiryTime = null;
        let resendCooldownTime = null;

        function initializeTimer() {
            // Set expiry time to 10 minutes from now
            expiryTime = Date.now() + (OTP_EXPIRY_MINUTES * 60 * 1000);
            localStorage.setItem('otpExpiryTime', expiryTime);
            updateTimer();
        }

        function updateTimer() {
            const now = Date.now();
            const timeLeft = expiryTime - now;

            if (timeLeft <= 0) {
                // OTP expired, auto-resend
                autoResendOtp();
                return;
            }

            const minutes = Math.floor(timeLeft / 60000);
            const seconds = Math.floor((timeLeft % 60000) / 1000);
            document.getElementById('timerDisplay').textContent = 
                `(Expires in ${minutes}:${seconds.toString().padStart(2, '0')})`;

            setTimeout(updateTimer, 1000);
        }

        function resendOtp() {
            const resendBtn = document.getElementById('resendBtn');
            resendBtn.disabled = true;
            resendBtn.classList.add('opacity-50', 'cursor-not-allowed');

            document.getElementById('resendForm').submit();

            // Reset cooldown
            resendCooldownTime = Date.now() + (RESEND_COOLDOWN_SECONDS * 1000);
            updateResendCooldown();
        }

        function autoResendOtp() {
            const email = document.querySelector('input[name="email"]').value;
            if (!email) return;

            fetch('{{ route("password.request-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reset timer for new OTP
                    initializeTimer();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function updateResendCooldown() {
            const resendBtn = document.getElementById('resendBtn');
            const now = Date.now();
            const timeLeft = resendCooldownTime - now;

            if (timeLeft <= 0) {
                resendBtn.disabled = false;
                resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                return;
            }

            const seconds = Math.ceil(timeLeft / 1000);
            resendBtn.textContent = `Resend OTP (${seconds}s)`;

            setTimeout(updateResendCooldown, 1000);
        }

        // OTP Input Validation
        const otpInput = document.getElementById('otp');
        const otpError = document.getElementById('otpError');
        const verifyForm = document.getElementById('verifyForm');

        otpInput.addEventListener('input', function(e) {
            // Remove any non-digit characters
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Clear error on input
            otpError.textContent = '';
        });

        otpInput.addEventListener('keypress', function(e) {
            // Only allow digits
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
                otpError.textContent = 'Invalid OTP format. Only numbers are allowed.';
            }
        });

        verifyForm.addEventListener('submit', function(e) {
            const otpValue = otpInput.value.trim();
            
            // Validate OTP format
            if (!/^\d{6}$/.test(otpValue)) {
                e.preventDefault();
                otpError.textContent = 'Invalid OTP format. Please enter exactly 6 digits.';
                return false;
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedExpiryTime = localStorage.getItem('otpExpiryTime');
            if (savedExpiryTime && parseInt(savedExpiryTime) > Date.now()) {
                expiryTime = parseInt(savedExpiryTime);
            } else {
                initializeTimer();
            }
            updateTimer();
        });
    </script>
</x-guest-layout>
