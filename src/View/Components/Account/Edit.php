<?php

namespace Elfcms\Elfcms\View\Components\Account;

use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class Edit extends \Illuminate\View\Component
{
    public $template = 'basic', $user;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($user, $template = 'basic')
    {
        $this->template = $template;
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (View::exists('components.account.' . $this->template . '.edit')) {
            return view('components.account.' . $this->template . '.edit');
        }
        if (View::exists('basic::components.account.'.$this->template . '.edit')) {
            return view('basic::components.account.'.$this->template . '.edit');
        }
        if (View::exists($this->template . '.edit')) {
            return view($this->template . '.edit');
        }
        return null;
    }
}
