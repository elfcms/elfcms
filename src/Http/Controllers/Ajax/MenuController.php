<?php

namespace Elfcms\Elfcms\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Update positions for menu items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Elfcms\Elfcms\Models\Menu  $menu
     * @return bool
     */
    public function itemOrder(Request $request, Menu $menu)
    {
        if (!$request->ajax()) abort(403);

        $result = [
            'result' => 'error',
            'message' => '',
        ];

        $data = $request->all();

        if (!empty($data['menuId']) && $data['menuId'] != $menu->id) {
            $result['message'] = __('elfcms::default.error_saving_data');
        }

        if (empty($data['items'])) {
            $result['message'] = __('elfcms::default.error_saving_data');
        }

        //$items = $menu->items;
        if (!empty($menu->items)) {
            foreach ($menu->items as $item) {
                if (!empty($data['items'][$item->id])) {
                    $item->position = $data['items'][$item->id]['position'];
                    $item->parent_id = $data['items'][$item->id]['parent'];
                    $item->save();
                }
            }
        }

        $result['message'] = __('elfcms::default.changes_saved');
        $result['result'] = 'success';

        return $result;

    }

}
