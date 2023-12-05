<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FragmentItemOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'data_type_id',
        'name',
        'value',
        'value_int',
        'value_float',
        'value_date',
        'value_datetime',
    ];

    //protected $table = 'fragment_item_options';

    public function items()
    {
        return $this->belongsTo(FragmentItem::class, 'item_id');
    }

    public function datatypes()
    {
        return $this->belongsTo(DataType::class, 'data_type_id', 'id', 'data_types');
    }

    public function getValueAttribute($value)
    {
        $typeCode = DataType::find($this->data_type_id);
        switch ($typeCode->code) {
            case 'int':
                $value = $this->value_int;
                break;

            case 'float':
                $value = $this->value_float;
                break;

            case 'date':
                $value = $this->value_date;
                break;

            case 'datetime':
                $value = $this->value_datetime;
                break;

            default:
                //
                break;
        }
        return $value;
    }
}
