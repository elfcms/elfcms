<?php

namespace Elfcms\Elfcms\View\Components\Account;

use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class Register extends \Illuminate\View\Component
{
    public $template = 'basic';

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($template = 'basic')
    {
        $this->template = $template;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (View::exists('components.account.' . $this->template . '.register')) {
            return view('components.account.' . $this->template . '.register');
        }
        if (View::exists('basic::components.account.'.$this->template . '.register')) {
            return view('basic::components.account.'.$this->template . '.register');
        }
        if (View::exists($this->template . '.register')) {
            return view($this->template . '.register');
        }
        return null;
    }
}
