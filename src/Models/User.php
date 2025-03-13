<?php

namespace Elfcms\Elfcms\Models;

use App\Models\User as ModelsUser;
use Elfcms\Elfcms\Events\SomeMailEvent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends ModelsUser
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function data()
    {
        return $this->hasOne(UserData::class);
    }

    public function setConfirmationToken()
    {
        $token = Str::random(32);

        $this->confirm_token = $token;
        $this->confirm_token_at = now();
        $this->save();

        return $this;
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function scopeActive($query)
    {
        return $query->where('is_confirmed',1);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withPivot('user_id','role_id');
    }

    public function assignRole(Role $role)
    {
        return $this->roles()->save($role);
    }

    public function isConfirmed()
    {
        return !! $this->is_confirmed;
    }

    public function fullname()
    {
        if (empty($this->data)) {
            return null;
        }
        $nameArray = [];
        if (!empty($this->data->first_name)) {
            $nameArray[] = trim($this->data->first_name);
        }
        if (!empty($this->data->second_name)) {
            $nameArray[] = trim($this->data->second_name);
        }
        if (!empty($this->data->last_name)) {
            $nameArray[] = trim($this->data->last_name);
        }
        return implode(' ', $nameArray);
    }

    public function name($emailname = false)
    {
        $name = $this->fullname();

        if (empty(trim($name ?? ' '))) {
            $name = $this->email;
            if ($emailname) {
                $name = stristr($name,'@',true);
            }
        }

        return $name;
    }

    protected function avatar(): Attribute
    {
        $avatar = $this->data->photo ?? $this->data->thumbnail ?? null;
        return Attribute::make(
            get: fn () => $avatar,
        );
    }

    public function isAdmin()
    {
        $result = false;
        $roleCode = Config::get('elfcms.elfcms.user_admin_role');
        if (empty($roleCode)) {
            $roleCode = 'admin';
        }
        if (!empty($this->roles)) {
            foreach ($this->roles as $role) {
                if ($role->code == $roleCode) {
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }

}
