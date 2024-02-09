<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TwoFactorCode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Two Factor Authentication Controller
    |--------------------------------------------------------------------------
    |
    */

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        return view('auth.two_factor');
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'integer|required',
        ]);

        $user = auth()->user();
        $code = TwoFactorCode::where('user_id', auth()->user()->id)->first();

        if(isset($code)) {
            if(Carbon::parse($code->expires_at) > Carbon::now()->toDateTimeString()) {
                if($code->code == $request->two_factor_code) {
                    $user->update([
                        'is_verified' => true,
                        'two_factor_renew' => now()->addWeeks(2)
                    ]);
                    $code->delete();

                    return redirect()->route('dashboard')->with('verified', '');
                } else {
                    return redirect()->back()->with('swal_invalid_code', '');
                }
            } else {
                $code->delete();
                return redirect()->back()->with('swal_code_expired', '');
            }

            return redirect()->back()->with('swal_invalid_code', '');
        }

    }

    public function resend()
    {
        auth()->user()->generateTwoFactorCode();

        return redirect()->back()->with('swal_email_sent', '');
    }
}