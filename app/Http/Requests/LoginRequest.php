<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => [
                'nullable',
                'email',
                'required_without:phone',
            ],
            'password' => ['required', 'string', 'min:6'],
            'phone' => [
                'nullable',
                'string',
                'required_without:email',
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('email') && $this->filled('phone')) {
                $validator->errors()->add('email', 'Provide only one of email or phone.');
            }
        });
    }
}
