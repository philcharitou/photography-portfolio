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

class AccountDeletionController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function view(Request $request)
    {
        return (new \Statamic\View\View)
            ->template('authentication/request-account-deletion')
            ->layout('layout');
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

        // App::setLocale(auth()->user()->setting('language_preference'));
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
