<?php

namespace Elfcms\Elfcms\Aux;

use Elfcms\Elfcms\Models\User as ModelsUser;
use App\Models\User as SystemUser;
use Illuminate\Support\Facades\Auth;

class User
{

    public $user;

    public function __construct($user = null)
    {
        if (empty($user)) {
            $user = Auth::user();
        }
        if ($user instanceof SystemUser) {
            $this->user = $user;
        } elseif (gettype($user) == 'integer' || (intval($user) == $user)) {
            $this->user = ModelsUser::find($user);
        } elseif (gettype($user) == 'string') {
            $this->user = ModelsUser::where('email', $user)->first();
        } else {
            $this->user = Auth::user();
        }
    }

    public function name()
    {
        $name = $this->fullname();

        if (empty(trim($name))) {
            $name = $this->user->email;
        }

        return $name;
    }

    public function id()
    {
        $id = $this->user->id;

        return $id;
    }

    public function fullname()
    {
        return $this->user->data->first_name . ' ' . $this->user->data->second_name . ' ' . $this->user->data->last_name;
    }

    public function avatar($isThumbnail = false)
    {
        $avatar = null;
        if (($isThumbnail && !empty($this->user->data->thumbnail)) || (!$isThumbnail && empty($this->user->data->photo))) {
            if (!empty($this->user->data->thumbnail)) {
                $avatar = $this->user->data->thumbnail;
            }
        } elseif (($isThumbnail && empty($this->user->data->thumbnail)) || (!$isThumbnail && !empty($this->user->data->photo))) {
            if (!empty($this->user->data->photo)) {
                $avatar = $this->user->data->photo;
            }
        }
        return $avatar;
    }
}
