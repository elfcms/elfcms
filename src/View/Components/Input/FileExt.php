<?php

namespace Elfcms\Elfcms\View\Components\Input;

use Elfcms\Elfcms\Models\FilestorageFile;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class FileExt extends Component
{
    public $inputData, $value, $code, $accept, $template, $boxId, $jsName, $download, $extension, $mime, $icon, $file;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($inputData = [], $value = null, $code = null, $accept = null, $template='default', $download = false, filestorageFile $file = null)
    {
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
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (View::exists('components.input.fileext.' . $this->template)) {
            return view('components.input.fileext.' . $this->template);
        }
        if (View::exists('elfcms.components.input.fileext.'.$this->template)) {
            return view('elfcms.components.input.fileext.'.$this->template);
        }
        if (View::exists('elfcms::components.input.fileext.'.$this->template)) {
            return view('elfcms::components.input.fileext.'.$this->template);
        }
        if (View::exists($this->template)) {
            return view($this->template);
        }
        return null;
    }
}
