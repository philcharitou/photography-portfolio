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

class UserController extends Controller
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
        if(auth()->user()->hasRole('super_admin')) {
            $users = User::paginate(25);

            return view('users.index')
                ->with('users', $users);
        } else {
            return redirect()->back()->with('denied', '');
        }
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
        if(auth()->user()->hasRole('super_admin')) {
            $user_id = User::orderBy('id_number', 'DESC')->first();
            $roles = Role::where('name', '!=', "super_admin")->pluck('name');
            $companies = Contact::where('type', 'internal')->pluck('company_name')->toArray();
            $departments = Department::pluck('name');
            $branches = ['north_america', 'europe', 'asia'];

            //Set new user number with leading zeroes or default to "00000"
            if (isset($user_id)) {
                $new_user_id = str_pad($user_id->id + 1, 5, "0", STR_PAD_LEFT);
            } else {
                $new_user_id = '00003';
            }

            return view('users.create')
                ->with('new_user_id', $new_user_id)
                ->with('departments', $departments)
                ->with('companies', $companies)
                ->with('branches', $branches)
                ->with('roles', $roles);
        } else {
            return redirect()->back()->with('denied', '');
        }
    }

    /**
     * Store a newly created user in database.
     *
     * @return RedirectResponse
     */
    public function store(UserStoreRequest $request)
    {
        if(auth()->user()->hasRole('super_admin')) {
            $rand_pass = $this->random_str(10);

            if(strtolower($request->company) == "oaple forest products ltd.") {
                $company = "OP";
            } else {
                $company = "ML";
            }

            $user = User::create([
                // Login Details
                'email' => $request->email,
                'password' => bcrypt($rand_pass),
                // Identification Detail(s)
                'id_number' => $request->id_number,
                'company' => $company,
                'branch' => strtolower(str_replace(" ", "_", $request->branch)),
                'department' => strtolower(str_replace(" ", "_", $request->department)),
                // Personal Details
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'image' => $request->file('image') ? $request->file('image')->store('images/users' . $request->id_number, 's3') : null,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // Handle roles
            if(!$user->hasRole('super_admin')) {
                if ($request->has('role') && strtolower($request->role) != "super_admin" && in_array(strtolower($request->role), Role::all()->pluck('name')->toArray())) {
                    $user->syncRoles([strtolower($request->role)]);
                }
            }

            if($request->ports) {
                $ports = array_map('trim', array_map('strtoupper', preg_split('/[,()\/-]+/', $request->ports)));

                foreach($ports as $name) {
                    $pol = Pol::where('name', $name)->first();

                    if($pol) { $user->pols()->attach($pol); }
                }
            }

            $user->save();

            // Send email for password reset
            $token = Password::getRepository()->create($user);
            Mail::to($user->email)->send(new NewUserMail([
                'token' => $token,
                'password' => $rand_pass,
                'email' => $user->email,
                'name' => $user->first_name
            ]));

            return redirect()->route('users.index')
                ->with('create', '');
        } else {
            return redirect()->back()->with('denied', '');
        }
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
        $user = User::find($id);
        $roles = Role::where('name', '!=', "super_admin")->pluck('name');
        $companies = Contact::where('type', 'internal')->pluck('abbreviation')->toArray();
        $departments = Department::pluck('name');
        $branches = ['north_america', 'europe', 'asia'];

        $user->load('user_settings');

        return view('users.edit')
            ->with('user', $user)
            ->with('departments', $departments)
            ->with('companies', $companies)
            ->with('branches', $branches)
            ->with('roles', $roles);
    }

    /**
     * Update the selected user in database.
     *
     * @return RedirectResponse
     */
    public function update(UserStoreRequest $request, $id)
    {
        if(auth()->user()->hasRole('super_admin')) {
            $validatedData = $request->validated();
            $user = User::find($id);

            if($validatedData['pin'] && strlen($validatedData['pin']) == 4) {
                $user->pin = $validatedData['pin'];
            } else {
                unset($validatedData['pin']);
            }

            if(str_replace(" ", "_", $validatedData['company']) == "oaple_forest_products_ltd") { $validatedData['company'] = "OP"; }
            if(str_replace(" ", "_", $validatedData['company']) == "max_leap_international_limited") { $validatedData['company'] = "ML"; }

            $user->update($validatedData);

            // Handle User Settings
            foreach($request->settings as $setting => $value) {

                $setting_object = Setting::where('description', $setting)->first();

                if($setting_object) {
                    if($setting_object->constrained) {
                        $setting_value = AllowedSettingValue::where('setting_id', $setting_object->id)
                            ->where('caption', $value)->first();

                        if($setting_value) {
                            UserSetting::updateOrCreate([
                                'user_id' => $user->id,
                                'setting_id' => $setting_object->id,
                            ],
                                [
                                    'allowed_setting_value_id' => $setting_value->id,
                                ]);
                        }
                    } else {
                        if($setting == "background") {
                            $value = $value->store('images/users/backgrounds/' . $user->id_number, 's3');
                        }

                        UserSetting::updateOrCreate([
                            'user_id' => $user->id,
                            'setting_id' => $setting_object->id,
                        ],
                            [
                                'unconstrained_value' => $value,
                            ]);
                    }
                }
            }

            // Handle file request
            if($request->image) {
                $user->image = $request->file('image')->store('images/users/' . $user->id_number, 's3');
            }

            // Handle roles
            if(!$user->hasRole('super_admin')) {
                if ($request->has('role') && $request->role != "super_admin" && in_array($request->role, Role::all()->pluck('name')->toArray())) {
                    $user->syncRoles($request->role);
                }
            }

            // Handle ports relationship
            if($request->ports) {
                $ports = array_map('trim', array_map('strtoupper', preg_split('/[,()\/-]+/', $request->ports)));

                foreach($ports as $name) {
                    $pol = Pol::where('name', $name)->first();

                    if($pol) { $user->pols()->attach($pol); }
                }
            }

            // Handle PIN if changed
            if(array_key_exists('pin', $validatedData)) {
                $user->pin = Hash::make($validatedData['pin']);
                $user->default_pin = (string) $validatedData['pin'] === (string) config('requirepin.default', '0000');
            }

            $user->save();

            return redirect()->back()
                ->with('swal_update', '');
        } else {
            $validatedData = $request->validated();
            $user = User::find($id);

            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // Handle User Settings
            foreach($request->settings as $setting => $value) {

                $setting_object = Setting::where('description', $setting)->first();

                if($setting_object) {
                    if($setting_object->constrained) {
                        $setting_value = AllowedSettingValue::where('setting_id', $setting_object->id)
                            ->where('caption', $value)->first();

                        if($setting_value) {
                            UserSetting::updateOrCreate([
                                'user_id' => $user->id,
                                'setting_id' => $setting_object->id,
                            ],
                                [
                                    'allowed_setting_value_id' => $setting_value->id,
                                ]);
                        }
                    } else {
                        if($setting == "background") {
                            $value = $value->store('images/users/backgrounds/' . $user->id_number, 's3');
                        }

                        UserSetting::updateOrCreate([
                            'user_id' => $user->id,
                            'setting_id' => $setting_object->id,
                        ],
                            [
                                'unconstrained_value' => $value,
                            ]);
                    }
                }
            }

            // Handle file request
            if($request->image) {
                $user->image = $request->file('image')->store('images/users/' . $user->id_number, 's3');
            }

            // Handle PIN if changed
            if(array_key_exists('pin', $validatedData)) {
                $user->pin = Hash::make($validatedData['pin']);
                $user->default_pin = (string) $validatedData['pin'] === (string) config('requirepin.default', '0000');
            }

            $user->save();

            return redirect()->back()
                ->with('swal_update', '');
        }
    }

    /**
     * Delete the selected user.
     *
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        if(auth()->user()->hasRole('super_admin')) {
            User::where('id', $id)->delete();

            return redirect()->route('users.index')
                ->with('delete', '');
        } else {
            return redirect()->back()->with('denied', '');
        }
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

    /**
     * Block the selected user.
     *
     * @return RedirectResponse
     */
    public function block($id)
    {
        if(auth()->user()->hasRole('super_admin')) {
            User::where('id', $id)->update(['blocked' => true]);

            return redirect()->route('users.index')
                ->with('blocked', '');
        } else {
            return redirect()->back()->with('denied', '');
        }
    }

    /**
     * Unblock the selected user.
     *
     * @return RedirectResponse
     */
    public function unblock($id)
    {
        if(auth()->user()->hasRole('super_admin')) {
            User::where('id', $id)->update(['blocked' => 0]);

            return redirect()->route('users.index')
                ->with('blocked', '');
        } else {
            return redirect()->back()->with('denied', '');
        }
    }

    /**
     * Generate a random string.
     *
     * @return string
     * @throws Exception
     */
    function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;

        if ($max < 1) {
            throw new Exception('$keyspace must be at least two characters long');
        }

        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }

        return $str;
    }
}
