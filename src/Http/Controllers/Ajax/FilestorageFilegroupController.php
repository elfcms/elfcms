<?php

namespace Elfcms\Elfcms\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\DataType;
use Elfcms\Elfcms\Models\FilestorageFilegroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FilestorageFilegroupController extends Controller
{

    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request  $request, $byId = false)
    {
        $groups = FilestorageFilegroup::all();
        if ($request->ajax()) {
            $groupData = $groups->toArray();
            if ($byId) {
                $newData = [];
                foreach ($groupData as $group) {
                    $newData[$group['id']] = $group;
                }
                $groupData = $newData;
                unset($newData);
            }
            return [
                'result' => 'success',
                'message' => '',
                'data' => $groupData
            ];
        }
        else {
            return view(
                'elfcms::admin.filestorage.groups.content.list',
                [
                    'groups' => $groups,
                ]
            );
        }
    }

    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emptyItem(Request  $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $emptyUnit = [
            'id' => 'newgroup',
            'name' => null,
            'code' => null,
            'description' => null,
            'mime_prefix' => null,
        ];
        return view(
            'elfcms::admin.filestorage.groups.content.item',
            [
                'group' => (object)$emptyUnit,
            ]
        );
    }

    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request  $request)
    {
        $result = [
            'result' => 'error',
            'message' => '',
            'data' => null
        ];
        if (!$request->isMethod('POST')) {
            $result['message'] = __('elfcms::default.method_must_be',['method'=>'POST']);
            return $result;
        }

        $except = [];
        $groupDelete = [];

        if (!empty($request->group)) {
            foreach($request->group as $id => $group) {
                if (!empty($group['delete'])) {
                    $groupDelete[] = $id;
                    $except[] = 'group.'.$id;
                }
            }
        }

        if (!empty($request->newgroup)) {
            foreach($request->newgroup as $id => $newgroup) {
                if (!empty($newgroup['delete']) || (empty($newgroup['name']) && empty($newgroup['code']))) {
                    $except[] = 'newgroup.'.$id;
                }
            }
        }
        $data = $request->except($except);

        $validator = Validator::make($data, [
            '*group.*.name' => 'required',
            '*group.*.code' => 'required',
        ]);

        if ($validator->fails()) {
            $errorsData = $validator->errors()->messages();
            $errorsMessages = [];
            foreach ($errorsData as $message) {
                $errorsMessages[] = $message[0];
            }
            $result['message'] = implode('. ', $errorsMessages);

            return $result;
        }

        if (!empty($groupDelete)) {
            $deleted = FilestorageFilegroup::destroy($groupDelete);
            if (!$deleted) {
                $result['message'] = __('elfcms::default.error_of_deleting');

                return $result;
            }
        }

        if (!empty($data['group'])) {
            foreach ($data['group'] as $id => $group) {
                $groupItem = FilestorageFilegroup::find($id);
                if ($groupItem) {
                    $options = [];
                    if (!empty($group['options']) && is_array($group['options'])) {
                        foreach ($group['options'] as $option) {
                            if (!empty($option['delete']) || (empty($option['key']) && empty($option['value']))) {
                                continue;
                            }
                            $options[$option['key']] = $option['value'];
                        }
                    }
                    $groupItem->code = $group['code'];
                    $groupItem->name = $group['name'];
                    $groupItem->description = $group['description'];
                    $groupItem->mime_prefix = $group['mime_prefix'];
                    $saved = $groupItem->save();
                    if (!$saved) {
                        $result['message'] =  __('elfcms::default.error_of_saving_id',['id'=>$id]);

                        return $result;
                    }
                }
            }
        }

        if (!empty($data['newgroup'])) {
            foreach ($data['newgroup'] as $newgroup) {
                /* $options = [];
                if (!empty($newgroup['options']) && is_array($newgroup['options'])) {
                    foreach ($newgroup['options'] as $option) {
                        if (!empty($option['delete']) || (empty($option['key']) && empty($option['value']))) {
                            continue;
                        }
                        $options[$option['key']] = $option['value'];
                    }
                    $newgroup['options'] = json_encode($options);
                } */
                $created = FilestorageFilegroup::create($newgroup);
                if (!$created) {
                    $result['message'] = __('elfcms::default.error_of_creating_element_with_name',['name'=>$newgroup['name']]);

                    return $result;
                }
            }
        }

        $groups = FilestorageFilegroup::all();
        $view = view(
            'elfcms::admin.filestorage.groups.content.list',
            [
                'groups' => $groups,
            ]
        )->render();
        if (!$view) {
            $result['message'] = __('elfcms::default.view_not_found');
            return $result;
        }
        $result['result'] = 'success';
        $result['message'] = __('elfcms::default.data_saved_successfully');
        $result['data'] = $view;

        return $result;
    }
}
