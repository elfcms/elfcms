<?php

namespace Elfcms\Elfcms\Http\Controllers\Ajax;

use Elfcms\Elfcms\Models\DataType;
use Illuminate\Http\Request;

class FragmentDataTypeController extends \App\Http\Controllers\Controller
{
    public function get(Request $request)
    {

        $result = [];
        if ($request->ajax()) {
            $data_types = DataType::all()->toArray();
            $filterData = ['bool','int','float','string','text','date','time','datetime','json'];

            $result = array_filter($data_types,function($item) use ($filterData) {
                return in_array($item['code'],$filterData);
            });

            $result = json_encode($result);
        }
        return $result;

    }

}
