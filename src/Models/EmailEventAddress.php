<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailEventAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'field',
        'email_event_id',
        'email_address_id'
    ];

    public function address()
    {
        return $this->belongsTo(EmailAddress::class, 'email_address_id');
    }
}
