<?php

namespace Elfcms\Elfcms\View\Components\Input;

use Elfcms\Elfcms\Models\FilestorageFile;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class FSFile extends Component
{
    //public $inputData, $value, $code, $accept, $template, $boxId, $jsName, $download, $extension, $mime, $icon, $file;

    public $template, $file, $name, $id, $download, $boxId, $value, $accept, $height, $width;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    //public function __construct($inputData = [], $value = null, $code = null, $accept = null, $template='default', $download = false, filestorageFile $file = null)
    public function __construct(filestorageFile|null $file = null, $name = null, $id = null, $download = false, $accept = null, $template = 'default', $width = null, $height = null, $value = null)
    {
        $width = intval($width);
        $height = intval($height);
        $this->boxId = uniqid();
        $this->file = $file;
        $this->name = $name ?? $this->boxId . '_file';
        $this->id = $id ?? $this->boxId . '_file';
        $this->download = $download ? true : false;
        $this->template = $template;
        $this->value = $file ? $file->id : $value;
        $this->accept = $accept;
        $this->height = (!empty($height) && $height > 50) ? $height : 150;
        $this->width = !empty($width && $width > 100) ? $width : 150;
    }
    /* {
        if (empty($inputData)) {
            $inputData = [
                'code' => null,
                'value' => null,
                'extension' => null,
                'mime' => null,
            ];
        }
        $extension = $inputData['extension'] ?? fsExtension($inputData['value']) ?? fsExtension($value) ?? null;
        $mime = $inputData['mime'] ?? mime_content_type(file_path($inputData['value'],true,'filestorage')) ?? mime_content_type(file_path($value),true,'filestorage') ?? null;
        //$icon = fsIcon($extension);
        //$icon = route('files.preview', ['file' => $inputData['value']]);
        $this->inputData = $inputData;
        $this->value = $value ?? $inputData['value'];
        $this->code = $code ?? $inputData['code'];
        $this->accept = $accept;
        $this->template = $template;
        $this->download = $download;
        $this->extension = $extension;
        $this->mime = $mime;
        $this->icon = route('files.preview', ['file' => $this->value]);
        if (empty($file?->id)) {
            $file = FilestorageFile::find($this->value) ?? null;
        }
        else {
            $file = null;
        }
        $this->file = $file;
        $this->boxId = uniqid();
        $this->jsName = Str::camel(str_replace(']','',str_replace('[','_',$this->code)));
    } */

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (View::exists('components.input.fsfile.' . $this->template)) {
            return view('components.input.fsfile.' . $this->template);
        }
        if (View::exists('elfcms.components.input.fsfile.' . $this->template)) {
            return view('elfcms.components.input.fsfile.' . $this->template);
        }
        if (View::exists('elfcms::components.input.fsfile.' . $this->template)) {
            return view('elfcms::components.input.fsfile.' . $this->template);
        }
        if (View::exists($this->template)) {
            return view($this->template);
        }
        return null;
    }
}
