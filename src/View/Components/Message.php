<?php

namespace Elfcms\Elfcms\View\Components;

use Elfcms\Elfcms\Models\Message as ModelsMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class Message extends Component
{
    public $message, $template, $show = true, $popup = '';

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($message, $template='default')
    {
        $result = $message;
        if (is_numeric($message)) {
            $message = intval($message);
            $result = ModelsMessage::find($message);
        }
        if (gettype($message) == 'string') {
            $result = ModelsMessage::where('code',$message)->first();
        }
        if ($result) {
            if ($result->active == 0) {
                $this->show = false;
            }
            else {
                if (!empty($result->date_from) && Carbon::parse($result->date_from) > Carbon::now()) {
                    $this->show = false;
                }
                if (!empty($result->date_to) && Carbon::parse($result->date_to) < Carbon::now()) {
                    $this->show = false;
                }
            }

            if ($result->is_popup != 0) {
                $this->popup = 'popup.';
            }
        }
        $this->message = $result;
        $this->template = $template;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if ($this->show === false) {
            return null;
        }

        if (View::exists('components.message.' . $this->popup . $this->template)) {
            return view('components.message.' . $this->popup . $this->template);
        }
        if (View::exists('elfcms::components.message.' . $this->popup . $this->template)) {
            return view('elfcms::components.message.' . $this->popup . $this->template);
        }
        if (View::exists($this->template)) {
            return view($this->template);
        }
        return null;
    }
}
