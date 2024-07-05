<?php

namespace Elfcms\Elfcms\View\Components;

use Elfcms\Elfcms\Models\Menu as MenuModel;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class Menu extends Component
{
    public $menu, $template, $params;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($menu, $template='default', $params=[])
    {
        $result = $menu;
        if (is_numeric($menu)) {
            $menu = intval($menu);
            $result = MenuModel::find($menu);
        }
        if (gettype($menu) == 'string') {
            $result = MenuModel::where('code',$menu)->first();
        }
        $this->menu = $result;
        $this->template = $template;
        $this->params = $params;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (View::exists('components.menu.' . $this->template)) {
            return view('components.menu.' . $this->template);
        }
        if (View::exists('elfcms.components.menu.'.$this->template)) {
            return view('elfcms.components.menu.'.$this->template);
        }
        if (View::exists('elfcms::components.menu.'.$this->template)) {
            return view('elfcms::components.menu.'.$this->template);
        }
        if (View::exists($this->template)) {
            return view($this->template);
        }
        return null;
    }
}
