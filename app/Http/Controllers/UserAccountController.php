<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Mail\NewUserMail;
use App\Models\Contact;
use App\Models\Ranges\Department;
use App\Models\Ranges\Pol;
use App\Models\Settings\AllowedSettingValue;
use App\Models\Settings\Setting;
use App\Models\Settings\UserSetting;
use App\Models\TwoFactorCode;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserAccountController extends Controller
{

    /**
     * Create a new controller instance.
     * Methods within this instance can only be accessed by users who are:
     * A) authenticated,
     * B) have the role "super_admin"
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a paginated list of users.
     *
     * @return mixed
     */
    public function index()
    {
        // here
    }

    /**
     * Mark selected user notification as read
     *
     * @return RedirectResponse
     */
    public function markNotification($id)
    {
        DatabaseNotification::find($id)->markAsRead();
    }

    /**
     * Display a list of notifications.
     *
     * @return Application|Factory|View
     */
    public function seeNotifications() {

        foreach(auth()->user()->unreadNotifications as $notification) {
            $notification->markAsRead();
        }

        return view('users.notifications');
    }

    /**
     * Display form for creating a new user.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        // here
    }

    /**
     * Store a newly created user in database.
     *
     * @return RedirectResponse
     */
    public function store(UserStoreRequest $request)
    {
        // here
    }

    /**
     * Display the selected user.
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the selected user.
     *
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        // here
    }

    /**
     * Update the selected user in database.
     *
     * @return RedirectResponse
     */
    public function update(UserStoreRequest $request, $id)
    {
        // here
    }

    /**
     * Delete the selected user.
     *
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        // here
    }

    /**
     * Require password reset for the selected user.
     *
     * @return RedirectResponse
     */
    public function reset_password($id)
    {
        if(auth()->user()->hasRole('super_admin')) {
            $user = User::find($id);
            $user->update(['pass_reset' => 1]);

            // Send email for password reset
            $token = Password::getRepository()->create($user);
            $user->sendPasswordResetNotification($token);

            return redirect()->route('users.index')
                ->with('password_reset', '');
        } else {
            return redirect()->back()->with('denied', '');
        }
    }
}
