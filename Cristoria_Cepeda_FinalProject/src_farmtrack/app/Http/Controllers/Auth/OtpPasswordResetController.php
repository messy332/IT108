<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class OtpPasswordResetController extends Controller
{
    public function requestOtp(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Email not found'], 422);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        PasswordResetOtp::updateOrCreate(
            ['email' => $request->email],
            ['otp' => $otp, 'expires_at' => now()->addMinutes(10)]
        );

        Mail::send('emails.otp', ['otp' => $otp, 'email' => $request->email], function ($message) use ($request) {
            $message->to($request->email)->subject('Your Password Reset OTP');
        });

        return response()->json(['success' => true, 'message' => 'OTP sent to your email']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'digits:6'],
        ], [
            'otp.digits' => 'Invalid OTP format. Please enter exactly 6 digits.',
        ]);

        // Check if OTP exists for this email
        $record = PasswordResetOtp::where('email', $request->email)->first();

        if (!$record) {
            return response()->json(['success' => false, 'message' => 'No OTP found. Please request a new one.'], 422);
        }

        // Check if OTP is expired
        if ($record->expires_at < now()) {
            $record->delete();
            return response()->json(['success' => false, 'message' => 'OTP has expired. A new one will be sent automatically.'], 422);
        }

        // Check if OTP matches
        if ($record->otp !== $request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP. Please check and try again.'], 422);
        }

        session(['otp_verified_email' => $request->email]);
        $record->delete();

        return response()->json(['success' => true, 'message' => 'OTP verified']);
    }

    public function resetPassword(Request $request)
    {
        // Get email from session or request body
        $email = session('otp_verified_email') ?? $request->email;
        
        if (!$email) {
            return response()->json(['success' => false, 'message' => 'Session expired. Please verify OTP again.'], 422);
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 422);
        }
        
        $user->update(['password' => Hash::make($request->password)]);

        session()->forget('otp_verified_email');

        return response()->json(['success' => true, 'message' => 'Password reset successfully']);
    }
}
