<?php

namespace Elfcms\Elfcms\View\Components;

use Elfcms\Elfcms\Models\FragmentItem;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class Fragment extends Component
{
    public $item, $theme;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item, $theme='default')
    {
        if (is_numeric($item)) {
            $item = intval($item);
            $item = FragmentItem::with('options')->find($item);
        }
        elseif (gettype($item) == 'string') {
            $item = FragmentItem::where('code',$item)->with('options')->first();
        }
        $this->item = $item;
        $this->theme = $theme;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (View::exists('components.fragment.' . $this->theme)) {
            return view('components.fragment.' . $this->theme);
        }
        if (View::exists('elfcms.components.fragment.'.$this->theme)) {
            return view('elfcms.components.fragment.'.$this->theme);
        }
        if (View::exists('elfcms::components.fragment.'.$this->theme)) {
            return view('elfcms::components.fragment.'.$this->theme);
        }
        if (View::exists($this->theme)) {
            return view($this->theme);
        }
        return null;
    }
}
