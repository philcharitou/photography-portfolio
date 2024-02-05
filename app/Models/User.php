<?php

namespace App\Models;

use App\Mail\ResetPasswordMail;
use App\Mail\SendTwoFactorCode;
use App\Models\Ranges\Pol;
use App\Models\Settings\AllowedSettingValue;
use App\Models\Settings\Setting;
use App\Models\Settings\UserSetting;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $branch
 * @property string $company
 * @property string $department
 * @property string $email
 */

class User extends Authenticatable implements HasLocalePreference, Auditable
{
    use Notifiable, CanResetPassword, HasRoles, \OwenIt\Auditing\Auditable;

    protected $guard_name = 'web';

    /**
     * The channels the user receives notification broadcasts on.
     */
    public function receivesBroadcastNotificationsOn(): string
    {
        return 'users.'.$this->id;
    }

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_code', 'pin'
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'two_factor_expires_at',
        'two_factor_renew',
        'last_pass_reset',
    ];

    protected $fillable = [
        // Login Details
        'email',
        // Filtering Details
        'id_number',
        'company',
        'branch',
        'department',
        // Administrative Columns
        'notification_preference',
        'last_pass_reset',
        'last_login',
        'last_access',
        'type',
        // Last Resource Feature
        'last_resource',
        'last_resource_route',
        // 2FA
        'two_factor_renew',
        // Boolean(s)
        //'blocked',
        'is_verified',
        //'pass_reset',
        // Personal Detail(s)
        'first_name',
        'last_name',
        'image',
        'phone',
        'address',

//        'remember_token',
//        'two_factor_code',
    ];
    /* Helper Functions */

    /**
     * Generate a unique two-factor authentication code
     */
    public function generateTwoFactorCode()
    {
        $code = rand(100000, 999999);

        TwoFactorCode::updateOrCreate(
            ['user_id' => auth()->user()->id],
            [
                'code' => $code,
                'user_id' => auth()->user()->id,
                'expires_at' => now()->addMinutes(10)
            ]
        );

        Mail::to(auth()->user()->email)->send(new SendTwoFactorCode($code));
    }

    /**
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale()
    {
        return $this->locale;
    }

    /**
     * Regenerate two-factor code with new expiry date
     */
    public function resetTwoFactorCode()
    {
        TwoFactorCode::where('user_id', $this->id)->first()->delete();
    }

    /**
     * Generate new notification for a new user to reset password
     */
    public function sendNewUserNotification($token)
    {
        //$this->notify(new NewUserResetPasswordNotification($token));
    }

    /**
     * Generate new notification for resetting user password
     */
    public function sendPasswordResetNotification($token)
    {
        Mail::to($this->email)->send(new ResetPasswordMail([
            'token' => $token,
            'email' => $this->email,
        ]));

        // Old (default) laravel auth() process
        //$this->notify(new CustomResetPasswordNotification($token));
    }
}
