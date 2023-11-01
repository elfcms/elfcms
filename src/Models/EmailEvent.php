<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailEvent extends DefaultModel
{
    use HasFactory;

    public $emailFields = [
        'from',
        'to',
        'cc',
        'bcc'
    ];

    protected $fillable = [
        'code',
        'name',
        'subject',
        'description',
        'text',
        'contentparams',
        'view'
    ];

    protected $strings = [
        ['code' => 'userregisterconfirm', 'name' => 'User register confirm', 'description' => '', 'subject' => 'User register confirmation', 'content' => 'Please confirm your e-mail.', 'view' => 'elfcms::emails.events.register-confirm'],
        ['code' => 'passwordrecoveryrequest', 'name' => 'Password recovery request', 'description' => '', 'subject' => 'Password recovery request', 'content' => 'To recover your password, follow the link', 'view' => 'elfcms::emails.events.password-recovery-request'],
    ];

    protected $casts = [
        'contentparams' => 'array',
    ];

    public $text, $subject, $attatch, $attatchData, $params=[], $custom = [];

    public function eventAddresses()
    {
        return $this->belongsToMany(EmailEventAddress::class);
    }

    public function addresses()
    {
        return $this->belongsToMany(EmailAddress::class, 'email_event_addresses')->get();
    }

    public function fields()
    {
        $result = [];

        $emailFieldList = $this->hasMany(EmailEventAddress::class, 'email_event_id')->get();

        foreach ($emailFieldList as $field) {
            if (in_array($field->field, $this->emailFields)) {
                $result[$field->field] = $field->address()->first();
            }
        }

        return $result;
    }

    public function start($field = 'name')
    {
        return parent::start('name');
    }
}
