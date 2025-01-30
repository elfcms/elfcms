<?php

namespace Elfcms\Elfcms\Http\Requests\Admin;

use Elfcms\Elfcms\Models\Filestorage;
use Elfcms\Elfcms\Models\FilestorageFile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FilestorageFileStorRequest extends FormRequest
{
    private $file, $ulid, $filePath;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => __('elfcms::default.name'),
            'file' => __('elfcms::default.file'),
            'alt_text' => __('elfcms::default.alt_text'),
            'link_title' => __('elfcms::default.link_title'),
            'description' => __('elfcms::default.description'),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'file' => 'required|file|max:' . (config('elfcms.filestorage.max_size') ? (config('elfcms.filestorage.max_size') / 1024) : 1024 * 10),
        ];
    }

    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    protected function prepareForValidation()
    {
        $this->ulid = strtolower(Str::ulid());
        $this->file = $this->file('file');
        $this->filePath = null;
        $groupId = null;
        $storage = Filestorage::find($this->storage_id ?? $this->filestorage);
        if ($storage) {
            $groupId = $storage->group_id;
        }
        if ($storage) {
            $this->filePath = '/' . $storage->code;
        }

        /* Log::debug('FilestorageFileStorRequest', [
            'ulid' => $this->ulid,
            'file' => $this->file,
            'filePath' => $this->filePath,
            'groupId' => $groupId,
        ]); */
        $this->merge([
            'id' => $this->ulid,
            'path' => $this->file ? file_path($this->ulid . '.' . $this->file->extension(),false,'filestorage') : null,
            'name' => $this->file?->getClientOriginalName() ?? $this->ulid,
            'size' => $this->file?->getSize(),
            'mimetype' => $this->file?->getMimeType(),
            'extension' => $this->file?->extension(),
            'storage_id' => $this->storage_id ?? $this->filestorage ?? null,
            'group_id' => $this->group_id ?? $groupId,
        ]);
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        $storedFile = $this->file->storeAs($this->filePath, $this->ulid . '.' . $this->file->extension(), 'filestorage');
        /* Log::debug('FilestorageFileStorRequest', [
            'storedFile' => $this->file,
        ]); */
        if (str_starts_with($this->file?->getMimeType(), 'image/')) {
            $imageSize = getimagesize($this->file);
            if ($imageSize) {
                $this->merge([
                    'width' => $imageSize[0],
                    'height' => $imageSize[1],
                ]);
            }
        }
        $maxPosition = FilestorageFile::where('storage_id', $this->storage_id)->max('position');
        $position = empty($maxPosition) && $maxPosition !== 0 ? 0 : $maxPosition + 1;
        $this->merge([
            'path' => $storedFile,
            'position' => $position,
        ]);
    }

    /**
     * Get the validated data from the request.
     *
     * @param  array|int|string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function validated($key = null, $default = null)
    {
        if ($this->ajax()) {
            $instance = $this->getValidatorInstance();
            if ($instance->fails()) {
                $errorText = '';
                foreach ($instance->errors()->toArray() as $k => $text) {
                    $errorText .= implode('; ', $text);
                }
                throw new HttpResponseException(response()->json(['result' => 'error', 'message' => $errorText], 422));
            }
        } else {
            parent::validated($key, $default);
        }
    }
}
