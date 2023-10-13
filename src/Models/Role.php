<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name','code','description'];

    protected $strings = [
        ['name' => 'Administrator', 'code' => 'admin', 'description' => ''],
        ['name' => 'Registered users', 'code' => 'users', 'description' => ''],
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('user_id','role_id');
    }

    public function start()
    {
        foreach($this->strings as $string) {
            $exists = $this->where('code',$string['code'])->count();
            if ($exists && $exists > 0) {
                continue;
            }

            $newString = $this->create($string);

            if (!$newString) {
                return false;
            }
        }
    }

}
