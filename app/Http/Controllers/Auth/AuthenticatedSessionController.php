<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * This method returns the view for the login page.
     *
     * @return View The login view
     *
     * @apiSuccess {HTML} view The rendered login view
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * This method authenticates the user, regenerates the session,
     * and redirects to the intended location or home.
     *
     * @param LoginRequest $request The incoming login request
     *
     * @throws \Illuminate\Validation\ValidationException When validation fails
     *
     * @return RedirectResponse
     *
     * @apiSuccess {Redirect} redirect Redirects to the intended location or home
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * This method logs out the user, invalidates the session,
     * regenerates the CSRF token, and redirects to the home page.
     *
     * @param Request $request The incoming HTTP request
     *
     * @return RedirectResponse
     *
     * @apiSuccess {Redirect} redirect Redirects to the home page
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Handle a mobile login request to the application.
     *
     * This method authenticates a user based on their email and password.
     * If successful, it returns the user data and a new API token.
     *
     * @param Request $request The incoming HTTP request
     * 
     * @throws \Illuminate\Validation\ValidationException When validation fails
     * 
     * @return JsonResponse
     *
     * @apiSuccess {Object} user The authenticated user's data
     * @apiSuccess {string} token The newly created API token for the user
     *
     * @apiError {String} message Error message when authentication fails
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 401 Unauthorized
     *     {
     *       "message": "Invalid credentials"
     *     }
     */
    public function mobileLogin(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('auth-token')->plainTextToken;

            $nameParts = explode(' ', $user->name, 2);
            $responseUser = $user->toArray();
            $responseUser['firstName'] = $nameParts[0];
            $responseUser['lastName'] = $nameParts[1] ?? '';

            unset($responseUser['name']);

            return response()->json([
                'user' => $responseUser,
                'token' => $token,
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
