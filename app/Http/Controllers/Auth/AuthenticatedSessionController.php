<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Services\WhatsAppAutomation\ReactTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        if ($request->has('token')) {
            $accessToken = $request->token;
            $token = PersonalAccessToken::findToken($accessToken);

            if ($token) {
                Auth::login($token->tokenable); // Log in the user
                return redirect()->route('admin.index');
            }
        }
        
        return view('admin.sign-in');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        // return redirect()->intended(RouteServiceProvider::HOME);
        if (Auth::user()->type == 'superadmin' || Auth::user()->type == 'supersatff') {
            return redirect()->route('superadmin.index');
        } else {
            return redirect()->route('admin.index');
        }
    }

    /**
     * Destroy an authenticated session.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        $sessionId = (string) $request->session()->getId();
        if ($user->type == 'superstaff' && !is_null($user->store_id)) {
            $user->store_id = NULL;
            $user->save();

            return redirect()->back()->with("success", "You have successfully exits from store.");
        }

        if ($user) {
            app(ReactTokenService::class)->revokeAdminSession((int) $user->id, $sessionId);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }


    public function destroy2(Request $request)
    {
        $user = Auth::user();
        $sessionId = (string) $request->session()->getId();

        if ($user) {
            app(ReactTokenService::class)->revokeAdminSession((int) $user->id, $sessionId);
        }

        Auth::logout();
        return redirect('/');
    }

}
