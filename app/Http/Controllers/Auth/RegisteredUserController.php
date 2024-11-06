<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function mobileRegister(Request $request)
    {
        Log::info('Mobile registration attempt', ['email' => $request->email]);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        Log::info('Validation passed');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Log::info('User created', ['user_id' => $user->id]);

        $verificationCode = sprintf('%04d', mt_rand(0, 9999));
        $user->verification_code = $verificationCode;
        $user->save();

        Log::info('Verification code set', ['code' => $verificationCode]);

        try {
            Mail::send('emails.verification-code', ['verificationCode' => $verificationCode], function($message) use ($user) {
                $message->to($user->email)
                        ->subject('Your Email Verification Code')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });
            Log::info('Verification code sent successfully');
        } catch (\Exception $e) {
            Log::error('Failed to send verification code', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully. Please check your email for the verification code.',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Verify user's email with verification code.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyEmailWithCode(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'verification_code' => 'required|string',
        ]);

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified'
            ], 422);
        }

        if ($user->verification_code !== $request->verification_code) {
            return response()->json([
                'message' => 'Invalid verification code'
            ], 422);
        }

        $user->markEmailAsVerified();
        $user->verification_code = null;
        $user->save();

        return response()->json([
            'message' => 'Email verified successfully',
            'user' => $user
        ], 200);
    }
}
