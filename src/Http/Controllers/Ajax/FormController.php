<?php

namespace Elfcms\Elfcms\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    /**
     * Update positions for form groups.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Elfcms\Elfcms\Models\Form  $form
     * @return bool
     */
    public function groupOrder(Request $request, Form $form)
    {
        if (!$request->ajax()) abort(403);

        $result = [
            'result' => 'error',
            'message' => '',
        ];

        $data = $request->all();

        if (!empty($data['formId']) && $data['formId'] != $form->id) {
            $result['message'] = __('elfcms::default.error_saving_data');
        }

        if (empty($data['groups'])) {
            $result['message'] = __('elfcms::default.error_saving_data');
        }

        //$groups = $form->groups;
        if (!empty($form->groups)) {
            foreach ($form->groups as $group) {
                if (!empty($data['groups'][$group->id])) {
                    $group->position = $data['groups'][$group->id];
                    $group->save();
                }
            }
        }

        $result['message'] = __('elfcms::default.changes_saved');
        $result['result'] = 'success';

        return $result;

    }

    /**
     * Update positions for form fields.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Elfcms\Elfcms\Models\Form  $form
     * @return bool
     */
    public function fieldOrder(Request $request, Form $form)
    {
        if (!$request->ajax()) abort(403);

        $result = [
            'result' => 'error',
            'message' => '',
        ];

        $data = $request->all();

        if (!empty($data['formId']) && $data['formId'] != $form->id) {
            $result['message'] = __('elfcms::default.error_saving_data');
        }

        if (empty($data['fields'])) {
            $result['message'] = __('elfcms::default.error_saving_data');
        }

        if (!empty($form->fields)) {
            foreach ($form->fields as $field) {
                if (!empty($data['fields'][$field->id] && $data['fields'][$field->id]['new'] == 1)) {
                    $field->update([
                        'position' => $data['fields'][$field->id]['position'],
                        'group_id' =>  $data['fields'][$field->id]['group']
                    ]);
                }
            }
        }

        $result['message'] = __('elfcms::default.changes_saved');
        $result['result'] = 'success';

        return $result;

    }
}
