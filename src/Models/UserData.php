<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserData extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'second_name',
        'last_name',
        'zip_code',
        'country',
        'city',
        'street',
        'house',
        'full_address',
        'phone_code',
        'phone_number',
        'photo',
        'thumbnail'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function form_of_address()
    {
        return $this->belongsTo(UserAddressForm::class,'address_form_id');
    }

    public function gender()
    {
        return $this->belongsTo(UserGenderForm::class,'gender_form_id');
    }
}
