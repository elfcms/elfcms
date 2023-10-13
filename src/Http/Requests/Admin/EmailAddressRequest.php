<?php

namespace Elfcms\Elfcms\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EmailAddressRequest extends FormRequest
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
        if($this->_method && strtoupper($this->_method) == 'PUT') {
            return [
                'name' => 'required',
                'email' => 'required',
                'description' => 'nullable'
            ];
        }
        return [
            'name' => 'required|unique:Elfcms\Elfcms\Models\EmailAddress,name',
            'email' => 'required|unique:Elfcms\Elfcms\Models\EmailAddress,email',
            'description' => 'nullable'
        ];
    }

    protected function prepareForValidation(): void
    {
        //dd($this);
    }
}
