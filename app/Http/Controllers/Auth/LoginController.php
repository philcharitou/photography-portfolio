<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent as JesAgent;
use Laravel\Fortify\Http\Requests\LoginRequest;
use phpseclib3\System\SSH\Agent;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     */
    protected $redirectTo = '/dashboard';

    // Custom properties for throttle
    protected $maxAttempts = 5; // Default is 5
    protected $decayMinutes = 60; // Default is 1

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $remember_me = $request->has('remember_me');

        if (Auth::attempt($credentials, $remember_me)) {
            // Authentication passed...
            return redirect()->intended('dashboard');
        }
    }

    public function authenticated(Request $request, $user)
    {
        $session_collection = DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', Auth::user()->getAuthIdentifier())
            ->orderBy('last_activity', 'desc')
            ->get();

        if($session_collection->count() > 2) {
            Auth::logoutOtherDevices($request->password);
        }

        App::setLocale(auth()->user()->setting('language_preference'));
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }

    /**
     * Create a new agent instance from the given session.
     *
     * @param  mixed  $session
     * @return mixed
     */
    protected function createAgent($session)
    {
        return tap(new JesAgent, function ($agent) use ($session) {
            $agent->setUserAgent($session->user_agent);
        });
    }
}