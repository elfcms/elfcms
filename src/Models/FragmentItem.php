<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FragmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'image',
        'text',
    ];

    protected $appends = ['option_data'];

    public function getOptionDataAttribute()
    {
        $result = [];
        $options = $this->hasMany(FragmentItemOption::class, 'item_id')->get();
        foreach ($options as $option) {
            $value = $option->value;
            switch ($option->datatypes->code) {
                case 'int':
                    $value = $option->value_int;
                    break;

                case 'float':
                    $value = $option->value_float;
                    break;

                case 'date':
                    $value = $option->value_date;
                    break;

                case 'datetime':
                    $value = $option->value_datetime;
                    break;

                default:
                    //
                    break;
            }
            $result[$option->name] = $value;
        }
        return $result;
    }

    public function options()
    {
        return $this->hasMany(FragmentItemOption::class, 'item_id');
    }
}
