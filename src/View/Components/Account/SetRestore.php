<?php

namespace Elfcms\Elfcms\View\Components\Account;

use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class SetRestore extends \Illuminate\View\Component
{
    public $template = 'basic',
        $token;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($template = 'basic', $token)
    {
        $this->template = $template;
        $this->token = $token;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (View::exists('components.account.' . $this->template . '.setrestore')) {
            return view('components.account.' . $this->template . '.setrestore');
        }
        if (View::exists('basic::components.account.'.$this->template . '.setrestore')) {
            return view('basic::components.account.'.$this->template . '.setrestore');
        }
        if (View::exists($this->template . '.setrestore')) {
            return view($this->template . '.setrestore');
        }
        return null;
    }
}
