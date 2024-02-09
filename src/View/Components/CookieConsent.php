<?php

namespace Elfcms\Elfcms\View\Components;

use Elfcms\Elfcms\Models\CookieCategory;
use Elfcms\Elfcms\Models\CookieSetting;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class CookieConsent extends Component
{
    public $active, $text, $use_default_text, $privacy_path, $categories, $template, $consented;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($template='default')
    {
        /* $this->active = $active ?? 0;
        $this->text = $value ?? $inputData['value'];
        $this->code = $code ?? $inputData['code'];
        $this->accept = $accept;
        $this->template = $template;
        $this->download = $download;
        $this->boxId = uniqid();
        $this->jsName = Str::camel(str_replace(']','',str_replace('[','_',$this->code))); */
        $settings = CookieSetting::first() ?? new CookieSetting();
        $this->categories = CookieCategory::all()->toArray() ?? [];
        $this->active = $settings->active ?? 0;
        $this->use_default_text = $settings->use_default_text ?? 0;
        $this->privacy_path = $settings->privacy_path;
        if (!empty($this->use_default_text) || empty($this->text)) {
            if (!empty($this->privacy_path)) {
                $this->text = __('elfcms::default.default_cookie_text_link',['privacy_link'=>$this->privacy_path]);
            }
            else {
                $this->text = __('elfcms::default.default_cookie_text');
            }
        }
        else {
            $this->text = $settings->text;
        }
        $this->template = $template;
        $this->consented = json_decode(Cookie::get('cookie_consent'));
        if (!empty($this->categories) && !empty($this->consented) && !empty($this->consented['categories'])) {
            foreach ($this->categories as $key => $category) {
                $consented = false;
                if (!empty($this->consented) && !empty($this->consented['categories']) && in_array($category['name'], $this->consented)) {
                    $consented = true;
                }
                $this->categories[$key]['consented'] = $consented;
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (empty($this->active)) return null;

        if (View::exists('components.cookie-consent.' . $this->template)) {
            return view('components.cookie-consent.' . $this->template);
        }
        if (View::exists('elfcms.components.cookie-consent.'.$this->template)) {
            return view('elfcms.components.cookie-consent.'.$this->template);
        }
        if (View::exists('elfcms::components.cookie-consent.'.$this->template)) {
            return view('elfcms::components.cookie-consent.'.$this->template);
        }
        if (View::exists($this->template)) {
            return view($this->template);
        }
        return null;
    }
}
