<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElfcmsContact extends DefaultModel
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'elfcms_contacts';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'params',
        'value',
        'description_code',
    ];

    protected $strings = [
        ['code' => 'firm_name', 'name' => 'Firm name', 'params' => '{"type": "text"}'],
        ['code' => 'address', 'name' => 'Address', 'params' => '{"type": "text"}'],
        ['code' => 'email', 'name' => 'E-Mail', 'params' => '{"type": "text"}'],
        ['code' => 'phone', 'name' => 'Phone', 'params' => '{"type": "text"}'],
        ['code' => 'tax_number', 'name' => 'Tax number', 'params' => '{"type": "text"}'],
    ];

    public static function value($code)
    {
        return self::where('code',$code)->first()->value ?? null;
    }

    public static function get()
    {
        $all = self::all();
        $result = [];
        foreach ($all as $item) {
            $result[$item['code']] = $item;
        }

        return $result;
    }

    public static function values()
    {
        $all = self::all();
        $result = [];
        foreach ($all as $item) {
            $result[str_ireplace('site_','',$item['code'])] = $item->value;
        }

        return $result;
    }


}
