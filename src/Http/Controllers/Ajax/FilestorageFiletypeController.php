<?php

namespace Elfcms\Elfcms\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\DataType;
use Elfcms\Elfcms\Models\FilestorageFilegroup;
use Elfcms\Elfcms\Models\FilestorageFiletype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FilestorageFiletypeController extends Controller
{

    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request  $request, $byId = false)
    {
        $types = FilestorageFiletype::all();
        $groups = FilestorageFilegroup::all();
        if ($request->ajax()) {
            $typeData = $types->toArray();
            if ($byId) {
                $newData = [];
                foreach ($typeData as $type) {
                    $newData[$type['id']] = $type;
                }
                $typeData = $newData;
                unset($newData);
            }
            return [
                'result' => 'success',
                'message' => '',
                'data' => $typeData
            ];
        }
        else {
            return view(
                'elfcms::admin.filestorage.types.content.list',
                [
                    'types' => $types,
                    'groups' => $groups
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
            'id' => 'newtype',
            'name' => null,
            'code' => null,
            'group_id' => null,
            'description' => null,
            'mime_prefix' => null,
            'mime_type' => null,
        ];

        $groups = FilestorageFilegroup::all();
        return view(
            'elfcms::admin.filestorage.types.content.item',
            [
                'type' => (object)$emptyUnit,
                'groups' => $groups
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
        $typeDelete = [];

        if (!empty($request->type)) {
            foreach($request->type as $id => $type) {
                if (!empty($type['delete'])) {
                    $typeDelete[] = $id;
                    $except[] = 'type.'.$id;
                }
            }
        }

        if (!empty($request->newtype)) {
            foreach($request->newtype as $id => $newtype) {
                if (!empty($newtype['delete']) || (empty($newtype['name']) && empty($newtype['code']))) {
                    $except[] = 'newtype.'.$id;
                }
            }
        }
        $data = $request->except($except);

        $validator = Validator::make($data, [
            '*type.*.name' => 'required',
            '*type.*.code' => 'required',
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

        if (!empty($typeDelete)) {
            $deleted = FilestorageFiletype::destroy($typeDelete);
            if (!$deleted) {
                $result['message'] = __('elfcms::default.error_of_deleting');

                return $result;
            }
        }

        if (!empty($data['type'])) {
            foreach ($data['type'] as $id => $type) {
                $typeItem = FilestorageFiletype::find($id);
                if ($typeItem) {
                    $options = [];
                    if (!empty($type['options']) && is_array($type['options'])) {
                        foreach ($type['options'] as $option) {
                            if (!empty($option['delete']) || (empty($option['key']) && empty($option['value']))) {
                                continue;
                            }
                            $options[$option['key']] = $option['value'];
                        }
                    }
                    $typeItem->code = $type['code'];
                    $typeItem->name = $type['name'];
                    $typeItem->group_id = empty($type['group_id']) ? null : $type['group_id'];
                    $typeItem->description = $type['description'];
                    $typeItem->mime_prefix = $type['mime_prefix'];
                    $typeItem->mime_type = $type['mime_type'];
                    $saved = $typeItem->save();
                    if (!$saved) {
                        $result['message'] =  __('elfcms::default.error_of_saving_id',['id'=>$id]);

                        return $result;
                    }
                }
            }
        }

        if (!empty($data['newtype'])) {
            foreach ($data['newtype'] as $newtype) {
                $newtype['group_id'] = !empty($newtype['group_id']) ? $newtype['group_id'] : null;
                $created = FilestorageFiletype::create($newtype);
                if (!$created) {
                    $result['message'] = __('elfcms::default.error_of_creating_element_with_name',['name'=>$newtype['name']]);

                    return $result;
                }
            }
        }

        $types = FilestorageFiletype::all();
        $groups = FilestorageFilegroup::all();
        $view = view(
            'elfcms::admin.filestorage.types.content.list',
            [
                'types' => $types,
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
