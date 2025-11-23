<?php

namespace App\Http\Requests;


use App\Rules\Mobile;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'email' => [
                'nullable',
                'email',
                'unique:users,email',
                'required_without:phone',
            ],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'phone' => [
                'nullable',
                'string',
                'unique:users,phone',
                'required_without:email',
                new Mobile()
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $fields = $this->only(['email', 'phone']);
            if (count($fields) == 0 or count($fields) == 2) {
                $validator->errors()->add('email', 'Either email or phone must be provided, but not both.');
            }
            return $validator;
        });
    }
}
