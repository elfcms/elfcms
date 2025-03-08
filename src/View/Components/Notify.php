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
    public function __construct($type = 'info', $title = '', $text = '', $template='default', $close = true, $delay = 5000, $time = 500, $params=[])
    {
        $this->type = $type ?? $params['type'] ?? 'info';
        if (!in_array($this->type,['info', 'success', 'warning', 'error'])) $this->type = 'info';
        $this->title = $title ?? $params['title'] ?? '';
        $this->text = $text ?? $params['text'] ?? '';
        $this->close = true;
        $this->close = $close;
        if (isset($params['close'])) {
            $this->close = $params['close'];
        }
        if (gettype($this->close) == 'string' && in_array($this->close,['true','false'])) {
            //
        }
        elseif($this->close) {
            $this->close = 'true';
        }
        else {
            $this->close = 'false';
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
