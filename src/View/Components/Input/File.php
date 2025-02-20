<?php

namespace Elfcms\Elfcms\View\Components\Input;

use Illuminate\Support\Facades\View;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class File extends Component
{
    //public $params, $boxId, $template;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public array $params, public string|null $template, public string|null $boxId, $value = null, $name = null, $accept = null, $download = false, $height = null, $width = null)
    {
        $params['name'] = $params['name'] ?? $name;
        $params['value'] = $params['value'] ?? $value;
        $params['id'] = $params['id'] ?? $params['name'];
        $params['value_name'] = $params['value_name'] ?? $params['name'] . '_path';
        $params['accept'] = $params['accept'] ?? $accept;
        $params['template'] = $params['template'] ?? $template;
        $params['download'] = isset($params['download']) ? (bool)$params['download'] : (bool)$download;
        $params['code'] = $params['code'] ?? $params['name'];
        $params['width'] = $params['width'] ?? $width;
        $params['height'] = $params['height'] ?? $height;
        $params['extension'] = empty($params['value']) ? null : fsExtension($params['value']);
        $params['icon'] = empty($params['extension']) ? null : fsIcon($params['extension']);
        $params['isImage'] = in_array(strtolower($params['extension']),['jpg','jpeg','gif','png','bmp','webp','svg']);
        $this->params = $params;
        $this->boxId = uniqid();
        $this->template = $template ?? 'default';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (View::exists('components.input.file.' . $this->template)) {
            return view('components.input.file.' . $this->template);
        }
        if (View::exists('elfcms.components.input.file.' . $this->template)) {
            return view('elfcms.components.input.file.' . $this->template);
        }
        if (View::exists('elfcms::components.input.file.' . $this->template)) {
            return view('elfcms::components.input.file.' . $this->template);
        }
        if (View::exists($this->template)) {
            return view($this->template);
        }
        return null;
    }
}
