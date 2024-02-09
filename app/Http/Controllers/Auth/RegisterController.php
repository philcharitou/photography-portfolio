<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'user_id' => 'required|string|min:8|max:8|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'admin_check' => 'nullable',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data)
    {
        if (array_key_exists("admin_check", $data) && $data['admin_check'] == 'on') {
            return User::create([
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' =>  $data['last_name'],
                'company' =>  $data['company'],
                'address' =>  $data['address'],
                'phone' =>  $data['phone'],
                'password' => bcrypt($data['password']),
                'role' => 'admin',
                'blocked' => 0,
                'pass_reset' => 0,
            ]);
        } else {
            return User::create([
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' =>  $data['last_name'],
                'company' =>  $data['company'],
                'address' =>  $data['address'],
                'phone' =>  $data['phone'],
                'password' => bcrypt($data['password']),
                'blocked' => 0,
                'pass_reset' => 0,
            ]);
        }
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return view('auth.register')->with('userAdded', true);
    }
}
