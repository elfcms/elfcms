<?php

namespace Elfcms\Elfcms\View\Components;

use Elfcms\Elfcms\Models\Form as FormModel;
use Elfcms\Elfcms\Models\FormFieldType;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class Form extends \Illuminate\View\Component
{
    public $form, $submit, $reset, $template;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($form, $template = 'default')
    {
        $result = $form;
        if (is_numeric($form)) {
            $form = intval($form);
            $result = FormModel::find($form);
        }
        if (gettype($form) == 'string') {
            $result = FormModel::where('code', $form)->first();
        }
        if ($result) {
            $this->form = $result;
            $submit = $result->fields()->where('type_id', FormFieldType::where('name', 'submit')->first()->id)->first();
            $this->submit = $submit;
            $reset = $result->fields()->where('type_id', FormFieldType::where('name', 'reset')->first()->id)->first();
            $this->reset = $reset;
            if (!empty($result->redirect_to)) {
                if (Route::has($result->redirect_to)) {
                    $this->form->redirect_to = route($result->redirect_to);
                } else {
                    $this->form->redirect_to = $result->redirect_to;
                }
            }
            if (!empty($result->action)) {
                if (Route::has($result->action)) {
                    $this->form->action = route($result->action);
                } else {
                    $this->form->action = $result->action;
                }
            } else {
                if (Route::has('form-send')) {
                    $this->form->action = route('form-send');
                } else {
                    $this->form->action = route(Route::currentRouteName());
                }
            }
        }

        $this->template = $template;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (View::exists('components.form.' . $this->template)) {
            return view('components.form.' . $this->template);
        }
        if (View::exists('elfcms.components.form.' . $this->template)) {
            return view('elfcms.components.form.' . $this->template);
        }
        if (View::exists('elfcms::components.form.' . $this->template)) {
            return view('elfcms::components.form.' . $this->template);
        }
        if (View::exists($this->template)) {
            return view($this->template);
        }
        return null;
    }
}
