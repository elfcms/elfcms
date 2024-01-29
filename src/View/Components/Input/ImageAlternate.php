<?php

namespace Elfcms\Elfcms\View\Components\Input;

use Illuminate\Support\Facades\View;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class ImageAlternate extends Component
{
    public $valueName, $inputName, $value, $valueId, $accept, $template, $boxId, $jsName, $download;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($inputName = null, $value = null, $valueName = null, $valueId = null, $accept = null, $template='default', $download = false)
    {
        $this->inputName = $inputName;
        $this->valueName = $valueName;
        $this->value = $value;
        $this->valueId = $valueId;
        $this->accept = $accept;
        $this->template = $template;
        $this->download = $download;
        $this->boxId = uniqid();
        $this->jsName = Str::camel(str_replace(']','',str_replace('[','_',$inputName)));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (View::exists('components.input.image-alt.' . $this->template)) {
            return view('components.input.image-alt.' . $this->template);
        }
        if (View::exists('elfcms::components.input.image-alt.'.$this->template)) {
            return view('elfcms::components.input.image-alt.'.$this->template);
        }
        if (View::exists($this->template)) {
            return view($this->template);
        }
        return null;
    }
}
