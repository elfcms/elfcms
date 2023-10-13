<?php

namespace Elfcms\Elfcms\Aux;

use Elfcms\Elfcms\Events\SomeMailEvent;
use Elfcms\Elfcms\Models\EmailEvent;
use Elfcms\Elfcms\Models\Form;
use Elfcms\Elfcms\Models\FormResult;
use Illuminate\Http\Request;

class FormSaver
{

    public $name, $text, $success, $id, $redirect_to;

    public function __construct()
    {
        $this->name = null;
        $this->text = __('elfcms::default.form_saving_general_error');
        $this->success = false;
        $this->id = null;
    }

    /**
     * Store a newly data from form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $this
     */
    public function save(Request $request)
    {
        $result = FormResult::create([
            'form_id' => $request->form_id,
            'form_data' => json_encode($request->all())
        ]);

        $form = Form::find($request->form_id);

        if ($result && $result->id) {
            $this->success = true;
            $this->name = 'success';
            $this->text = __('elfcms::default.form_saving_success');
            if (!empty($form->success_text)) {
                $this->text = $form->success_text;
            }
            $this->id = $result->id;
        } else {
            $this->success = false;
            $this->name = 'error';
            $this->text = __('elfcms::default.form_saving_general_error');
            if (!empty($form->error_text)) {
                $this->text = $form->error_text;
            }
            $this->id = null;
        }
        $this->redirect_to = null;
        if (!empty($request->redirect_to)) {
            $this->redirect_to = $request->redirect_to;
        } elseif (!empty($form->redirect_to)) {
            $this->redirect_to = $form->redirect_to;
        }

        if ($result && $result->id && $form && $form->event_id) {
            $event = EmailEvent::find($form->event_id);
            if ($event && $event->code) {
                $data = $this->read($result);
                event(new SomeMailEvent($event->code, ['params' => ['form' => $form, 'data' => $data]]));
            }
        }

        return $this;
    }

    public function toJson()
    {
        return $this->toJson();
    }

    /**
     * Read saved form data and join with fields params.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array $data
     */
    public static function read(FormResult $formResult)
    {

        $data = [];

        $resultData = json_decode($formResult->form_data, true);
        /* foreach ($resultData as $name => $value) {
            $data[$name] = $value;
        } */

        $form = Form::find($formResult->form_id);
        if (!empty($form->fields)) {
            foreach ($form->fields as $field) {
                $value = empty($resultData[$field->name]) ? null : $resultData[$field->name];
                $data[$field->name] = [
                    'title' => $field->title,
                    'description' => $field->description,
                    'type' => $field->type->name,
                    'value' => $value
                ];
            }
        }

        return $data;
    }
}
