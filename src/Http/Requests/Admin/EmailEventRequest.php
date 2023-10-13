<?php

namespace Elfcms\Elfcms\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EmailEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $result = [
            'name' => 'required|unique:Elfcms\Elfcms\Models\EmailEvent,name',
            'code' => 'required|unique:Elfcms\Elfcms\Models\EmailEvent,code',
            'description' => 'nullable',
            'subject' => 'nullable',
            'content' => 'nullable',
            'contentparams' => 'nullable',
            'view' => 'nullable',
        ];

        if($this->_method && strtoupper($this->_method) == 'PUT') {
            $result['name'] = 'required';
            $result['code'] = 'required';
        }

        return $result;
    }
}
