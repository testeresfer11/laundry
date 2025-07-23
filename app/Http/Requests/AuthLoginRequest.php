<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'     => ['required','email',
                            Rule::exists('users', 'email')->where(function ($query) {
                                $query->where('status',1);
                            })],
            'password'  => 'required|min:8'
        ];
    }

    public function messages() 
    {
        return [
            'email.exists' => 'Invalid Credentials',
        ];
    }
}
