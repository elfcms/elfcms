<?php

namespace Elfcms\Elfcms\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultModel extends Model
{

    public function start(string|null $field = 'code')
    {
        if (!empty($this->strings)) {
            foreach($this->strings as $string) {
                $exists = $this->where($field,$string[$field])->count();
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
}
