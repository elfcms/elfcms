<?php

namespace Elfcms\Elfcms\View\Components\Input;

use Illuminate\Support\Facades\View;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class Checkbox extends Component
{
    public $checked, $code, $style, $template, $label, $color;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($checked = false, $code = null, $style = null, $label = null, $color = null, $template='default')
    {
        if (!in_array($style,['green','blue','darkblue','red'])) $style = null;
        if (!empty($color)) {
            $color = 'style="color:' . $color. ';"';
        }
        $this->checked = $checked;
        $this->code = $code ?? uniqid();
        $this->style = $style;
        $this->label = $label;
        $this->color = $color;
        $this->template = $template;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (View::exists('components.input.checkbox.' . $this->template)) {
            return view('components.input.checkbox.' . $this->template);
        }
        if (View::exists('elfcms::components.input.checkbox.'.$this->template)) {
            return view('elfcms::components.input.checkbox.'.$this->template);
        }
        if (View::exists($this->template)) {
            return view($this->template);
        }
        return null;
    }
}
