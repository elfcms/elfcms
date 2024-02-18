<?php

namespace Elfcms\Elfcms\Aux;

use Elfcms\Elfcms\Models\FragmentItem;

class Fragment
{

    public static function get(string|int $fragment)
    {
        if (is_numeric($fragment)) {
            return FragmentItem::find($fragment);
        }
        else {
            return FragmentItem::where('code',$fragment)->first();
        }

        return false;
    }
}
