<?php

namespace Elfcms\Elfcms\View\Components;

use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class Notify extends Component
{
    public $type, $title, $text, $template, $close, $delay, $time, $params;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type = 'info', $title = '', $text = '', $template='default', $close = null, $delay = 5000, $time = 500, $params=[])
    {
        $this->type = $type ?? $params['type'] ?? 'info';
        if (!in_array($this->type,['info', 'success', 'warning', 'error'])) $this->type = 'info';
        $this->title = $title ?? $params['title'] ?? '';
        $this->text = $text ?? $params['text'] ?? '';
        $this->close = true;
        if ($close !== null) {
            $this->close = $close;
        }
        elseif (isset($params['close'])) {
            $this->close = $params['close'];
        }
        $this->delay = $delay ?? $params['delay'] ?? 5000;
        $this->time = $time ?? $params['time'] ?? 500;
        $this->template = $template ?? $params['template'] ?? 'default';
        $this->params = $params;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (View::exists('components.notify.' . $this->template)) {
            return view('components.notify.' . $this->template);
        }
        if (View::exists('elfcms.components.notify.'.$this->template)) {
            return view('elfcms.components.notify.'.$this->template);
        }
        if (View::exists('elfcms::components.notify.'.$this->template)) {
            return view('elfcms::components.notify.'.$this->template);
        }
        if (View::exists($this->template)) {
            return view($this->template);
        }
        return null;
    }
}
