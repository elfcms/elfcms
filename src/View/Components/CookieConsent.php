<?php

namespace Elfcms\Elfcms\View\Components;

use Elfcms\Elfcms\Models\CookieCategory;
use Elfcms\Elfcms\Models\CookieSetting;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;
use Illuminate\Support\Str;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

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
        $settings = CookieSetting::first() ?? new CookieSetting();
        $this->categories = CookieCategory::all()->toArray() ?? [];
        $this->active = $settings->active ?? 0;
        $this->use_default_text = $settings->use_default_text ?? 0;
        $this->privacy_path = $settings->privacy_path;
        if (!empty($this->use_default_text) || empty($settings->text)) {
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
        $this->consented = json_decode(Cookie::get('cookie_consent'),true);
        if ($this->consented === null) {
            $cookie = null;
            try {
                $cookie = Crypt::decryptString(Cookie::get('cookie_consent'));
                if ($cookie) {
                    $cookieArray = explode('|', $cookie);
                    if (!empty($cookieArray[1])) {
                        $this->consented = json_decode($cookieArray[1],true);
                    }
                }
            }
            catch (DecryptException $e) {
                //
            }
        }
        if (!empty($this->categories)) {
            foreach ($this->categories as $key => $category) {
                $consented = false;
                if (!empty($this->consented) && !empty($this->consented['categories']) && !empty($this->consented['categories'][$category['name']])) {
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
