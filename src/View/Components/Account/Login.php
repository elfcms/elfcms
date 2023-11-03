<?php

namespace Elfcms\Elfcms\View\Components\Account;

use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class Login extends \Illuminate\View\Component
{
    public $template = 'default';

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($template = 'default')
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
        if (View::exists('components.account.' . $this->template . '.login')) {
            return view('components.account.' . $this->template . '.login');
        }
        if (View::exists('elfcms::components.account.'.$this->template . '.login')) {
            return view('elfcms::components.account.'.$this->template . '.login');
        }
        if (View::exists($this->template . '.login')) {
            return view($this->template . '.login');
        }
        return null;
    }
}
