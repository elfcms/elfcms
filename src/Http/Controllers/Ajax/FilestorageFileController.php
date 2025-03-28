<?php

namespace Elfcms\Elfcms\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Filestorage;
use Elfcms\Elfcms\Models\FilestorageFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FilestorageFileController extends Controller
{
    public function filestorageFileGroupSave(Request $request, Filestorage $filestorage)
    {
        $result = [
            'result' => 'success',
            'message' => __('filestorage::default.filestorage_edited_successfully'),
            'data' => null
        ];
        if (empty($request->file)) {
            $result['message'] = 'Error';
            $result['result'] = 'error';

            return $result;
        }
        $toUpdate = [];
        $toDelete = [];
        foreach ($request->file as $fileId => $fileData) {
            if (!empty($fileData['delete'])) {
                $toDelete[] = $fileId;
            }
            elseif (!empty($fileData['position'])) {
                /* $toUpdate[] = [
                    'id' => $fileId,
                    'filestorage_id' => $filestorage->id,
                    'position' => $fileData['position']
                ]; */
                FilestorageFile::where('id',$fileId)->where('storage_id',$filestorage->id)->update(['position'=>$fileData['position']]);
            }
        }
        if (!empty($toDelete)) {
            FilestorageFile::destroy($toDelete);
        }
        /* if (!empty($toUpdate)) {
            FilestorageFile::upsert(
                $toUpdate,
                ['id'],
                ['position']
            )->dd();
        } */
        return $result;//[$toUpdate,$toDelete];
    }
}
